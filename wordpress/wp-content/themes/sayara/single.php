<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package sayara
 */

global $sayara_opt;
 
$related_posts = !empty( $sayara_opt['related_posts'] ) ? $sayara_opt['related_posts'] : '';

$sayara_blog_details_post_navigation = !empty( $sayara_opt['sayara_blog_details_post_navigation'] ) ? $sayara_opt['sayara_blog_details_post_navigation'] : '';

get_header(); ?>

<section class="section-padding bg-gray">
    <div class="container">
    	<div class="row justify-content-center">
    		<div class="<?php if ( is_active_sidebar('sidebar') ){ echo'col-xl-9 col-md-7'; } else { echo'col-lg-12'; } ?>">
    		<?php
            
    		while ( have_posts() ) : the_post();
                if (function_exists('setPostViews')) {
                    setPostViews(get_the_id());
                }
    			
    			get_template_part( 'template-parts/content', get_post_type() );

    			if ( true == $sayara_blog_details_post_navigation ) {
    				the_post_navigation( array(
    		            'prev_text' => esc_html__('&#171; Previous Post', 'sayara'),
    		            'next_text' => esc_html__('Next Post &#187;', 'sayara')
    		        ) );
    			}

                if ( $related_posts == true ){
                    sayara_related_posts();
                }

    			// If comments are open or we have at least one comment, load up the comment template.
    			if ( comments_open() || get_comments_number() ) :
    				comments_template();
    			endif;

    		endwhile; // End of the loop.
    		?>
    		</div>
            <?php if ( is_active_sidebar('sidebar') ){ ?>
    		<div class="col-xl-3 col-md-5">
    			<?php get_sidebar(); ?>
    		</div>
            <?php } ?>
    	</div>
    </div>
</section>

<?php get_footer();