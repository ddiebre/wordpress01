<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Makali_Theme
 * @since Makali 1.0
 */
$makali_opt = get_option( 'makali_opt' );
get_header();
?>
	<div class="main-container error404">
		<div class="container">
			<div class="search-form-wrapper">
				<h2><?php esc_html_e( "OOPS! PAGE NOT BE FOUND", 'makali' ); ?></h2>
				<p class="home-link"><?php esc_html_e( "Sorry but the page you are looking for does not exist, has been removed, changed or is temporarity unavailable.", 'makali' ); ?></p>
				<?php get_search_form(); ?>
				<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Back to home', 'makali' ); ?>"><?php esc_html_e( 'Back to home page', 'makali' ); ?></a>
			</div>
		</div>
		<!-- brand logo -->
		<?php 
			if(isset($makali_opt['inner_brand']) && function_exists('makali_brands_shortcode') && shortcode_exists( 'Makali' ) ){
				if($makali_opt['inner_brand'] && isset($makali_opt['brand_logos'][0]) && $makali_opt['brand_logos'][0]['thumb']!=null) { ?>
					<div class="inner-brands">
						<div class="container">
							<?php if(isset($makali_opt['inner_brand_title']) && $makali_opt['inner_brand_title']!=''){ ?>
								<div class="title">
									<h3><?php echo esc_html( $makali_opt['inner_brand_title'] ); ?></h3>
								</div>
							<?php } ?>
							<?php echo do_shortcode('[Makali]'); ?>
						</div>
					</div>
				<?php }
			}
		?>
		<!-- end brand logo -->  
	</div>
<?php get_footer(); ?>