=== WC Try Before You Buy ===
Contributors: tahireu
Tags: woocommerce, webshop, shop, e-shop, web-shop, extension, try-before-you-buy
Requires at least: 3.1.0
Tested up to: 5.0
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fill your customer's cart with products you'll send them by mail, and allow them to choose whether they will keep or return the product, and to leave you feedback about return reason.

== Description ==

This is an initial, MVP version of WooCommerce extension which will give you functionalities needed to simulate 'try before you buy' type of online store on your WordPress/WooCommerce installation.

It will allow you, as admin, to fill your customer's cart with products you'll send them by mail, and allow them to choose whether they will keep or return the product, and to leave you feedback about return reason.

This plugin DOES NOT cover other functionalities common for 'try before you buy' online stores that can already be achieved with other existing plugins, such as surveys or cart item exchange option.

How 'Try Before You Buy' online stores works: People tells you few words about them, you (or your stylists) choose products that will fit them best, you send them those products, and they choose if they will keep them or not.
For more info, google out 'Try Before You Buy online stores'.

This plugin will:
* Hide a shop page for all users (prevent users from adding products to the cart by themselves)
* Hide all WooCommerce pages for unregistered users
* Allow you, as admin, to fill customers carts with items you'll send them via mail
* Allow your customers to choose between 'keep' and 'return' product, which will automatically correct the price in their cart
* Allow your customers to tell you what was the 'return reason' (it will be displayed in admin orders pages)
* Give you an overview of all items prepared to be added to customer's carts - possibility to see the status of prepared items pre user and delete items added by mistake

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'WC Try Before You Buy'
3. When you find it, click 'install', and after that, click 'activate'
4. On each WooCommerce Product page you'll find WC Try Before You Buy meta box which will allow you to fill customers carts with items you'll send them via mail
5. On 'WooCommerce > WC Try Before You Buy Overview' page, you will be able to see the status of items prepared to be added to customer's cart and delete items added by mistake
6. On 'Cart' page, your customers will be able to choose between 'keep' and 'return' product, and to leave information about eventual 'return reason'

= From WordPress.org =

1. Download 'WC Try Before You Buy'.
2. Upload the 'WC Try Before You Buy' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. When you upload it, click 'install', and after that, click 'activate'
4. On each WooCommerce Product page you'll find WC Try Before You Buy meta box which will allow you to fill customers carts with items you'll send them via mail
5. On 'WooCommerce > WC Try Before You Buy Overview' page, you will be able to see the status of items prepared to be added to customer's cart and delete items added by mistake
6. On 'Cart' page, your customers will be able to choose between 'keep' and 'return' product, and to leave information about eventual 'return reason'

== Screenshots ==

1. Admin side - WC Try Before You Buy Admin meta box on the bottom of each WooCommerce Product page in WP admin area
2. Admin side - WC Try Before You Buy Overview page
3. Customer side - Cart page with options to keep or return product, and to give feedback about return reason
4. Customer side - Return reasons info will be displayed in Cart, Checkout, Email and Order complete pages
5. Admin side - Return reasons will be visible to admin in 'Orders > Order' page

== Changelog ==

= 1.0.1 =
* Removed unnecessary comments
* Fixed typos
* Updated info messages in TBYB meta box to be more accurate, understandable and self-explainable
* Updated info messages behavior in TBYB meta box to be more logical
* Updated and improved howto explanations on TBYB Overview page and in TBYB Meta Box
* Updated readme.txt description, stable tag, tested up to, known issues and plans for the future.
* Updated screenshots relevant to these changes

== Upgrade Notice ==

= 1.0.1 =
Main advantages of 1.0.1 are better error handling and improved info messages behavior and explanations when submitting data in TBYB meta box.
Also, this patch will improve howto explanations on both 'Product > TBYB meta box' and 'TBYB Overview' pages.

== Known Issues ==

* Plugin is not compatible with 'Stock management at product level' WooCommerce option
* Plugin is not compatible with 'Grouped' product type
* 'Delete' and 'Clear All' buttons on Overview page are not working in responsive/mobile mode in Firefox due to Firefox bug: https://bugzilla.mozilla.org/show_bug.cgi?id=1273997

== Plans For The Future ==

* Make it compatible with 'Stock management at product level' WooCommerce option
* Make it compatible with 'Grouped' products
* Add pagination and search field to WC Try Before You Buy Overview page
* Add languages support
* Add some kind of multi-step form questionnaire plugin to be installed along with this plugin
* Make it to work even if AJAX fail
* Standardize 'Cart' page layout as much as possible for various themes
* Add 'dismiss' option to info messages

If you want some of the functionality mentioned above to be implemented, or you have your own suggestions, please contact me at https://github.com/tahireu or tahiri.damir[at]gmail.com