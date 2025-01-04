<?php

namespace WPCategoryMedia\Admin;

use WPCategoryMedia\Core\Loader;
use WPCategoryMedia\Traits\SingletonTrait;

class CategoryImage {

    use SingletonTrait;  

    /**
     * CategoryImage constructor.
     *
     * Initializes the hooks and actions for managing category images.
     * This constructor method is called when the CategoryImage class is instantiated. It invokes the 
     * `init()` method, which sets up the necessary WordPress actions and filters to enable functionality 
     * for adding, saving, updating, and displaying category images.
     *
     * @return void
     */

    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the hooks and actions for the WP Category Media plugin.
     *
     * This method registers the necessary WordPress hooks and filters to add functionality to the plugin.
     * It includes actions for adding, saving, updating, and displaying category images, as well as loading
     * admin media files and scripts. Additionally, it adds custom columns to the category admin table and makes
     * them sortable.
     *
     * - `category_add_form_fields`: Displays the form fields for adding category images.
     * - `created_category`: Saves the category image when a new category is created.
     * - `category_edit_form_fields`: Displays the form fields for updating the category image.
     * - `edited_category`: Updates the category image when a category is edited.
     * - `admin_enqueue_scripts`: Enqueues necessary media scripts for the admin panel.
     * - `admin_footer`: Adds custom JavaScript for handling media uploads in the admin.
     * - `get_the_archive_title`: Inserts the category image at the top of the category archive.
     * - `manage_edit-category_columns`: Adds the category image column to the category list in the admin.
     * - `manage_category_custom_column`: Displays the category image in the admin column.
     * - `manage_edit-category_sortable_columns`: Makes the category image column sortable in the admin.
     *
     * @return void
    */
    
    private function init() {
        add_action( 'category_add_form_fields', array( $this, 'wpcm_add_category_image' ), 10, 2 );
        add_action( 'created_category', array( $this, 'wpcm_save_category_image' ), 10, 2 );
        add_action( 'category_edit_form_fields', array( $this, 'wpcm_updated_category_image' ), 10, 2 );
        add_action( 'edited_category', array( $this, 'wpcm_update_category_image' ), 10, 2 );
        add_action( 'admin_enqueue_scripts', array( $this, 'wpcm_load_media' ) );
        add_action( 'admin_footer', array( $this, 'wpcm_add_script' ) );
        add_filter( 'get_the_archive_title', array( $this, 'wpcm_insert_category_image_top' ) );
        add_filter( 'manage_edit-category_columns', array( $this, 'wpcm_add_category_image_column' ), 5 );
        add_action( 'manage_category_custom_column', array( $this, 'wpcm_display_category_image_column' ), 10, 3 );
        add_filter( 'manage_edit-category_sortable_columns', array( $this, 'wpcm_sortable_category_image_column' ) );
    }

    /**
     * Add the category image field in the new category page.
     *
     * This method generates the HTML form fields for adding a category image, specifying the image width
     * and border radius. It includes a nonce field for security and allows the user to select or remove
     * an image for the category. The fields include options for image width and border radius.
     * It is triggered when adding a new category in the WordPress admin.
     *
     * @param string $taxonomy The taxonomy the category belongs to.
     * 
     * @return void
     */
 
    public function wpcm_add_category_image( $taxonomy ) {
        $nonce = wp_create_nonce( 'wpcm_category_image_nonce' );
        ?>
        <div class="form-field term-group">
            <label for="wpcm-category-image-id"><?php _e( 'Image', 'wp-category-media' ); ?></label>
            <input type="hidden" id="wpcm-category-image-id" name="wpcm-category-image-id" class="custom_media_url" value="">
            <div id="category-image-wrapper"></div>
            <input type="hidden" name="wpcm_category_image_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
            <p>
                <input type="button" class="button button-secondary wpcm_tax_media_button" id="wpcm_tax_media_button" name="wpcm_tax_media_button" value="<?php _e( 'Add Image', 'wp-category-media' ); ?>" />
                <input type="button" class="button button-secondary wpcm_tax_media_remove" id="wpcm_tax_media_remove" name="wpcm_tax_media_remove" value="<?php _e( 'Remove Image', 'wp-category-media' ); ?>" />
            </p>
    
            <label for="wpcm-category-image-width"><?php _e( 'Image Width', 'wp-category-media' ); ?></label>
            <input type="text" name="wpcm-category-image-width" id="wpcm-category-image-width" value="100%" placeholder="e.g., 100%" />
    
            <label for="wpcm-category-image-border-radius"><?php _e( 'Border Radius (px)', 'wp-category-media' ); ?></label>
            <input type="text" name="wpcm-category-image-border-radius" id="wpcm-category-image-border-radius" value="10" placeholder="e.g., 10px" />
        </div>
        <?php
    }

