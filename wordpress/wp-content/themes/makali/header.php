<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Makali_Theme
 * @since Makali 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php $makali_opt = get_option( 'makali_opt' ); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div class="wrapper <?php if($makali_opt['page_layout']=='box'){echo 'box-layout';}?> <?php if($makali_opt['page_layout']=='box_body'){echo 'box-body';}?>">
	<div class="page-wrapper">
		<?php if(isset($makali_opt['header_layout']) && $makali_opt['header_layout']!=''){
			$header_class = str_replace(' ', '-', strtolower($makali_opt['header_layout']));
		} else {
			$header_class = '';
		}
		if(isset($makali_opt['header_mobile_layout']) && $makali_opt['header_mobile_layout']!=''){
			$header_mobile_class = str_replace(' ', '-', strtolower($makali_opt['header_mobile_layout']));
		} else {
			$header_mobile_class = '';
		}
		if( (class_exists('RevSliderFront')) && (is_front_page() && has_shortcode( $post->post_content, 'rev_slider_vc')) ) {
			$hasSlider_class = 'rs-active';
		} else {
			$hasSlider_class = '';
		}
		?>
		<div class="header-container <?php echo esc_html($header_class)." ".esc_html($hasSlider_class) ?> <?php if(isset($makali_opt['page_banner']['url']) && ($makali_opt['page_banner']['url'])!=''){ echo 'has-page-banner'; } ?>">
			<div class="header">
				<div class="header-content">
					<?php
					if ( isset($makali_opt['header_layout']) && $makali_opt['header_layout']!="") {
						$jscomposer_templates_args = array(
							'orderby'          => 'title',
							'order'            => 'ASC',
							'post_type'        => 'templatera',
							'post_status'      => 'publish',
							'posts_per_page'   => 100,
							'suppress_filters' => false,
						);
						$jscomposer_templates = get_posts( $jscomposer_templates_args );
						if(count($jscomposer_templates) > 0) {
							foreach($jscomposer_templates as $jscomposer_template){
								if($jscomposer_template->post_title == $makali_opt['header_layout']){
								?>
									<div class="header-composer">
										<?php
											echo do_shortcode($jscomposer_template->post_content);
										?>
									</div>
								<?php
								}
								// header mobile
								if ( isset($makali_opt['header_mobile_layout']) && $makali_opt['header_mobile_layout'] != "") {
									if($jscomposer_template->post_title == $makali_opt['header_mobile_layout']){ ?>
										<div class="header-mobile <?php echo esc_html($header_mobile_class); ?>">
											<div class="container">
												<?php 
													echo do_shortcode($jscomposer_template->post_content);
												?>
											</div>
										</div>
									<?php }
								}
							}
						}
					} else {
						?>
						<div class="header-default">
							<div class="container">
								<div class="logo-wrapper">
									<div class="row">
										<div class="col-12 col-xl-3">
											<?php if( isset($makali_opt['logo_main']['url']) && $makali_opt['logo_main']['url']!=''){ ?>
												<div class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo esc_url($makali_opt['logo_main']['url']); ?>" alt=" <?php echo esc_attr( get_bloginfo( 'name', 'display' ) ) ?> " /></a></div>
											<?php
											} else { ?>
												<h1 class="logo site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
												<?php
											} ?>
										</div>
										<div class="col-12 col-xl-8">
											<div class="nav-container">
												<?php if ( has_nav_menu( 'primary' ) ) : ?>
													<div class="horizontal-menu visible-large">
														<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'primary-menu-container', 'menu_class' => 'nav-menu' ) ); ?>
													</div>
												<?php endif; ?>
											</div> 
										</div>
										<div class="col-12 col-xl-1">
											<div class="header-search">
												<div class="search-dropdown">
													<?php get_search_form(); ?>
												</div>
											</div>
										</div>
									</div>
									<?php if ( has_nav_menu( 'mobilemenu' ) ) : ?>
										<div class="visible-small mobile-menu"> 
											<div class="mbmenu-toggler"><?php echo esc_html($makali_opt['mobile_menu_label']);?><span class="mbmenu-icon"><i class="fa fa-bars"></i></span></div>
											<div class="clearfix"></div>
											<?php wp_nav_menu( array( 'theme_location' => 'mobilemenu', 'container_class' => 'mobile-menu-container', 'menu_class' => 'nav-menu' ) ); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
					} 
					?>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php if($makali_opt['page_layout']=='box_body') { ?>
			<div class="box-body-inner">
		<?php } ?>