<?php
/**
 * @wordpress-plugin
 * Plugin Name:       WP Category Media
 * Plugin URI:        https://njengah.com
 * Description:       WP Category Media is an easier way to add and manage category images, enhancing the visual appeal of your archive and catagory pages. You can also use the shortcode to display multiple catagories layouts with category images.
 * Version:           1.0.0
 * Author:            Joe Njenga
 * Author URI:        https://njengah.com
 * Text Domain:       wp-category-media
 * Domain Path:       /languages
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Author Email:      dev@njengah.com
 * Tested up to:      6.7.1
 * Requires at least: 4.7
 * Requires PHP:      7.1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Define Constants.
 */
define( 'WP_CATEGORY_MEDIA_VERSION', '1.0.0' );
define( 'WP_CATEGORY_MEDIA_FILE', __FILE__ );
define( 'WP_CATEGORY_MEDIA_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_CATEGORY_MEDIA_URL', plugins_url( '', WP_CATEGORY_MEDIA_FILE ) );
define( 'WP_CATEGORY_MEDIA_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_CATEGORY_MEDIA_BASE_DIR', __DIR__ );
define( 'WP_CATEGORY_MEDIA_ADMIN', WP_CATEGORY_MEDIA_PATH . 'src/Admin/' );

/**
 * Autoload CLASSES.
 */
if ( file_exists( WP_CATEGORY_MEDIA_PATH . 'vendor/autoload.php' ) ) {
    require_once WP_CATEGORY_MEDIA_PATH . 'vendor/autoload.php';
}

/**
 * Main WP Category Media function to initialize the plugin.
 */
function wp_category_media_init() {
    \WPCategoryMedia\Core\Main::get_instance();
}
add_action( 'plugins_loaded', 'wp_category_media_init' );

