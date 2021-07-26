<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package sayara 
 */
global $sayara_opt;

$sayara_error_title = !empty( $sayara_opt['sayara_error_title'] ) ? $sayara_opt['sayara_error_title'] : __( 'Oops! That page can&rsquo;t be found.', 'sayara' );
$sayara_error_text = !empty( $sayara_opt['sayara_error_text'] ) ? $sayara_opt['sayara_error_text'] : __( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'sayara' );

get_header(); ?>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="error-404">
				<h1><?php echo esc_html( $sayara_error_title ); ?></h1>
				<p><?php echo esc_html( $sayara_error_text ); ?></p>
				<a href="<?php echo esc_url( get_home_url() ); ?>" class="sayara-btn"><?php echo esc_html__( 'Go to Home', 'sayara' ); ?></a>
			</div>
		</div>
	</div>
</div>

<?php get_footer();