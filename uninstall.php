<?php
/**
 * Uninstall hook for WP Category Media.
 *
 * This file is triggered when the user uninstalls the plugin from the WordPress admin.
 * It will remove any data created by the plugin, such as options and custom taxonomies.
 *
 * @package WPCategoryMedia
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit; 
}



// $terms = get_terms( array(
//     'taxonomy'   => 'category',
//     'hide_empty' => false,
// ) );

// foreach ( $terms as $term ) {
//     delete_term_meta( $term->term_id, 'wpcm-category-image-id' ); 
// }


// delete_option( 'wpcm_category_image_default' ); 
