<?php
namespace WPCategoryMedia\Core;

use WPCategoryMedia\Admin\CategoryImage; 
use WPCategoryMedia\Frontend\CategoryDisplay;
use WPCategoryMedia\Traits\SingletonTrait;
use WPCategoryMedia\Admin\Admin;

class Main {

    use SingletonTrait;

    protected $loader;

    protected function __construct() {
        $this->initialize();
    }

    private function initialize() {
        $this->load_dependencies();
        $this->register_hooks();
        i18n::get_instance();
        CategoryImage::get_instance();
        CategoryDisplay::get_instance();
    }


    private function load_dependencies() {
        $this->loader = new Loader();
        $admin = Admin::get_instance();
        add_action('admin_enqueue_scripts', array($admin, 'enqueue_admin_assets'));
        add_action('wp_enqueue_scripts', array($admin, 'enqueue_frontend_assets'));
    }

    private function register_hooks() {
        $this->loader->run();
    }

   
}
