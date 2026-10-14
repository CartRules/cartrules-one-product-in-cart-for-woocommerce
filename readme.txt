=== CartRules One Product in Cart for WooCommerce ===
Contributors: businessbloomer
Tags: woocommerce, cart, one product, restrict cart, checkout
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin ensures customers can only buy one product at a time.

== Description ==

This plugin stops customers from mixing different products in the same order. If a product is already in the cart, adding a different product will either be blocked, or the cart will be emptied first, depending on the option you choose. Different variations of the same product are treated as the same product, so they're never blocked from sitting in the cart together.

This is useful for stores that sell a small number of standalone products or bundles that should never be combined with anything else in the same order, for example a single service booking, a made-to-order item, or a product with its own dedicated fulfillment process.

Once activated, go to WooCommerce > Settings > CartRules > One Product in Cart to turn the restriction on and choose what should happen.

Works with both the classic, shortcode-based cart and checkout, and the newer WooCommerce Cart and Checkout blocks.

= Why upgrade to CartRules PRO? =

This plugin only restricts the cart to one product at a time, for every product on the store. CartRules One in Cart PRO replaces it, and every other free CartRules plugin, with a single plugin that handles every rule type and gives you more control over how each one behaves.

**Every rule type, in one plugin**

* Category, tag, brand, shipping class, product type, any custom taxonomy, or individual products and variations, instead of installing a separate plugin per rule type.
* Unlimited rules, each with its own settings, managed from one screen: WooCommerce > Settings > CartRules > One in Cart PRO.

**More control per rule**

* Scope a rule to specific products or variations instead of "any one at a time" (for example, only keep one specific bundle separate, and let everything else mix freely).
* Choose what happens when a rule is broken: block the new product with a message, or empty the cart and add it, decided separately for each rule.
* Flags other cart restrictions already running on your store, whether from a free CartRules plugin or your own custom code, so two rules never silently conflict.
* Works with both the classic cart and checkout and the WooCommerce Cart and Checkout blocks.

[Get CartRules One in Cart PRO →](https://cartrules.com/product/cartrules-one-in-cart-pro/)

== Frequently Asked Questions ==

= Is there a version that combines multiple rule types? =

Yes. CartRules One in Cart PRO lets you combine category, tag, brand, shipping class, product type, and individual product/variation restrictions in one plugin, with unlimited rules and more advanced conditions.

= Does this treat variations of the same product as different products? =

No. WooCommerce always identifies a variation by its parent product, so different variations of the same product can sit in the cart together; only a genuinely different product triggers the restriction.

= Does this work with the WooCommerce Cart and Checkout blocks, or only the classic shortcode-based cart? =

Both. The restriction is applied when a product is added to the cart, so it works the same way whether your store uses the classic cart/checkout pages or the block-based versions.

= Does this affect orders created or edited from wp-admin? =

No, the restriction only applies to the storefront cart. Orders added or changed from wp-admin are not affected.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install it through the Plugins menu in WordPress directly.
2. Activate the plugin through the Plugins menu in WordPress.
3. Go to WooCommerce > Settings > CartRules > One Product in Cart to turn the restriction on and choose what should happen.

== Changelog ==

= 1.0.1 =
* Fixed: the rule is now checked again on the cart and checkout pages, so products that reached the cart another way (a guest cart merged at login, "Order again", or another plugin) can no longer be checked out together. Carts the rule allows are not affected.

= 1.0.0 =
* Initial release
