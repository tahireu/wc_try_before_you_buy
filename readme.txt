=== WC Try Before You Buy ===
Contributors: tahireu
Tags: woocommerce, webshop, shop, e-shop, web-shop, extension, try-before-you-buy
Requires at least: 3.1.0
Tested up to: 4.9.8
Stable tag: 1.0.0
License: GPL
License URI: http://www.opensource.org/licenses/gpl-license.php

WooCommerce support for "Try Before You Buy" type of online stores - Prepare cart content for your customers from admin side and allow them to choose whether they will keep or return the product.

== Description ==

How 'Try Before You Buy' online stores works: People tells you few words about them, you (or your stylists) choose products that will fit them best, you send them those products, and they choose if they will keep them or not.
For more info, google out 'Try Before You Buy online stores'.
This plugin will extend your WooCommerce web shop to a "Try Before You Buy" type of online store.
It will:
* Hide a shop page for all users (prevent users from adding products to the cart by themselves)
* Hide all WooCommerce pages for unregistered users
* Allow you, as admin, to fill customers carts with items you'll send them via mail
* Allow your customers to choose between 'keep' and 'return' product, which will automatically correct the price for them
* Allow your customers to tell you what was the 'return reason' (it will be displayed in admin orders pages)
* Overview of prepared carts content - possibility to see the status of prepared carts pre user and delete items added by mistake

Keep in mind this and MVP version of this software, supporting only basic functionality. If you are interested about some extended or custom functionality, please contact me at https://github.com/tahireu or tahiri.damir[at]gmail.com

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'WC Try Before You Buy'
3. When you find it, click 'install', and after that, click 'activate'
4. On each WooCommerce Product page you'll find WC Try Before You Buy meta box which will allow you to fill customers carts with items you'll send them via mail
5. On 'WooCommerce > Prepared Carts' page, you will be able to see the status of prepared carts pre user and delete items added by mistake
6. On 'Cart' page, your customers will be able to choose between 'keep' and 'return' product, and to give information about eventual 'return reason'

= From WordPress.org =

1. Download 'WC Try Before You Buy'.
2. Upload the 'WC Try Before You Buy' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. When you upload it, click 'install', and after that, click 'activate'
4. On each WooCommerce Product page you'll find WC Try Before You Buy meta box which will allow you to fill customers carts with items you'll send them via mail
5. On 'WooCommerce > Prepared Carts' page, you will be able to see the status of prepared carts pre user and delete items added by mistake
6. On 'Cart' page, your customers will be able to choose between 'keep' and 'return' product, and to give information about eventual 'return reason'

== Known Issues ==

* Plugin is not compatible with 'Enable stock management at product level' WooCommerce option
* Cart page layout may vary depending on which theme is used

== Plans For The Future ==

* Make it quantity/stock/inventory compatible
* Create pagination and search on Prepared Carts Overview page
* Add languages support
* Add some kind of multi-step form questionnaire plugin to be installed along with this plugin
* Make it to work even if AJAX fail
* Standardize 'Cart' page layout as much as possible for various themes
* Add 'dismiss' option to info messages

If you want some of the functionality mentioned above to be implemented, or you have your own suggestions, please contact me at https://github.com/tahireu or tahiri.damir[at]gmail.com