   /**
     * Save the category image.
     *
     * This method is responsible for saving the category image, width, and border radius metadata
     * for a given category in the WordPress admin. It verifies the nonce for security and ensures
     * the current user has the appropriate capabilities to modify category data. If the fields are 
     * set in the POST data, it sanitizes and saves the category's metadata. 
     *
     * @param int $term_id Term ID of the category being saved.
     * @param int $tt_id Term taxonomy ID (not used in this method but passed by WordPress).
     * 
     * @return void
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
    
        if ( isset( $_POST['wpcm-category-image-id'] ) && '' !== $_POST['wpcm-category-image-id'] ) {
            $image = absint( $_POST['wpcm-category-image-id'] );
            add_term_meta( $term_id, 'wpcm-category-image-id', $image, true );
        }
    
        if ( isset( $_POST['wpcm-category-image-width'] ) ) {
            $width = sanitize_text_field( $_POST['wpcm-category-image-width'] );
            add_term_meta( $term_id, 'wpcm-category-image-width', $width, true );
        }
    
        if ( isset( $_POST['wpcm-category-image-border-radius'] ) ) {
            $border_radius = sanitize_text_field( $_POST['wpcm-category-image-border-radius'] );
            add_term_meta( $term_id, 'wpcm-category-image-border-radius', $border_radius, true );
        }
    }

    /**
     * Update the category image form 
     *
     * This method displays the form fields for the category image, image width, and border radius 
     * in the category edit page. It retrieves the existing image, width, and border radius values 
     * from the term meta and populates the fields accordingly. It also generates a nonce for security 
     * and handles the "Add Image" or "Change Image" button text based on whether an image is already set.
     *
     * @param object $term Term object that contains the category's data.
     * @param string $taxonomy The taxonomy the term belongs to (not used in this method).
     * 
     * @return void
     */
    public function wpcm_updated_category_image( $term, $taxonomy ) {
        $nonce = wp_create_nonce( 'wpcm_category_image_nonce' );
        $image_id = get_term_meta( $term->term_id, 'wpcm-category-image-id', true );
        $image_width = get_term_meta( $term->term_id, 'wpcm-category-image-width', true );
        $border_radius = get_term_meta( $term->term_id, 'wpcm-category-image-border-radius', true );
        ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="wpcm-category-image-id"><?php _e( 'Image', 'wp-category-media' ); ?></label>
            </th>
            <td>
                <input type="hidden" id="wpcm-category-image-id" name="wpcm-category-image-id" value="<?php echo esc_attr( $image_id ); ?>">
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
    
                <label for="wpcm-category-image-width"><?php _e( 'Image Width', 'wp-category-media' ); ?></label>
                <input type="text" name="wpcm-category-image-width" id="wpcm-category-image-width" value="<?php echo esc_attr( $image_width ); ?>" placeholder="e.g., 100%" />
    
                <label for="wpcm-category-image-border-radius"><?php _e( 'Border Radius (px)', 'wp-category-media' ); ?></label>
                <input type="text" name="wpcm-category-image-border-radius" id="wpcm-category-image-border-radius" value="<?php echo esc_attr( $border_radius ); ?>" placeholder="e.g., 10px" />
            </td>
        </tr>
        <?php
    }

    /**
     * Update the category image field value.
     *
     * This method is responsible for updating the category image, width, and border radius metadata
     * for a given category in the WordPress admin. It verifies the nonce for security and ensures
     * the current user has the appropriate capabilities to modify category data. If the fields are 
     * set in the POST data, it sanitizes and updates the category's metadata with the new values. 
     * Otherwise, it clears the existing values.
     *
     * @param int $term_id Term ID of the category being updated.
     * @param int $tt_id Term taxonomy ID (not used in this method but passed by WordPress).
     * 
     * @return void
     */

    public function wpcm_update_category_image( $term_id, $tt_id ) {
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
    
        if ( isset( $_POST['wpcm-category-image-id'] ) && '' !== $_POST['wpcm-category-image-id'] ) {
            $image = absint( $_POST['wpcm-category-image-id'] );
            update_term_meta( $term_id, 'wpcm-category-image-id', $image );
        } else {
            update_term_meta( $term_id, 'wpcm-category-image-id', '' );
        }
    
        if ( isset( $_POST['wpcm-category-image-width'] ) ) {
            $width = sanitize_text_field( $_POST['wpcm-category-image-width'] );
            update_term_meta( $term_id, 'wpcm-category-image-width', $width );
        } else {
            update_term_meta( $term_id, 'wpcm-category-image-width', '' );
        }
    
        if ( isset( $_POST['wpcm-category-image-border-radius'] ) ) {
            $border_radius = sanitize_text_field( $_POST['wpcm-category-image-border-radius'] );
            update_term_meta( $term_id, 'wpcm-category-image-border-radius', $border_radius );
        } else {
            update_term_meta( $term_id, 'wpcm-category-image-border-radius', '' );
        }
    }

