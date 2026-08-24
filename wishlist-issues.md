# 🔴 Trialware and Locked Features
Please review your plugin to ensure that it does not include any locked or restricted built-in functionality. This is not permitted under the WordPress.org Plugin Directory Guidelines you agreed to when submitting the plugin.

❌ Guideline 5 – Trialware
Plugins must be fully functional. You may not:
Lock, disable or limit built-in features behind a license key, trial period, usage limit, time, quota or any other kind of intended restriction.

Even if the locked feature is present in the code "just in case the user upgrades," it’s still not allowed. Your plugin may point out which features are available through a separated plugin, but that's it. All plugin code hosted on WordPress.org must be free and fully functional.

🌐 Guideline 6 – Serviceware
Plugins may connect to a legitimate external service to perform certain functionality, provided:
The service performs actual processing on external servers.
The functionality provided cannot be done locally by the plugin.
The service is clearly documented in your readme, including Terms of Use and Privacy Policy links.

For example: a "Spam checker" plugin that connects to a external service to check for spam (and thus uses it to provide that functionality) is generally acceptable. A plugin that simply checks a license key to unlock local features is not.

✅ Ask yourself:
Does any function only work after a license check or payment?
Is any functionality in the plugin code disabled or limited until it’s unlocked?
Are there any limitations on the plugin after a certain amount of time or usage?

After excluding functionalities provided by legitimate external services, if the answer is yes to any of the above, the plugin does not comply.

🔧 How to fix it:
Remove all license checks or other mechanisms that control access to features built in in the plugin code.
Remove or fully enable any built in features that are currently locked or limited.
Make sure external services are compliant and clearly documented.

ℹ️ Important clarification:
WordPress.org is not a marketplace. It's a repository for free, fully functional, GPL-compliant plugins.

If you are not offering a service and want to offer additional features through a paid version, that code must be:
Hosted elsewhere (e.g., your own website).
Not included in the plugin hosted on WordPress.org.
GPL compliant: Do not include any mechanisms that would prevent a plug-in from being used after a license has been checked.

✨ Wishlist item quantity selection is implemented locally but explicitly disabled for the free version.

⚠️ The AI has highlighted the most apparent issues. There may be additional concerns not explicitly mentioned. You must read and comprehend the guidelines and review the entire code thoroughly to ensure that there are no other issues.
❗ If more issues of the same nature are found in the following review, this plugin will not be reviewed again. Ensure full compliance with the guidelines to avoid rejection.

# 🔴️ Phoning Home / Collecting User Data Without Opt-In Consent

Under the guidelines that you accepted when you submitted the plugin, plugins are not permitted to track users or otherwise collect data without clear opt-in consent (Guidelines 7 & 9). This is considered to be phoning home. The only time it is even considered is if your plugin's remote calls are in relation to a service.

It's not ok to track users of their code without the tracking being 100% optional, and turned OFF by default. The WordPress.org plugins directory feel strongly about the privacy of plugin users, and by that standard, they should not have their actions recorded. It's also illegal in many countries, so this is for your own safety.

At this time, we do not permit the use of Google Analytics services to track wp-admin usage in any form, even if it's opt-in.

Please remove any calls back to your own server, or a 3rd party, from the plugin. Alternately, if you feel we have made an incorrect assessment, you may provide more information as to exactly how you are providing a service.

From your plugin:
✨ Loads Font Awesome from use.fontawesome.com by default and Bootstrap from netdna.bootstrapcdn.com without explicit opt-in, exposing visitor and admin request data to third parties.


# 🔴️ Attempting to process custom CSS/JS/PHP / Allowing arbitrary script insertion.

We no longer permit plugins to allow users to save arbitrary custom CSS, JavaScript, or PHP within the plugin.

The primary reason for this is that WordPress includes it's own, robust, error-checking, CSS editor in the Customizer or Editor already. Any time your plugin replicates functionality found in WordPress (i.e. the uploader, jquery) is frowned upon, as it presents a possible security risk. The features in WordPress have been tested by many more people than use most plugins, so the built in tools are less likely to have issues.

As for JavaScript, we recognize that script insertion plugins are amazing and powerful. They're also incredibly dangerous and require a high level understanding of sanitization, security, and usage. And in the case of most plugins, these are entirely unnecessary.

You should never be asking users to paste in arbitrary JavaScript. Instead, have them paste in the values custom to their scripts and generate the rest programmatically.

