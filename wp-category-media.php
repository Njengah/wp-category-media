<?php
/**
 * @wordpress-plugin
 * Plugin Name:       WP Category Media
 * Plugin URI:        https://njengah.com
 * Description:       A WordPress plugin that allows you to easily add and manage images for categories, enhancing the visual appeal of your site.
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
 * Define plugin constants.
 */
define( 'WP_CATEGORY_MEDIA_VERSION', '1.0.0' );
define( 'WP_CATEGORY_MEDIA_FILE', __FILE__ );
define( 'WP_CATEGORY_MEDIA_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_CATEGORY_MEDIA_URL', plugins_url( '', WP_CATEGORY_MEDIA_FILE ) );
define( 'WP_CATEGORY_MEDIA_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_CATEGORY_MEDIA_BASE_DIR', __DIR__ );
define( 'WP_CATEGORY_MEDIA_ADMIN', WP_CATEGORY_MEDIA_PATH . 'src/Admin/' );

/**
 * Load Composer autoload (if available).
 */
if ( file_exists( WP_CATEGORY_MEDIA_PATH . 'vendor/autoload.php' ) ) {
    require_once WP_CATEGORY_MEDIA_PATH . 'vendor/autoload.php';
}

/**
 * Main WP Category Media function to initialize the plugin.
 */
function wp_category_media_init() {
    \WP_Category_Media\Core\Main::get_instance();
}

add_action( 'plugins_loaded', 'wp_category_media_init' );
