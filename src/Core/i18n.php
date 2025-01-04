<?php

namespace WPCategoryMedia\Core;

use WPCategoryMedia\Traits\SingletonTrait;

class i18n {
    use SingletonTrait;

    public function __construct() { }

    /**
     * Load the plugin text domain for translation.
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'wp-category-media', 
            false, 
            dirname(plugin_basename(WP_CATEGORY_MEDIA_FILE)) . '/languages' 
        );
    }
}
