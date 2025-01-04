<?php
/**
 * Uninstall hook for WP Category Media.
 *
 * This file is triggered when the user uninstalls the WP Category Media plugin from the WordPress admin.
 * It will remove any data created by the plugin, such as options, custom taxonomies, and any other plugin-specific data.
 * This ensures a clean removal of the plugin from the WordPress site.
 *
 * @package WPCategoryMedia
 * @since 1.0.0
 */

 // Prevent direct access to this file
 
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit; 
}
