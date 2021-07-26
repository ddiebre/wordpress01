<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Makali_Theme
 * @since Makali 1.0
 */
$makali_opt = get_option( 'makali_opt' );
$customsidebar = get_post_meta( $post->ID, '_makali_custom_sidebar', true );
$customsidebar_pos = get_post_meta( $post->ID, '_makali_custom_sidebar_pos', true );
$makali_page_main_extra_class = NULl;
if (is_active_sidebar( $customsidebar )) {
	if ($customsidebar_pos == 'left') {
		$makali_page_main_extra_class = 'order-lg-last';
	}
} elseif (is_active_sidebar('page') && isset($makali_opt['sidebarse_pos']) && $makali_opt['sidebarse_pos']=='left') {
	$makali_page_main_extra_class = 'order-lg-last';
}
get_header();
?>
<div class="main-container default-page">
	<div class="breadcrumb-container">
		<div class="container">
			<?php Makali_Class::makali_breadcrumb(); ?>
		</div>
	</div>
	<div class="container">
		<div class="row"> 
			<div class="col-12 <?php if ( (is_active_sidebar( $customsidebar ) && $customsidebar !='') || is_active_sidebar( 'sidebar-page' ) ) : ?>col-lg-9<?php endif; ?> <?php echo esc_attr($makali_page_main_extra_class);?>">
				<header class="entry-header">
					<h2 class="entry-title"><?php the_title(); ?></h2>
				</header>
				<div class="page-content default-page">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', 'page' ); ?>
						<?php comments_template( '', true ); ?>
					<?php endwhile; // end of the loop. ?>
				</div>
			</div>
			<?php
			if($customsidebar != ''){
				if($customsidebar_pos == 'left' && is_active_sidebar( $customsidebar ) ) {
					echo '<div id="secondary" class="col-12 col-lg-3 order-lg-first">';
						dynamic_sidebar( $customsidebar );
					echo '</div>';
				}
				if($customsidebar_pos == 'right' && is_active_sidebar( $customsidebar ) ) {
					echo '<div id="secondary" class="col-12 col-lg-3">';
						dynamic_sidebar( $customsidebar );
					echo '</div>';
				} 
			} else {
				get_sidebar('page');
			} ?>
		</div>
	</div> 
	<!-- brand logo -->
	<?php 
		if(isset($makali_opt['inner_brand']) && function_exists('makali_brands_shortcode') && shortcode_exists( 'ourbrands' ) ){
			if($makali_opt['inner_brand'] && isset($makali_opt['brand_logos'][0]) && $makali_opt['brand_logos'][0]['thumb']!=null) { ?>
				<div class="inner-brands">
					<div class="container">
						<?php if(isset($makali_opt['inner_brand_title']) && $makali_opt['inner_brand_title']!=''){ ?>
							<div class="heading-title style1 ">
								<h3><?php echo esc_html( $makali_opt['inner_brand_title'] ); ?></h3>
							</div>
						<?php } ?>
						<?php echo do_shortcode('[ourbrands]'); ?>
					</div>
				</div>
			<?php }
		}
	?>
	<!-- end brand logo --> 
</div>
<?php get_footer(); ?>