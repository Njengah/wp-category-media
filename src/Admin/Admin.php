<?php

namespace WPCategoryMedia\Admin;

use WPCategoryMedia\Traits\SingletonTrait;

class Admin {

    use SingletonTrait;

    public function __construct() {}

    
    /**
     * Enqueue admin-specific styles and scripts.
     *
     * This method is responsible for enqueuing the CSS and JavaScript assets 
     * that are specific to the WordPress admin panel for the WP Category Media plugin.
     * It ensures that the required styles and scripts are loaded only on admin pages 
     * where they are needed, optimizing performance on other pages.
     *
     * @package WPCategoryMedia
     * @since 1.0.0
     */
    public function enqueue_admin_assets() {
        wp_enqueue_style(
            'wpcm-admin-css',
            plugin_dir_url(dirname(__DIR__)) . 'assets/css/admin.css',
            [],
            WP_CATEGORY_MEDIA_VERSION
        );

        wp_enqueue_script(
            'wpcm-admin-js',
            plugin_dir_url(dirname(__DIR__)) . 'assets/js/admin.js', 
            array('jquery'),
            WP_CATEGORY_MEDIA_VERSION, 
            true 
        );
    }

    /**
     * Enqueue frontend-specific styles.
     *
     * This method is responsible for enqueuing the CSS assets that are specific to 
     * the frontend of the WordPress site for the WP Category Media plugin. 
     * It ensures that the necessary styles are loaded only on the pages where 
     * the plugin's frontend functionality is used, improving the site's performance.
     *
     * @package WPCategoryMedia
     * @since 1.0.0
     */
    public function enqueue_frontend_assets() {

        wp_enqueue_style(
            'wpcm-public-css',
            plugin_dir_url(dirname(__DIR__)) . 'assets/css/public.css',
            [],
            WP_CATEGORY_MEDIA_VERSION
        );


    }



}
