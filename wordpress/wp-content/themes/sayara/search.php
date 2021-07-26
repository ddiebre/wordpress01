<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package sayara
 */

get_header();
?>
<section class="section-padding">
	<div class="container">
		<div class="row justify-content-center">
			<div class="<?php if ( is_active_sidebar('sidebar') ){ echo'col-xl-9 col-md-7'; } else { echo'col-lg-12'; } ?>">
				<?php
				if (have_posts()) :
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;

					the_posts_pagination( array(
					    'mid_size'  => 2,
					    'prev_text' => esc_html__( '&#10094; Prev', 'sayara' ),
					    'next_text' => esc_html__( 'Next &#10095;', 'sayara' ),
					) );

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

			</div>
			<?php if ( is_active_sidebar('sidebar') ){ ?>
				<div class="col-xl-3 col-md-5">
					<?php get_sidebar() ?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>

<?php get_footer();
