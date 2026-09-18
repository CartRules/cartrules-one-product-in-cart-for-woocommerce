<?php

defined( 'ABSPATH' ) || exit;

/**
 * Adds this module's "One Product in Cart" section to the shared "CartRules" tab.
 */

add_filter( 'woocommerce_get_sections_cartrules', 'cartrules_opic_add_settings_section' );
add_filter( 'woocommerce_get_settings_cartrules', 'cartrules_opic_settings_fields', 10, 2 );

function cartrules_opic_add_settings_section( $sections ) {
	$sections['opic'] = __( 'One Product in Cart', 'cartrules-one-product-in-cart-for-woocommerce' );

	return $sections;
}

function cartrules_opic_settings_fields( $settings, $section_id ) {
	if ( 'opic' !== $section_id ) {
		return $settings;
	}

	return array(
		array(
			'title' => __( 'One Product in Cart', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'type'  => 'title',
			'desc'  => sprintf(
				/* translators: %1$s: link to the CartRules PRO plugin page. */
				__( 'Prevent customers from mixing different products in the same cart. Need more control? %1$s puts every cart rule (category, tag, brand, shipping class, product type, individual products or variations) in one place. Each rule can target specific products instead of "any one at a time" (for example, only keep a specific bundle separate), and you get unlimited custom rules with full control over when each one applies. It also checks for other cart restrictions already running on your store, whether from another CartRules plugin or your own custom code, so nothing quietly conflicts.', 'cartrules-one-product-in-cart-for-woocommerce' ),
				'<a href="https://cartrules.com/product/cartrules-one-in-cart-pro/" target="_blank" rel="noopener noreferrer">CartRules One in Cart PRO</a>'
			),
			'id'    => 'cartrules_opic_settings_title',
		),
		array(
			'title'   => __( 'Enable restriction', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'desc'    => __( 'Only allow one product in the cart at a time', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'id'      => 'cartrules_opic_enabled',
			'default' => 'no',
			'type'    => 'checkbox',
		),
		array(
			'title'   => __( 'When a different product is added', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'desc'    => __( 'Choose what happens when a customer tries to add a different product', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'id'      => 'cartrules_opic_mode',
			'default' => 'deny',
			'type'    => 'select',
			'class'   => 'wc-enhanced-select',
			'options' => array(
				'deny'    => __( 'Block the new product and show an error', 'cartrules-one-product-in-cart-for-woocommerce' ),
				'replace' => __( 'Empty the cart first, then add the new product', 'cartrules-one-product-in-cart-for-woocommerce' ),
			),
		),
		array(
			'title'    => __( 'Blocked message', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'desc_tip' => __( 'Shown when a product is blocked. Use {product} for the product already in the cart.', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'id'       => 'cartrules_opic_deny_message',
			'default'  => __( 'You already have "{product}" in your cart. Please remove it first, or complete that order separately.', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'type'     => 'textarea',
			'css'      => 'width:100%; height: 75px;',
		),
		array(
			'title'    => __( 'Replaced message', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'desc_tip' => __( 'Shown when the cart is emptied and replaced. Use {product} for the product that was removed.', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'id'       => 'cartrules_opic_replace_message',
			'default'  => __( 'Your cart contained "{product}", so we replaced it with your new selection.', 'cartrules-one-product-in-cart-for-woocommerce' ),
			'type'     => 'textarea',
			'css'      => 'width:100%; height: 75px;',
		),
		array(
			'type' => 'sectionend',
			'id'   => 'cartrules_opic_settings_end',
		),
	);
}
