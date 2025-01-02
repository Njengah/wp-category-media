<?php

namespace WPCategoryMedia\Core;

class Loader {
    /**
     * Array of actions registered with WordPress.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Array of filters registered with WordPress.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @param string $hook The name of the WordPress action.
     * @param object $component A reference to the instance of the class.
     * @param string $callback The name of the method in the $component.
     * @param int    $priority The priority at which the function should be fired.
     * @param int    $accepted_args The number of arguments accepted by the callback.
     */
    public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->actions[] = compact( 'hook', 'component', 'callback', 'priority', 'accepted_args' );
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @param string $hook The name of the WordPress filter.
     * @param object $component A reference to the instance of the class.
     * @param string $callback The name of the method in the $component.
     * @param int    $priority The priority at which the function should be fired.
     * @param int    $accepted_args The number of arguments accepted by the callback.
     */
    public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->filters[] = compact( 'hook', 'component', 'callback', 'priority', 'accepted_args' );
    }

    /**
     * Register the actions and filters with WordPress.
     */
    public function run() {
        // Register actions
        foreach ( $this->actions as $action ) {
            add_action(
                $action['hook'],
                [ $action['component'], $action['callback'] ],
                $action['priority'],
                $action['accepted_args']
            );
        }

        // Register filters
        foreach ( $this->filters as $filter ) {
            add_filter(
                $filter['hook'],
                [ $filter['component'], $filter['callback'] ],
                $filter['priority'],
                $filter['accepted_args']
            );
        }
    }
}
