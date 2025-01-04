<?php
namespace WPCategoryMedia\Core;

use WPCategoryMedia\Admin\CategoryImage; 
use WPCategoryMedia\Frontend\CategoryDisplay;
use WPCategoryMedia\Traits\SingletonTrait;
use WPCategoryMedia\Admin\Admin;

class Main {

    use SingletonTrait;

    protected $loader;

    /**
     * Protected constructor to initialize the class.
     *
     * This constructor method initializes the class and calls the `initialize()` method 
     * to load dependencies and register the necessary hooks for the plugin functionality.
     * It also initializes the instances of the `i18n`, `CategoryImage`, and `CategoryDisplay` classes 
     * for plugin localization and category-related functionalities.
     *
     * @since 1.0.0
     * @return void
     */

    protected function __construct() {
        $this->initialize();
    }

    /**
     * Initializes the plugin by loading dependencies and registering hooks.
     *
     * This method is called from the constructor. It loads the required dependencies 
     * and registers actions and filters needed to function properly. It also initializes 
     * the plugin components such as i18n, CategoryImage, and CategoryDisplay.
     *
     * @since 1.0.0
     * @return void
     */

    private function initialize() {
        $this->load_dependencies();
        $this->register_hooks();
        i18n::get_instance();
        CategoryImage::get_instance();
        CategoryDisplay::get_instance();
    }

    /**
     * Loads the plugin dependencies.
     *
     * This method includes the necessary files for the plugin to function properly, 
     * including loading the Loader class, and enqueuing admin and frontend assets.
     * It also hooks into the WordPress admin and frontend to load the corresponding assets.
     *
     * @since 1.0.0
     * @return void
     */
    private function load_dependencies() {
        $this->loader = new Loader();
        $admin = Admin::get_instance();
        add_action('admin_enqueue_scripts', array($admin, 'enqueue_admin_assets'));
        add_action('wp_enqueue_scripts', array($admin, 'enqueue_frontend_assets'));
    }

    /**
     * Registers the necessary hooks for the plugin.
     *
     * This method registers all the hooks needed to run the plugin, including the 
     * actions and filters that drive the plugin’s functionality. It uses the Loader class 
     * to manage the hooks for the plugin.
     *
     * @since 1.0.0
     * @return void
     */
    private function register_hooks() {
        $this->loader->run();
    }

   
}
