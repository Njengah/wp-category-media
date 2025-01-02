<?php
namespace WPCategoryMedia\Core;

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
    }

    private function load_dependencies() {
        $this->loader = new Loader();

        
        $this->loader->add_action( 'admin_init', $this, 'admin_init' );
        $this->loader->add_action( 'wp_footer', $this, 'display_footer_message' );
    }

    private function register_hooks() {
        $this->loader->run();
    }

    public function admin_init() {
       
    }

    public function display_footer_message() {
        echo '<p>This is a footer message added by the WP Category Media plugin.</p>';
    }
}