Also, if you are asking for code to make customization, make that a form instead. Besides security, you can't expect your users to know how to code.

PHP is even more complex. This is why WordPress itself allows you to lock people out of being able to edit theme and plugin files directly (via DEFINES that are used by many managed hosts), but also has a serious of post-processing checks that verify the site will still function after any changes.

Please, remove arbitrary code insertion from your plugin.
✨ A user-configurable Font Awesome URL is read from an option and enqueued as a stylesheet, allowing arbitrary remote CSS to be loaded on the site.


# 🔴️ Callback calls to a function/method whose return values are output must be properly escaped

Callback calls to a function/method in which return content is later rendered on screen must ensure that all returned data is properly escaped. Since WordPress outputs these return values, escaping must be applied to the data being returned.

This applies to callbacks registered with functions such as add_shortcode() or filters like the_content or the_title .

As with directly echoed variables, returning unescaped data can lead to unintended output and potential security issues, including XSS vulnerabilities. Please review the affected function or method and ensure that all variables, options, and generated values included in the return are escaped using the appropriate WordPress escaping functions.

For more details on selecting the correct escaping functions for each context, please refer to: https://developer.wordpress.org/apis/security/escaping/

Example(s) from your plugin:
includes/free/class-alg-wc-wish-list-compatibility.php:35 add_shortcode('ti_wishlists_addtowishlist', array($this, 'replace_ti_wishlist_by_ours'));
* ✨ Returns unescaped output from do_shortcode().
includes/free/class-alg-wc-wish-list-tab.php:35 add_filter('the_title', array($this, 'endpoint_title'));
* ✨ Returns the title after sanitization but without output escaping.



# 🔴️ Determine files and directories locations correctly

WordPress provides several functions for easily determining where a given file or directory lives.

We detected that the way your plugin references some files, directories and/or URLs may not work with all WordPress setups. This happens because there are hardcoded references or you are using the WordPress internal constants.

Let's improve it, please check out the following documentation:

https://developer.wordpress.org/plugins/plugin-basics/determining-plugin-and-content-directories/

It contains all the functions available to determine locations correctly.

Most common cases in plugins can be solved using the following functions:
For where your plugin is located: plugin_dir_path() , plugin_dir_url() , plugins_url()
For the uploads directory: wp_upload_dir() (Note: If you need to write files, please do so in a folder in the uploads directory, not in your plugin directories).

Example(s) from your plugin:
includes/admin/class-alg-wc-wish-list-settings-advanced.php:201 home_url( 'wp-admin/admin-ajax.php' ),
* ✨ The hardcoded wp-admin path can fail on customized admin directory or subdirectory installations; use admin_url( 'admin-ajax.php' ) instead.



ℹ️ To reference the URL of the wp-admin directory, it's best to use the function admin_url() .

Example(s) from your plugin:
includes/admin/class-alg-wc-wish-list-settings-advanced.php:201 home_url( 'wp-admin/admin-ajax.php' ),



# 🔴️ Internationalization: Text domain does not match plugin slug.

In order to make a string translatable in your plugin you are using a set of special functions. These functions collectively are known as "gettext".

These functions have a parameter called "text domain", which is a unique identifier for retrieving translated strings.

This "text domain" must be the same as your plugin slug so that the plugin can be translated by the community using the tools provided by the directory. As for example, if this plugin slug is "wish-list-for-woocommerce" the Internationalization functions should look like:
esc_html__( 'Hello', 'wish-list-for-woocommerce' );

From your plugin, you have set your text domain as follows:
* This plugin is using the domain "url-coupons-for-woocommerce-by-algoritmika" for 2 element(s).
* This plugin is using the domain "alg-wc-compare-products" for 6 element(s).
* This plugin is using the domain "multi-order-for-woocommerce" for 1 element(s).

However, the current plugin slug is this:
wish-list-for-woocommerce



# 🔴️ Other possible issues

The AI detected certain cases not classified to specific sections of this report that can be related to security, compatibility, guidelines or other potential issues.

We know that the AI can be picky at times, so please review these cases carefully.

If there are issues, please resolve them. That way, we won't need to expend AI tokens checking the same thing again :)

