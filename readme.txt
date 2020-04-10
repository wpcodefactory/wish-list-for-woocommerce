=== Wish List for WooCommerce ===
Contributors: karzin
Tags: woocommerce,wishlist,wish list
Requires at least: 4.4
Tested up to: 5.4
Stable tag: 1.6.7
Requires PHP: 5.6.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Let your visitors save and share the products they love on your WooCommerce store with a Wish List

== Description ==

**Wish List for WooCommerce** offers some options to add products to a wish list.
There is a bunch of settings that can be customized making it flexible enough to be adapted to any theme and to any kind of design.

**Check some of its features:**

* Minimalist design
* Sharing on social networks and email(optional)
* Icons from FontAwesome (optional)
* Choose where wish list buttons will appear
* Possibility to add items as unlogged users
* A Widget that points to the Wish list page
* Add / Remove items from wishlist using Ajax

== Frequently Asked Questions ==

= Are there any widgets available? =
**Wish list link** - Displays a link to the wish list page

= Are there shortcodes available? =
*  **[alg_wc_wl]** Displays the wish list page
*  **[alg_wc_wl_counter]** Show the amount of items that are in the wish list
   * Params
      * **[alg_wc_wl_counter ignore_excluded_items="true"]** - Ignores excluded items. Use it if you notice the amount of items doesn't match the wish list

