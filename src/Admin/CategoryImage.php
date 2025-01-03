<?php

namespace WPCategoryMedia\Admin;

use WPCategoryMedia\Core\Loader;
use WPCategoryMedia\Traits\SingletonTrait;

class CategoryImage {

    use SingletonTrait;  

    /**
     * CategoryImage constructor.
     * Initialize the hooks and actions.
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the hooks and actions.
     */
    private function init() {
        add_action( 'category_add_form_fields', array( $this, 'wpcm_add_category_image' ), 10, 2 );
        add_action( 'created_category', array( $this, 'wpcm_save_category_image' ), 10, 2 );
        add_action( 'category_edit_form_fields', array( $this, 'wpcm_update_category_image' ), 10, 2 );
        add_action( 'edited_category', array( $this, 'wpcm_updated_category_image' ), 10, 2 );
        add_action( 'admin_enqueue_scripts', array( $this, 'wpcm_load_media' ) );
        add_action( 'admin_footer', array( $this, 'wpcm_add_script' ) );
    }

    /**
     * Add the category image field in the new category page.
     */
    public function wpcm_add_category_image( $taxonomy ) {
        
        $nonce = wp_create_nonce( 'wpcm_category_image_nonce' );
        ?>
        <div class="form-field term-group">
            <label for="category-image-id"><?php _e( 'Image', 'wp-category-media' ); ?></label>
            <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
            <div id="category-image-wrapper"></div>
            <input type="hidden" name="wpcm_category_image_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
            <p>
                <input type="button" class="button button-secondary wpcm_tax_media_button" id="wpcm_tax_media_button" name="wpcm_tax_media_button" value="<?php _e( 'Add Image', 'wp-category-media' ); ?>" />
                <input type="button" class="button button-secondary wpcm_tax_media_remove" id="wpcm_tax_media_remove" name="wpcm_tax_media_remove" value="<?php _e( 'Remove Image', 'wp-category-media' ); ?>" />
            </p>
        </div>
        <?php
    }

    /**
     * Save the category image.
     */
    public function wpcm_save_category_image( $term_id, $tt_id ) {
        
        if ( ! isset( $_POST['wpcm_category_image_nonce'] ) ) {
            error_log( 'Nonce is missing for category image save' );
            return;
        }

        if ( ! wp_verify_nonce( $_POST['wpcm_category_image_nonce'], 'wpcm_category_image_nonce' ) ) {
            error_log( 'Nonce verification failed for category image save' );
            return;
        }

       
        if ( ! current_user_can( 'manage_categories' ) ) {
            return;
        }

       
        if ( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ) {
            $image = absint( $_POST['category-image-id'] );
            add_term_meta( $term_id, 'category-image-id', $image, true );
        }
    }

    /**
     * Update the category image.
     */
    public function wpcm_update_category_image( $term, $taxonomy ) {
        $nonce = wp_create_nonce( 'wpcm_category_image_nonce' ); 
        ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="category-image-id"><?php _e( 'Image', 'wp-category-media' ); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta( $term->term_id, 'category-image-id', true ); ?>
                <input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo esc_attr( $image_id ); ?>">
                <div id="category-image-wrapper">
                    <?php if ( $image_id ) { ?>
                        <?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
                    <?php } ?>
                </div>
                <input type="hidden" name="wpcm_category_image_nonce" value="<?php echo esc_attr( $nonce ); ?>" /> 
                <p>
                <?php
                
                $button_text = $image_id ? __( 'Change Image', 'wp-category-media' ) : __( 'Add Image', 'wp-category-media' );
                ?>
                <input type="button" class="button button-secondary wpcm_tax_media_button" id="wpcm_tax_media_button" name="wpcm_tax_media_button" value="<?php echo esc_attr( $button_text ); ?>" />                   
                <input type="button" class="button button-secondary wpcm_tax_media_remove" id="wpcm_tax_media_remove" name="wpcm_tax_media_remove" value="<?php _e( 'Remove Image', 'wp-category-media' ); ?>" />
                </p>
            </td>
        </tr>
        <?php
    }

    /**
     * Update the category image field value.
     */
    public function wpcm_updated_category_image( $term_id, $tt_id ) {
        
       
        if ( ! isset( $_POST['wpcm_category_image_nonce'] ) ) {
            error_log( 'Nonce is missing for category image update' );
            return;
        }

        
        if ( ! wp_verify_nonce( $_POST['wpcm_category_image_nonce'], 'wpcm_category_image_nonce' ) ) {
            error_log( 'Nonce verification failed for category image update' );
            return;
        }

        
        if ( ! current_user_can( 'manage_categories' ) ) {
            return;
        }

       
        if ( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ) {
            $image = absint( $_POST['category-image-id'] );
            update_term_meta( $term_id, 'category-image-id', $image );
        } else {
            update_term_meta( $term_id, 'category-image-id', '' );
        }
    }

    /**
     * Load media files for category images.
     */
    public function wpcm_load_media() {
        wp_enqueue_media();
    }

    /**
     * Add necessary JavaScript for category image functionality.
     */
    public function wpcm_add_script() {
        ?>
        <script>
            jQuery(document).ready(function($) {
                function wpcm_media_upload(button_class) {
                    var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $('body').on('click', button_class, function(e) {
                        var button_id = '#' + $(this).attr('id');
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(button_id);
                        _custom_media = true;
                        wp.media.editor.send.attachment = function(props, attachment) {
                            if (_custom_media) {
                                $('#category-image-id').val(attachment.id);
                                $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                                $('#category-image-wrapper .custom_media_image').attr('src', attachment.url).css('display', 'block');
                            } else {
                                return _orig_send_attachment.apply(button_id, [props, attachment]);
                            }
                        };
                        wp.media.editor.open(button);
                        return false;
                    });
                }

                wpcm_media_upload('.wpcm_tax_media_button.button');
                $('body').on('click', '.wpcm_tax_media_remove', function() {
                    $('#category-image-id').val('');
                    $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                });

                $(document).ajaxComplete(function(event, xhr, settings) {
                    var queryStringArr = settings.data.split('&');
                    if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                        var xml = xhr.responseXML;
                        $response = $(xml).find('term_id').text();
                        if ($response != "") {
                            $('#category-image-wrapper').html('');
                        }
                    }
                });
            });
        </script>
        <?php
    }
}
