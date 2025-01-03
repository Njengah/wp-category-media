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
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
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

        // Counter 
        //  View All 
        //  button bg
        // button color
        // button radius
        // button color 
        // list icon  
        // list padding 
        //

        
        $cat_args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'exclude' => $atts['exclude_category']
        );
        $categories = get_categories( $cat_args );
        
       
        ob_start();
        ?>
        <div class="njenqah-catlay_1 container">
            <div class="row">
                <?php
                foreach ($categories as $category) {
                    $args = array(
                        'posts_per_page' => $atts['posts_per_category'],
                        'category__in' => array( $category->term_id ),
                    );
                    $cat_id = $category->term_id;
                    $image_id = get_term_meta( $cat_id, 'category-image-id', true );
                    $posts = get_posts( $args );

                    if (count($posts) >= 1) { ?>
                        <div class="column">
                            <div class="njenqah-catlay_1 item">
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
                                    setup_postdata($post);
                                    ?>
                                    <ul class="category-posts-list">
                                        <li>
                                            <a href="<?php the_permalink(); ?>" rel="bookmark" title="Category Link <?php the_title_attribute(); ?>">
                                                <?php echo mb_strimwidth(get_the_title(), 0, 30, ''); ?>
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
                                    <?php echo $category->count; ?>
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
