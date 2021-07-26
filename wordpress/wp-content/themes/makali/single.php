<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Makali_Theme
 * @since Makali 1.0
 */
$makali_opt = get_option( 'makali_opt' );
get_header();
$makali_bloglayout = 'sidebar';
if(isset($makali_opt['blog_layout']) && $makali_opt['blog_layout']!=''){
	$makali_bloglayout = $makali_opt['blog_layout'];
}
if(isset($_GET['layout']) && $_GET['layout']!=''){
	$makali_bloglayout = $_GET['layout'];
}
$makali_blogsidebar = 'right';
if(isset($makali_opt['sidebarblog_pos']) && $makali_opt['sidebarblog_pos']!=''){
	$makali_blogsidebar = $makali_opt['sidebarblog_pos'];
}
if(isset($_GET['sidebar']) && $_GET['sidebar']!=''){
	$makali_blogsidebar = $_GET['sidebar'];
}
if ( !is_active_sidebar( 'sidebar-1' ) )  {
	$makali_bloglayout = 'nosidebar';
}
switch($makali_bloglayout) {
	case 'sidebar':
		$makali_blogclass = 'blog-sidebar';
		$makali_blogcolclass = 9;
		break;
	default:
		$makali_blogclass = 'blog-nosidebar'; //for both fullwidth and no sidebar
		$makali_blogcolclass = 12;
		$makali_blogsidebar = 'none';
}
?>
<div class="main-container">
	<div class="breadcrumb-container">
		<div class="container">
			<?php Makali_Class::makali_breadcrumb(); ?> 
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12 <?php echo 'col-lg-'.$makali_blogcolclass; ?>">
				<div class="page-content blog-page single <?php echo esc_attr($makali_blogclass); if($makali_blogsidebar=='left') {echo ' left-sidebar'; } if($makali_blogsidebar=='right') {echo ' right-sidebar'; } ?> ">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', get_post_format() ); ?>
						<?php comments_template( '', true ); ?>
					<?php endwhile; // end of the loop. ?>
				</div>
			</div>
			<?php
			$customsidebar = get_post_meta( $post->ID, '_makali_custom_sidebar', true );
			$customsidebar_pos = get_post_meta( $post->ID, '_makali_custom_sidebar_pos', true );
			if($customsidebar != ''){
				if($customsidebar_pos == 'left' && is_active_sidebar( $customsidebar ) ) {
					echo '<div id="secondary" class="col-12 col-lg-3 order-lg-last">';
						dynamic_sidebar( $customsidebar );
					echo '</div>';
				} 
			} else {
				if($makali_blogsidebar=='left') {
					get_sidebar();
				}
			} ?>
			<?php
			if($customsidebar != ''){
				if($customsidebar_pos == 'right' && is_active_sidebar( $customsidebar ) ) {
					echo '<div id="secondary" class="col-12 col-lg-3">';
						dynamic_sidebar( $customsidebar );
					echo '</div>';
				} 
			} else {
				if($makali_blogsidebar=='right') {
					get_sidebar();
				}
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
							<div class="title">
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