<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Makali_Theme
 * @since Makali 1.0
 */
$makali_opt = get_option( 'makali_opt' );
?>
			<?php if(isset($makali_opt['footer_layout']) && $makali_opt['footer_layout']!=''){
				$footer_class = str_replace(' ', '-', strtolower($makali_opt['footer_layout']));
			} else {
				$footer_class = '';
			} 
			if( (class_exists('RevSliderFront')) && (is_front_page() && has_shortcode( $post->post_content, 'rev_slider_vc')) ) {
				$hasSlider_class = 'rs-active';
			} else {
				$hasSlider_class = '';
			}
			?>
			<div class="footer <?php echo esc_html($footer_class)." ".esc_html($hasSlider_class);?>">
				<div class="footer-inner">
					<?php
					if ( isset($makali_opt['footer_layout']) && $makali_opt['footer_layout']!="" ) {
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
								if($jscomposer_template->post_title == $makali_opt['footer_layout']){
									echo do_shortcode($jscomposer_template->post_content);
								}
							}
						}
					} else { ?>
						<div class="footer-default">
							<div class="container">
								<div class="widget-copyright">
									<?php esc_html_e( "Copyright", 'makali' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ) ?>"> <?php echo get_bloginfo('name') ?></a> <?php echo date('Y') ?>. <?php esc_html_e( "All Rights Reserved", 'makali' ); ?>
								</div>
							</div>
						</div>
					<?php
					}
					?>
				</div>
			</div>
		</div><!-- .page -->
	</div><!-- .wrapper -->
	<!--<div class="makali_loading"></div>-->
	<?php if ( isset($makali_opt['back_to_top']) && $makali_opt['back_to_top'] ) { ?>
	<div id="back-top"></div>
	<?php } ?>
	<?php wp_footer(); ?> 
	<?php if($makali_opt['page_layout']=='box_body') { ?>
		</div>
	<?php } ?>
</body>
</html>