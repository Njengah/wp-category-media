<?php
namespace WPCategoryMedia\Core;

use WPCategoryMedia\Traits\SingletonTrait;

class Main {

    // Use the SingletonTrait to enforce the Singleton pattern
    use SingletonTrait;

    /**
     * Main constructor.
     * This method is protected to ensure no instance can be created directly.
     */
    protected function __construct() {
        // Initialize the core functionalities of the plugin
        $this->init();
    }

    /**
     * Initialize the core functionalities of the plugin.
     */
    private function init() {
        // Add actions, filters, or load other necessary components for the plugin
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'wp_footer', array( $this, 'display_footer_message' ) );
    }

    /**
     * Admin initialization method to handle admin-related logic.
     */
    public function admin_init() {
        // Add your admin-related functionality here
        // For example, load admin settings, enqueue scripts, etc.
    }

    /**
     * Display a simple footer message as an example of frontend functionality.
     */
    public function display_footer_message() {
        echo '<p>This is a footer message added by the WP Category Media plugin.</p>';
    }
}
