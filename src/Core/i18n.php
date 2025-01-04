<?php

namespace WPCategoryMedia\Core;

use WPCategoryMedia\Traits\SingletonTrait;

class i18n {
    use SingletonTrait;

    public function __construct() { }

    /**
     * Load the plugin text domain for translation.
     *
     * This method loads the text domain for the plugin, enabling translation of the plugin's
     * strings to different languages. It ensures that the plugin can be properly translated
     * by the translation tools like Poedit or Loco Translate.
     *
     * It uses the `load_plugin_textdomain()` function to load the `.mo` files from the
     * `/languages/` directory located within the plugin directory.
     *
     * @since 1.0.0
     * @return void
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'wp-category-media', 
            false, 
            dirname(plugin_basename(WP_CATEGORY_MEDIA_FILE)) . '/languages' 
        );
    }
}
