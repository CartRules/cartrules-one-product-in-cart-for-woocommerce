<?php

defined( 'ABSPATH' ) || exit;

/**
 * Blocks or replaces cart contents when a different product is added.
 *
 * Unlike the other CartRules "one X in cart" plugins, this one has no taxonomy to look up:
 * the group a product belongs to is simply its own product ID. WooCommerce always passes the
 * *parent* product ID here, even for a variation, so different variations of the same product
 * are treated as the same group and can sit in the cart together; only a genuinely different
 * product triggers the restriction.
 */
class CartRules_OPIC_Cart_Restriction {

	/**
	 * Cart item keys staged for removal in "replace" mode, keyed by the product id that
	 * triggered the replacement. Populated during validation, consumed by handle_replace().
	 *
	 * @var array<int, array{cart_item_keys: string[], product_name: string}>
	 */
	private $pending_replacements = array();

	public function __construct() {
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'validate_add_to_cart' ), 10, 2 );
		add_action( 'woocommerce_add_to_cart', array( $this, 'handle_replace' ), 10, 2 );
		add_action( 'woocommerce_check_cart_items', array( $this, 'check_cart' ) );
	}

	/**
	 * Other plugins may also hook woocommerce_add_to_cart_validation. Removing cart items here
	 * instead of in handle_replace() would mutate the cart mid-filter-chain, so a plugin
	 * validating later would see an already-emptied cart and skip its own check.
	 *
	 * @param bool $passed
	 * @param int  $product_id
	 * @return bool
	 */
	public function validate_add_to_cart( $passed, $product_id ) {
		if ( ! $passed || 'yes' !== get_option( 'cartrules_opic_enabled', 'no' ) || WC()->cart->is_empty() ) {
			return $passed;
		}

		$cart_product_ids = array();

		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$cart_product_ids[] = (int) $cart_item['product_id'];
		}

		$cart_product_ids = array_values( array_unique( $cart_product_ids ) );

		if ( in_array( (int) $product_id, $cart_product_ids, true ) ) {
			return $passed;
		}

		$existing_product_name = get_the_title( $cart_product_ids[0] );

		if ( 'replace' === get_option( 'cartrules_opic_mode', 'deny' ) ) {
			$this->pending_replacements[ $product_id ] = array(
				'cart_item_keys' => array_keys( WC()->cart->get_cart() ),
				'product_name'   => $existing_product_name,
			);

			return true;
		}

		wc_add_notice( $this->build_deny_message( $existing_product_name ), 'error' );

		return false;
	}

	/**
	 * Items can reach the cart without add-to-cart validation: a guest cart merged at login,
	 * "Order again", or another plugin adding products directly. This replays the add-to-cart
	 * rule over the cart, item by item, so checkout blocks exactly the carts add-to-cart would
	 * have refused, and nothing it allows.
	 */
	public function check_cart() {
		if ( 'yes' !== get_option( 'cartrules_opic_enabled', 'no' ) || WC()->cart->is_empty() ) {
			return;
		}

		$seen = array();

		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$groups = array( (int) $cart_item['product_id'] );

			if ( empty( $groups ) ) {
				continue;
			}

			if ( $seen && ! array_intersect( $groups, $seen ) ) {
				wc_add_notice( $this->build_deny_message( get_the_title( $seen[0] ) ), 'error' );

				return;
			}

			$seen = array_values( array_unique( array_merge( $seen, $groups ) ) );
		}
	}

	/**
	 * Runs once WooCommerce has actually added the new item to the cart, so every plugin
	 * hooked into the validation filter has already had a chance to evaluate the original cart.
	 *
	 * @param string $cart_item_key
	 * @param int    $product_id
	 */
	public function handle_replace( $cart_item_key, $product_id ) {
		if ( ! isset( $this->pending_replacements[ $product_id ] ) ) {
			return;
		}

		$replacement = $this->pending_replacements[ $product_id ];
		unset( $this->pending_replacements[ $product_id ] );

		foreach ( $replacement['cart_item_keys'] as $conflicting_item_key ) {
			WC()->cart->remove_cart_item( $conflicting_item_key );
		}

		wc_add_notice( $this->build_replace_message( $replacement['product_name'] ), 'notice' );
	}

	/**
	 * The message options are only ever written to the database when the settings screen is
	 * saved, so get_option() needs the same fallback the settings screen shows in the
	 * textarea by default -- otherwise enabling this via wp_cli/wp option update, or a site
	 * migration that drops the option row, silently produces a blank notice.
	 */
	private function build_deny_message( $product_name ) {
		$default = __( 'You already have "{product}" in your cart. Please remove it first, or complete that order separately.', 'cartrules-one-product-in-cart-for-woocommerce' );

		return str_replace( '{product}', $product_name, get_option( 'cartrules_opic_deny_message', $default ) );
	}

	private function build_replace_message( $product_name ) {
		$default = __( 'Your cart contained "{product}", so we replaced it with your new selection.', 'cartrules-one-product-in-cart-for-woocommerce' );

		return str_replace( '{product}', $product_name, get_option( 'cartrules_opic_replace_message', $default ) );
	}
}