From your plugin:
includes/free/class-alg-wc-wish-list-customization-default-button.php:39 get_option(Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_MARGIN_LOOP, '10px 0 0 0');
* ✨ sanitize_text_field does not validate CSS margin syntax, and the value is interpolated into frontend CSS, permitting CSS injection through a crafted stored setting.
includes/free/class-alg-wc-wish-list-customization-wish-list.php:41 get_option(Alg_WC_Wish_List_Settings_Style::OPTION_MULTIPLE_TAB_DELETE_BUTTON_HOVER_COLOR, '*DC3232');
* ✨ The color option is inserted into generated CSS without contextual CSS validation, permitting CSS injection through a crafted stored setting.



# 🔴️ Nonces and User Permissions Needed for Security

Please add a nonce check to your input calls ($_POST, $_GET, $REQUEST) to prevent unauthorized access.

If you use wp_ajax_ to trigger submission checks, remember they also need a nonce check.

👮 Checking permissions: Keep in mind, a nonce check alone is not bulletproof security. Do not rely on nonces for authorization purposes. When needed, use it together with current_user_can() in order to prevent users without the right permissions from accessing things they shouldn't.

Also make sure that the nonce logic is correct by making sure it cannot be bypassed. Checking the nonce with current_user_can() is great, but mixing it with other checks can make the condition more complex and, without realising it, bypassable, remember that anything can be sent through an input, don't trust any input.

Keep performance in mind. Don't check for post submission outside of functions. Doing so means that the check will run on every single load of the plugin, which means that every single person who views any page on a site using your plugin will be checking for a submission. This will make your code slow and unwieldy for users on any high traffic site, leading to instability and eventually crashes.

The following links may assist you in development:

https://developer.wordpress.org/plugins/security/nonces/
https://developer.wordpress.org/plugins/javascript/ajax/*nonce
https://developer.wordpress.org/plugins/settings/settings-api/

From your plugin:
includes/free/class-alg-wc-wish-list-stock-manager.php:81 Alg_WC_Wish_List_Stock_Manager::save_stock_alert_infs() [classMethod] No nonce check found validating input origin on lines 81-105
* ↳ Line 102: $args['enable'] = isset( $_REQUEST['alg_wcwl_user_stock_alert'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['alg_wcwl_user_stock_alert'] ) ) : false;
* ✨ Stores stock-alert settings from request data without verifying a nonce, allowing CSRF changes to alert preferences.

Please, make sure that the nonce logic is correct. It's important to be cautious when structuring conditional checks around nonces.
includes/free/class-alg-wc-wish-list-ajax.php:867 if ( ! isset( $args['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( $args['_wpnonce'] ), 'clear_wishlist' ) || 0 === $permission ) {


# 🔴 Proper sanitization of inputs

ℹ️ Why it matters: Sanitizing inputs is crucial for protecting against security vulnerabilities like SQL injection, handling invalid data, and ensuring that only safe, expected data is processed.
Please check the official WordPress docs on sanitizing for details — this is a quick summary.

🔍 Identify unsanitized inputs: Check any input coming from sources such as: $_GET , $_POST , $_REQUEST , $_COOKIE , $_SERVER , $_SESSION
If these inputs aren’t sanitized first, that’s a potential risk! 🕵️

🛠 Fix it: Always wrap any input with the right sanitization function, like:
Content
Function
Plain text
sanitize_text_field()
Email
sanitize_email()
URL
esc_url_raw()
Key
sanitize_key()
Integer
absint()

Refer to the official WordPress documentation for a complete list of sanitization functions.
👉 Use the most restrictive function that fits the expected content.
👉 Sanitize as early as possible — ideally as soon as the data is received.
👉 Only trust what you’ve cleaned yourself.

Example:
$post_id = (int) $_GET['post_id']; // Sanitized as an integer
$email = sanitize_email( $_GET['email'] ); // Sanitized as an email
Your data is now sanitized! 🧼🍏

Examples from your code:
includes/free/class-alg-wc-wish-list-ajax.php:535 $posted = wp_unslash( $_POST );
* ↳ Line 536: $args   = wp_parse_args( $posted, array(
'ignore_excluded_items' => false,
'value'                 => array(),
'item_id'               => 0,
) );
* ✨ The unsanitized security nonce is passed by check_ajax_referer() to pluggable nonce verification.
includes/free/class-alg-wc-wish-list-ajax.php:457 $posted = wp_unslash( $_POST );
* ↳ Line 458: $args = wp_parse_args( $posted, array(
'ignore_excluded_items' => false,
'wishlist_tab_id'       => 0,
'wishlist_page_id'      => 0,
) );
* ✨ The unsanitized security nonce is passed by check_ajax_referer() to pluggable nonce verification.
