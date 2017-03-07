=== Wish List for WooCommerce ===
Contributors: algoritmika,karzin,anbinder
Tags: woocommerce,wishlist,wish list
Requires at least: 4.4
Tested up to: 4.7
Stable tag: 1.1.5
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Let your visitors save and share the products they love on your WooCommerce store with a Wish List

== Description ==

**Wish list for Woocommerce** offers some options to add products to a wish list.
There is a bunch of settings that can be customized making it flexible enough to be adapted to any theme and to any kind of design.

**Check some of its features:**

* Minimalist design
* Sharing on social networks (optional)
* Icons from FontAwesome (optional)
* Choose where wish list buttons will appear
* Possibility to add items as unlogged users
* A Widget that points to the Wish list page

= Shortcodes =
*  **[alg_wc_wl]** Displays the wish list page

= Translated to =
* Portuguese

= Feedback =
* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!

= More =
If you are interested in contributing - head over to the [Wish List for WooCommerce plugin GitHub Repository](https://github.com/algoritmika/wish-list-for-woocommerce) to find out how you can pitch in.

= Pro version =

Do you like the free version of this plugin? Imagine what the Pro version can do for you!
Check it out [here](http://coder.fm/item/wish-list-woocommerce/ "Wish list for WooCommerce Pro")!
http://coder.fm/item/wish-list-woocommerce/

**Take a look on some of its features:**

* Choose custom icons from FontAwesome for all your buttons and notifications
* Customize the default button in all ways (background and hover color, font weight, size, margin and more)
* Choose precisely where thumbnail button will be displayed inside product image and also style it the way you want
* Style your notifications
* Choose your social icon colors
* Customize all messages displayed to users easily
* Use tooltips to make this plugin even easier to users

== Installation ==

1. Upload the entire 'wish-list-for-woocommerce' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Start by visiting plugin settings at WooCommerce > Settings > Wish List.

== Screenshots ==

1. Use a minimalist button on product loop to add or remove items from wish list
2. Use a minimalist button on single product page to add or remove items from wish list
3. A ready-to-use wish list page
4. In general options you can turn on/off the plugin and choose to load icons from font awesome
5. Choose if you want to share your wish list on social networks
6. Control precisely where your wishlist buttons will appear
7. Decide what you are going to display in your wish list
8. Choose if you want to notify your users about items being added to wish list

== Changelog ==

= 1.1.5 - 06/03/2017 =
* New screenshots
* Update readme.txt
* Stop using sessions. Now unlogged users are managed with transients and cookies

= 1.1.4 - 03/03/2017 =
* Fix WooCommerce checking bug. Now the plugin is deactivated if WooCommerce is not active
* Show a notice when WooCommerce is not active
* Show a notice when Pro version is active
* Remove write_log function
* Better translation
* Add option to choose the wish list page
* Create widget displaying a link to wish list page
* Change text domain to match plugin slug so the plugin can be translated from translate.wordpress.org

= 1.1.3 - 17/02/2017 =
* Minor changes

= 1.1.2 - 06/02/2017 =
* Information about the Pro version on General Tab

= 1.1.1 - 01/02/2017 =
* Better CSS for notification
* New event (alg_wc_wl_notification_close) triggered on notification close
* Notification box is now closing on esc key
* New filter (alg_wc_wl_toggle_item_ajax_response) on "class-alg-wc-wish-list-ajax.php" to handle a possible override
* New tab on admin to handle notification options
* New option to enable notifications on desktop
* New option to enable notifications on mobile
* New option to show wish list link on notification after adding a product to wish list
* New option to Show an Ok button so the user has one more option to close notification

= 1.1.0 - 30/01/2017 =
* Created a new filter (alg_wc_wl_toggle_item_texts) to customize texts from remove and add items to wish list
* Passing text to default button template dynamically with "add_label" and "remove_label" params. They can be filtered using "alg_wc_wl_locate_template_params" hook
* New option to show an "Add to cart" button on wish list
* Created a function on JS to control thumb button position properly
* JS is now receiving an option to disable / enable notification progress bar
* Notification is now receiving dynamic options via JS
* Passing an icon class to thumb buttons
* Including a new filter called "alg_wc_wl_locate_template_params" for filtering params passed to templates
* Added a class "alg-wc-wl-btn-wrapper" on default button template to control alignment
* Now it's possible to pass an icon class to default buttons
* Auto deactivating plugin if Pro version is active
* Better function names for autoloading and locating templates
* Better prefix for checking autoloading function

= 1.0.0 - 23/01/2016 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.