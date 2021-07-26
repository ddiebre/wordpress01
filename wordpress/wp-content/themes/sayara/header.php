<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sayara
 */


global $sayara_opt;

$site_preloader = !empty( $sayara_opt['site_preloader'] ) ? $sayara_opt['site_preloader'] : '';
$site_preloader_image = !empty( $sayara_opt['site_preloader_image']['url'] ) ? $sayara_opt['site_preloader_image']['url'] : '';
$blog_breadcrumb_title = !empty($sayara_opt['blog_breadcrumb_title']) ? $sayara_opt['blog_breadcrumb_title'] : esc_html__( 'Latest news', 'sayara' );
$header_style = isset($sayara_opt['header_style']) ? $sayara_opt['header_style'] : '';

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	
	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>>
	 <?php wp_body_open(); ?>
	
	<?php if ($site_preloader): ?>
		<!-- Preloading -->
		<div id="preloader">
		    <div class="spinner">
				<img src="<?php echo esc_attr( $site_preloader_image ); ?>" alt="<?php the_title_attribute() ?>">
		    </div>
		</div>
	<?php endif ?>
	
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sayara' ); ?></a>

    <?php 
    if ( $header_style == 'style1' ) {
        get_template_part( 'template-parts/header/header', '1' );
    } elseif ( $header_style == 'style2' ) {
        get_template_part( 'template-parts/header/header', '2' );
    } else {
        get_template_part( 'template-parts/header/header', '1' );
    } ?>
	
	<?php if ( !is_page_template( 'custom-page-without-breadcrumb.php' ) ) { ?>
		
	
	<section class="breadcrumbs">
		<div class="container">
			<h1>
		    	<?php
		      	if(is_home() && is_front_page()){
		            echo esc_html( $blog_breadcrumb_title ); 
		      	} elseif( class_exists( 'WooCommerce' ) ) {
		      		if (is_product()) {
		      			echo esc_html__('Product','sayara');
		      		} else {
		      			echo wp_title('', false);
		      		}
		      	} else { 
		            echo wp_title('', false);
		      	} ?>
		    </h1>
			<?php sayara_breadcrumb(); ?>
		</div>
	</section>
	
	<?php } ?>
	