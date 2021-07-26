<?php
/**
 * The template for displaying Author Archive pages
 *
 * Used to display archive-type pages for posts by an author.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
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
		Makali_Class::makali_post_thumbnail_size('makali-post-thumb');
		break;
	case 'largeimage':
		$makali_blogclass = 'blog-large';
		$makali_blogcolclass = 9;
		Makali_Class::makali_post_thumbnail_size('makali-post-thumbwide');
		break;
	case 'grid':
		$makali_blogclass = 'grid';
		$makali_blogcolclass = 9;
		Makali_Class::makali_post_thumbnail_size('makali-post-thumbwide');
		break;
	default:
		$makali_blogclass = 'blog-nosidebar';
		$makali_blogcolclass = 12;
		$makali_blogsidebar = 'none';
		Makali_Class::makali_post_thumbnail_size('makali-post-thumb');
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
			<?php if($makali_blogsidebar=='left') : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			<div class="col-12 <?php echo 'col-lg-'.$makali_blogcolclass; ?>">
				<div class="blog-page blogs <?php echo esc_attr($makali_blogclass); if($makali_blogsidebar=='left') {echo ' left-sidebar'; } if($makali_blogsidebar=='right') {echo ' right-sidebar'; } ?>">
					<header class="entry-header">
						<h1 class="entry-title"><?php if(isset($makali_opt['blog_header_text'])) { echo esc_html($makali_opt['blog_header_text']); } else { esc_html_e('Blog', 'makali');}  ?></h1>
					</header>
					<?php if ( have_posts() ) : ?>
						<?php
							/* Queue the first post, that way we know
							 * what author we're dealing with (if that is the case).
							 *
							 * We reset this later so we can run the loop
							 * properly with a call to rewind_posts().
							 */
							the_post();
						?>
						<header class="archive-header">
							<h2 class="archive-title"><?php printf( esc_html__( 'Author Archives: %s', 'makali' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h2>
						</header><!-- .archive-header -->
						<?php
							/* Since we called the_post() above, we need to
							 * rewind the loop back to the beginning that way
							 * we can run the loop properly, in full.
							 */
							rewind_posts();
						?>
						<?php
						// If a user has filled out their description, show a bio on their entries.
						if ( get_the_author_meta( 'description' ) ) : ?>
						<div class="author-info archives">
							<div class="author-avatar">
								<?php
								/**
								 * Filter the author bio avatar size.
								 *
								 * @since Makali 1.0
								 *
								 * @param int $size The height and width of the avatar in pixels.
								 */
								$author_bio_avatar_size = apply_filters( 'makali_author_bio_avatar_size', 68 );
								echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
								?>
							</div><!-- .author-avatar -->
							<div class="author-description">
								<h2><?php printf( esc_html__( 'About %s', 'makali' ), get_the_author() ); ?></h2>
								<p><?php the_author_meta( 'description' ); ?></p>
							</div><!-- .author-description	-->
						</div><!-- .author-info -->
						<?php endif; ?>
						<div class="post-container">
							<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'content', get_post_format() ); ?>
							<?php endwhile; ?>
						</div>
						<?php Makali_Class::makali_pagination(); ?>
					<?php else : ?>
						<?php get_template_part( 'content', 'none' ); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php if( $makali_blogsidebar=='right') : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
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