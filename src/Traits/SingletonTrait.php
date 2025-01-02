<?php

namespace WPCategoryMedia\Traits;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * SingletonTrait to enforce the Singleton pattern in classes.
 */
trait SingletonTrait {

    /**
     * The single instance of the class.
     *
     * @var self|null
     */
    protected static $instance = null;

    /**
     * Get the single instance of the class.
     *
     * @return self
     */
    final public static function get_instance() {
        if ( is_null( static::$instance ) ) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Prevent cloning of the instance.
     */
    final private function __clone() {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__( 'Cloning this instance is not allowed.', 'wp-category-media' ),
            '1.0'
        );
    }

    /**
     * Prevent unserializing of the instance.
     */
    final private function __wakeup() {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__( 'Unserializing this instance is not allowed.', 'wp-category-media' ),
            '1.0'
        );
    }

    /**
     * Prevent serializing of the instance.
     */
    public function __sleep() {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__( 'Serialization of this instance is not allowed.', 'wp-category-media' ),
            '1.0'
        );
    }

    /**
     * Protected constructor to prevent creating a new instance outside of the class.
     */
    protected function __construct() {}
}