    /**
     * Load media files for category images.
     *
     * This method enqueues the necessary WordPress media files to enable the media uploader functionality.
     * The `wp_enqueue_media` function is called to ensure that the media uploader interface is available
     * in the WordPress admin when handling category images. This is crucial for allowing users to upload 
     * or select images directly from the media library.
     *
     * @return void
     */
    public function wpcm_load_media() {
        wp_enqueue_media();
    }

    /**
     * Add necessary JavaScript for category image functionality.
     *
     * This method enqueues JavaScript code to handle the functionality of the category image upload and removal.
     * It integrates with WordPress' media uploader to allow users to select or upload images directly in the admin interface.
     * The script also handles displaying the uploaded image as a thumbnail and removing the image when needed.
     * 
     * It is triggered on the admin side, where the user interacts with category image fields.
     *
     * @return void
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
                                $('#wpcm-category-image-id').val(attachment.id);
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
                    $('#wpcm-category-image-id').val('');
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


    /**
     * Automatically insert the category image at the top of the category template.
     *
     * This method hooks into the category template to check if the current page is a category archive page.
     * If it is, it retrieves the category's image from the term meta and inserts it at the top of the page.
     * The image is displayed using the `wp_get_attachment_image()` function.
     *
     * @param string $template The path to the template file.
     * 
     * @return string The path to the template file, unchanged.
     */

    public function wpcm_insert_category_image_top( $template ) {
        if ( is_category() ) {
           
            $category = get_queried_object();
            
            $image_id = get_term_meta( $category->term_id, 'wpcm-category-image-id', true );

            if ( $image_id ) {
               
                echo '<div class="wpcm-category-image">';
                echo wp_get_attachment_image( $image_id, 'medium' ); 
                echo '</div>';
            }
        }
        return $template; 
    }


    /**
     * Add a custom column for the category image in the categories list table.
     *
     * This method inserts a custom column, "category_image", at the beginning of the categories 
     * list table in the WordPress admin. It displays the category image or a placeholder 
     * for each category in the list.
     *
     * @param array $columns The current list of columns for the categories list table.
     * 
     * @return array Modified list of columns with the custom category image column added.
     */

    public function wpcm_add_category_image_column( $columns ) {
        $columns = array_slice( $columns, 0, 1, true ) + 
                   array( 'category_image' => __( 'Image', 'wp-category-media' ) ) + 
                   array_slice( $columns, 1, null, true );
    
        return $columns;
    }
    

   /**
     * Make the category image column sortable in the categories list table.
     *
     * This method adds the ability to sort the categories based on the custom category 
     * image column in the WordPress admin. By default, the column is not sortable, 
     * but this function registers it to be sortable by adding it to the `$columns` array.
     *
     * @param array $columns The current list of sortable columns.
     * 
     * @return array Modified list of sortable columns with the 'category_image' column added.
     */

    public function wpcm_sortable_category_image_column( $columns ) {
        $columns['category_image'] = 'category_image'; 
        return $columns;
    }


    /**
     * Display the category image in the custom category image column.
     *
     * This method is used to display the category image in the custom column added 
     * to the categories list table in the WordPress admin. It checks if a category 
     * has a set image, and if not, it uses a placeholder image. The image is then 
     * displayed with a fixed size of 60px x 60px.
     *
     * @param string $content The current content of the column.
     * @param string $column_name The name of the column.
     * @param int $term_id The ID of the term (category) being displayed.
     * 
     * @return string Modified content to display the category image or placeholder.
     */

    public function wpcm_display_category_image_column( $content, $column_name, $term_id ) {
        if ( 'category_image' === $column_name ) {
        
            if ( $term_id == 1 ) {
                
                $image_id = get_term_meta( $term_id, 'wpcm-category-image-id', true );
                
                if ( $image_id ) {
                  
                    $image_url = wp_get_attachment_url( $image_id );
                } else {
                    
                    $image_url = plugin_dir_url(dirname(__DIR__)) . 'assets/images/wpcm-placeholder.png';
                                
                }
            } else {
              
                $image_id = get_term_meta( $term_id, 'wpcm-category-image-id', true );
    
                if ( ! $image_id ) {
                    $image_url = plugin_dir_url(dirname(__DIR__)) . 'assets/images/wpcm-placeholder.png';
                } else {
                  
                    $image_url = wp_get_attachment_url( $image_id );
                }
            }
    
            $content = '<img src="' . esc_url( $image_url ) . '" class="attachment-thumbnail size-thumbnail" style="max-width: 60px; max-height: 60px;" />';
        }
        return $content;
    }
    


}
