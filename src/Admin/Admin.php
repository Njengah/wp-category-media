<?php

namespace WPCategoryMedia\Admin;

use WPCategoryMedia\Traits\SingletonTrait;

class Admin {

    use SingletonTrait;

    public function __construct() {}

    
    /**
     * Enqueue admin-specific styles and scripts.
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


    public function enqueue_frontend_assets() {

        wp_enqueue_style(
            'wpcm-public-css',
            plugin_dir_url(dirname(__DIR__)) . 'assets/css/public.css',
            [],
            WP_CATEGORY_MEDIA_VERSION
        );


    }



}
