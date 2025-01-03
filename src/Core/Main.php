<?php
namespace WPCategoryMedia\Core;

use WPCategoryMedia\Admin\CategoryImage; 
use WPCategoryMedia\Frontend\CategoryDisplay;
use WPCategoryMedia\Traits\SingletonTrait;

class Main {

    use SingletonTrait;

    protected $loader;

    protected function __construct() {
        $this->initialize();
    }

    private function initialize() {
        $this->load_dependencies();
        $this->register_hooks();
        CategoryImage::get_instance();
        CategoryDisplay::get_instance();

    }

    private function load_dependencies() {
        $this->loader = new Loader();
    }

    private function register_hooks() {
        $this->loader->run();
    }

   
}
