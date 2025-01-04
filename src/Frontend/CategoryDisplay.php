<?php
namespace WPCategoryMedia\Frontend;

use WPCategoryMedia\Traits\SingletonTrait;

class CategoryDisplay {

    use SingletonTrait;

    public function __construct() {
        $this->init();
    }

    private function init() {
        add_shortcode( 'wpcm_category_display', array( $this, 'render_category_display' ) );
    }

   /**
     * Render the categories and posts display.
     *
     * This method generates the HTML output for displaying categories with associated posts 
     * in a grid layout. The categories are fetched based on specific attributes, and 
     * each category's image and related posts are displayed in a styled layout.
     *
     * @param array $atts Shortcode attributes.
     *      - 'posts_per_category': The number of posts to display per category.
     *      - 'exclude_category': The category ID to exclude from the display.
     * @return string HTML output with categories and posts.
     *
     * @package WPCategoryMedia
     * @since 1.0.0
     */
    
    public function render_category_display( $atts ) {
        
        $atts = shortcode_atts(
            array(
                'posts_per_category' => 4, 
                'exclude_category' => 1,    
            ),
            $atts,
            'wpcm_category_display'
        );

        
        $cat_args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'exclude' => $atts['exclude_category']
        );
        $categories = get_categories( $cat_args );

        ob_start(); 
        ?>
        <div class="wpcm-catlay_1 container">
            <div class="row">
                <?php
                
                foreach ($categories as $category) {
                    
                    $args = array(
                        'posts_per_page' => $atts['posts_per_category'],
                        'category__in' => array( $category->term_id ),
                        'post_status' => 'publish', 
                        'ignore_sticky_posts' => 1, 
                    );
                    $cat_id = $category->term_id;
                    $image_id = get_term_meta( $cat_id, 'wpcm-category-image-id', true );
                    $posts = get_posts( $args );

                
                    if (count($posts) >= 1) { ?>
                        <div class="column">
                            <div class="wpcm-catlay_1 item">
                                <div class="header">
                                    <div class="cat-title">
                                        <?php
                                        echo '<p class="category-title"><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __("View all posts in %s"), $category->name ) . '" >' . $category->name . '</a></p>';
                                        ?>
                                    </div>
                                    <div class="category-image">
                                        <?php echo wp_get_attachment_image( $image_id, '' ); ?>
                                    </div>
                                </div>
                                <?php
                            
                                foreach ($posts as $post) {
                                    ?>
                                    <ul class="category-posts-list">
                                        <li>
                                            <a href="<?php echo get_permalink( $post->ID ); ?>" rel="bookmark" title="Category Link <?php echo get_the_title( $post->ID ); ?>">
                                                <?php echo mb_strimwidth(get_the_title( $post->ID ), 0, 30, ''); ?>
                                            </a>
                                        </li>
                                    </ul>
                                <?php } ?>
                                <div class="footer">
                                    <button>
                                        <a href="<?php echo get_category_link($category->term_id); ?>">
                                            <?php echo "View All"; ?>
                                        </a>
                                    </button>
                                    <!-- <p>Posts Count: <?php //echo $category->count; ?></p> -->
                                </div>
                            </div>
                        </div>
                    <?php }
                }
                ?>
            </div>
        </div>
        <?php
        return ob_get_clean(); 
    }


}