= How can I contribute? Is there a github repository? =
If you are interested in contributing - head over to the [Wish List for WooCommerce plugin GitHub Repository](https://github.com/algoritmika/wish-list-for-woocommerce) to find out how you can pitch in.

= How to setup WP Rocket cache? =
If you use WP Rocket please try to add a setting on advanced tab > Never cache (cookies)?
**alg-wc-wl-user-id**

= How can I help translating it? =
You can do it through [tranlslate.wordpress](https://translate.wordpress.org/projects/wp-plugins/wish-list-for-woocommerce)

= Is there a Pro version? =
Yes, it's located [here](https://wpcodefactory.com/item/wish-list-woocommerce/ "Wish list for WooCommerce Pro")

= What can I do in the Pro version? =

**Take a look on some of its features:**

* Support
* Ignore cache. The plugin can work just fine even if you use some caching plugin, like WP Super Cache, W3 Total Cache or some other
* Stock alert - Notify users via email when products they added to wish list become available
* As an admin, see what your customers have in their wishlist
* Choose custom icons from FontAwesome for all your buttons and notifications
* Customize the default button in all ways (background and hover color, font weight, size, margin and more)
* Choose precisely where the thumbnail button will be displayed inside product image and also style it the way you want
* Style your notifications
* Choose your social icon colors
* Customize all messages displayed to users easily
* Use tooltips to make this plugin even easier to users
* Save product attributes on wish list
* Add option to hide/show thumb or default button by product tag
* Display product images in emails
* Allow / Disallow Unlogged users from interacting with the Wish List
* Add a column on admin users list informing which customers have added items to the Wish List
* Add a column on the admin products list informing how many times a product has been added to the Wish List

= Can I see what the Pro version is capable of? =
* After installing the free version of this plugin, you can see the Pro version features in **WooCommerce > Settings > Wish List > Pro version**

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

= 1.6.7 - 10/04/2020 =
* Fix Font Awesome URL

= 1.6.6 - 08/04/2020 =
* Fix thumb icon on single product page
* Fix possible javascript error on product page
* Fix remove icon

= 1.6.5 - 07/04/2020 =
* Add option to load FontAwesome from a specific URL
* Update FontAwesome icons
* New Pro Feature: Add a column on admin users list informing which customers have added items to the Wish List
* New Pro Feature: Add a column on the admin products list informing how many times a product has been added to the Wish List

= 1.6.4 - 01/04/2020 =
* Add `amount` param for `sc_alg_wc_wl_counter` shortcode
* WC tested up to: 4.0
* WP Tested up to: 5.4

= 1.6.3 - 16/02/2020 =
* Add 'alg_wc_wl_can_toggle_unlogged' filter
* Improve actions on JS
* Inform about 'Allow Unlogged Users' option on pro version

= 1.6.2 - 29/01/2020 =
* Update icon classes from FontAwesome to the most recent version
* WC tested up to: 3.9

= 1.6.1 - 08/01/2020 =
* Simplify translation method
* Update German translation
* Stop removing metadata on plugin uninstall
* WC tested up to: 3.8

= 1.6.0 - 20/11/2019 =
* Improve compatibility with Timber
* Add 'alg_wc_wl_show_default_btn' hook
* Add 'alg_wc_wl_show_thumb_btn' hook
* Fix wish list access from guest users
* Fix displaying social options from other users
* Tested up to: 5.3

= 1.5.9 - 26/09/2019 =
* Make Copy to clipboard option disabled by default
* Update translation files
* Fix share url when shortlink is not available
* Add `alg_wc_wl_fa_icon_class` filter with 'class' and 'email' parameters
* WC tested up to: 3.7

= 1.5.8 - 08/07/2019 =
* Fix warning on Wish List Tab
* Fix extra white-space

= 1.5.7 - 30/06/2019 =
* Replace plugin name on Composer
* Add new option to copy Wish List URL to clipboard

= 1.5.6 - 17/05/2019 =
* Add new option to control wish list tab priority on My Account page
* Fix product name on Wish List
* Change author
* WC tested up to: 3.6
* WP Tested up to: 5.2

= 1.5.5 - 16/03/2019 =
* Add option to improve control over thumb button on loop

= 1.5.4 - 08/03/2019 =
* Add function to remove item from DOM if clicked from a Wish List created by a WooCommerce template
* Increase wish list tab priority on my account
* Tested up to: 5.1

= 1.5.3 - 29/12/2018 =
* Translate endpoints with WPML
* Update WooCommerce tested up to
* Update WordPress tested up to

= 1.5.2 - 03/12/2018 =
* Improve email template
* Toggle wish list item by url with 'wishlist_toggle' parameter

= 1.5.1 - 03/10/2018 =
* Improve translation function using the init hook
* Make translation function compatible with Polylang

= 1.5.0 - 13/09/2018 =
* Improve 'Frontend ajax url' option
* Add [alg_wc_wl] shortcode description on plugin's settings

= 1.4.41 - 19/07/2018 =
* Fix Izitoast script order

= 1.4.4 - 15/06/2018 =
* Add wpml configuration file

= 1.4.3 - 29/05/2018 =
* Improve default button position after product thumbnail
* Update WC tested up to

= 1.4.2 - 10/04/2018 =
* Fix issue where variable product ids are used instead of product ids

= 1.4.1 - 02/04/2018 =
* Delete wish list from unlogged user when register or login

= 1.4.0 - 29/03/2018 =
* Encrypt user id from query vars when accessing wish list from other people

= 1.3.9 - 23/03/2018 =
* Improve cookie handling on https sites
* Save wishlist on login
* Update WooCommerce tested up to

= 1.3.8 - 16/01/2018 =
* Remove email sharing link
* Change facebook sharing title parameter

= 1.3.7 - 15/12/2017 =
* Fix click on iphone
* Tested up to Wordpress version 4.9
* Tested up to WooCommerce version 3.2.5
* Fix wish list slug on my account page

= 1.3.6 - 20/11/2017 =
* Fix thumb button z-index
* Fix click on iphone
* Improve translation function

= 1.3.5 - 25/10/2017 =
* Fix php notice
* Fix conflict with the tip

= 1.3.4 - 09/10/2017 =
* Add text parameters to email sharing
* Improve css classes for different themes
* Globalize thumb button position function
* Update Izitoast
* Improve function that updates wish list counting
* Improve cookie to get unlogged user id
* Add translatable text in "Send wish list by email button"
* Improve thumb button positioning

= 1.3.3 - 20/09/2017 =
* Fix Polylang plugin compatibility
* Improve email sharing

= 1.3.2 - 23/08/2017 =
* Update template function
* New info about pro (stock-alert)
* Fix font awesome icons height
* Fix image height on wish list

= 1.3.1 - 20/07/2017 =
* Update info about pro version (Add option to change the pre-filled email textarea; Add wish list on user profile page;)

= 1.3.0 - 05/07/2017 =
* Fix [alg_wc_wl_counter] shortcode for unlogged users
* Hide social icons on wish list if there aren't wish list items
* Update wish list counter via Ajax
* Tested up to WordPress 4.8

= 1.2.10 - 29/06/2017 =
* Make the email option work with cache option
* Create a shortcode to show the amount of items that are in the wish list
* Add info about ignoring cache on Pro version

= 1.2.9 - 28/06/2017 =
* Update autoprefixer gulp module
* Fix default button alignment on safari
* Fix thumb button click on IOS
* Add option to add/remove loading icon
* Update izitoast to version 1.1.2
* Update thumb icon position on window resize

= 1.2.8 - 19/06/2017 =
* Fix wish list page creation on plugin install
* Add Wish list tab on My Account page

= 1.2.7 - 19/05/2017 =
* Improve default button style
* Fix error regarding the empty() function on Alg_WC_Wish_List_Item class

= 1.2.6 - 11/05/2017 =
* Add info on readme about the Widgets
* Remove metabox class
* Create function alg_wc_wl_get_toggle_wishlist_item_data() to pass parameters to toggle wish list items
* New action "alg_wc_wl_toggle_wish_list_item" after an item is toggled
* Change coder.fm link to wpcodefactory.com
* Save product attributes on database
* Save product attributes from unlogged user on registry
* Fix wish list page id if using the polylang plugin

= 1.2.5 - 13/04/2017 =
* Improve plugin description
* Add images about the pro version

= 1.2.4 - 11/04/2017 =
* Add images about the pro version
* Change thumb button position now that WooCommerce 3.0 uses a Magnifying glass icon on top right position
* Change thumb button color to red when an item is on wish list
* Change remove icon color to red
* Remove colon from wish list on mobile

= 1.2.3 - 25/03/2017 =
* Fix wish list url that goes on sharing email
* Prevent possible duplicates of Frontend Ajax URL on admin
* Make optional the feature to send email to admin

= 1.2.2 - 24/03/2017 =
* Improves function that calculates thumb button position on loop
* Add new option to share the wish list via email
* Improves performance by keeping the wishlist inside a variable
* Update izitoast
* Update translation

= 1.2.1 - 20/03/2017 =
* Fix bug where a warning was being generated by a missing cookie

= 1.2.0 - 14/03/2017 =
* Fix German translation
* Improve thumb button position
* Fix notice on localize_scripts() function
* Increase priority of cookies creation
* Fix translation slug on template files

= 1.1.9 - 13/03/2017 =
* Fix the problem where ajax can't recognize logged in users on domain-mapped sites. Now the frontend ajaxurl is passed through an option on admin
* German translation

= 1.1.8 - 12/03/2017 =
* Prevent an issue with themes that overwrite font-awesome font-family

= 1.1.7 - 10/03/2017 =
* Fix bug of empty icons on https connections. Now FontAwesome is being loaded through Protocol-relative URL

= 1.1.6 - 08/03/2017 =
* Fix multiple domain issue (Now user id cookie is being passed via ajax)
* Improve performance (Admin fields are being loaded only on admin)

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

= 1.0.0 - 23/01/2017 =
* Initial Release.

== Upgrade Notice ==

= 1.6.7 =
* Fix Font Awesome URL