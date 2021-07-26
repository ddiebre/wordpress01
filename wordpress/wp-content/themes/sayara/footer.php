<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sayara
 */

global $sayara_opt;

$footer_widget_display = !empty( $sayara_opt['footer_widget_display'] ) ? $sayara_opt['footer_widget_display'] : true;
$sayara_copyright_info = isset( $sayara_opt['sayara_copyright_info'] ) ? $sayara_opt['sayara_copyright_info'] : '';
$supported_currency = isset( $sayara_opt['supported_currency'] ) ? $sayara_opt['supported_currency'] : '';
$newsletter_modal_switch = isset( $sayara_opt['newsletter_modal_switch'] ) ? $sayara_opt['newsletter_modal_switch'] : '';
$modal_image = isset( $sayara_opt['modal_image']['url'] ) ? $sayara_opt['modal_image']['url'] : '';
$modal_title = isset( $sayara_opt['modal_title'] ) ? $sayara_opt['modal_title'] : '';
$modal_description = isset( $sayara_opt['modal_description'] ) ? $sayara_opt['modal_description'] : '';
$modal_shortcode = isset( $sayara_opt['modal_shortcode'] ) ? $sayara_opt['modal_shortcode'] : '';
$modal_timeout = isset( $sayara_opt['modal_timeout'] ) ? $sayara_opt['modal_timeout'] : 5000;
$backtotop = isset( $sayara_opt['backtotop'] ) ? $sayara_opt['backtotop'] : true;
?>


	<footer id="colophon" class="site-footer">
		<?php if ( $footer_widget_display == true & is_active_sidebar('footer') ): ?>
			<div class="footer-widgets">
				<div class="container">
	                <div class="row">
	                    <?php dynamic_sidebar( 'footer' ); ?>
	                </div>
				</div>
			</div>
		<?php endif ?>


		<div class="copyright-bar">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-sm-<?php if ( $supported_currency ) { echo'7 text-left'; }else{ echo'12 text-center'; }?>">
						<p>
						<?php
			    		if( $sayara_copyright_info ) {
							echo wp_kses( $sayara_copyright_info , array(
								'a'       => array(
								'href'    => array(),
								'title'   => array()
								),
								'br'      => array(),
								'em'      => array(),
								'strong'  => array(),
								'img'     => array(
									'src' => array(),
									'alt' => array()
								),
							));
						} else {
							echo esc_html__('Copyright', 'sayara'); ?> &copy; <?php echo esc_html( date("Y") ).' '.esc_html( get_bloginfo('name') ).' '.esc_html__(' All Rights Reserved.', 'sayara' );
						}
						?>
						</p>
					</div>
					<?php if ($supported_currency) { ?>
						<div class="col-sm-5 currency-footer">
							<?php foreach ( $supported_currency as $key => $currency ) { ?>
								<img src="<?php echo esc_url($currency['image']) ?>" alt="<?php echo esc_attr($currency['title']) ?>">
							<?php } ?>
						</div>
					<?php } ?>
					
				</div>
			</div>
		</div>
	</footer>

<?php if ($backtotop == true) {?>
	<!--======= Back to Top =======-->
	<div id="backtotop"><i class="fal fa-lg fa-arrow-up"></i></div>
<?php } ?>


<?php if ($newsletter_modal_switch == true): ?>
	<div class="modal fade" id="newsletterModal" tabindex="-1" data-time="<?php echo esc_attr( $modal_timeout ) ?>" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	        <a href="#" type="button" class="close" id="dont-show-hour" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	      	</a>
		    <div class="modal-body">
    			<img src="<?php echo esc_html( $modal_image ) ?>" alt="<?php echo esc_attr( $modal_title ) ?>">
    			<div class="modal-text-content">
	    			<h2><?php echo esc_html( $modal_title ) ?></h2>
	    			<p><?php echo esc_html( $modal_description ) ?></p>
	    			<div class="mt-4"><?php echo do_shortcode( $modal_shortcode ) ?></div>

	    			<div class="d-inline-block mt-3">
					    <input type="checkbox" class="form-check-input" id="dont-show">
					    <label class="form-check-label" for="dont-show"><?php echo esc_html__( 'Don\'t show this message again', 'sayara' ) ?></label>
				    </div>
			    </div>
		    </div>
	    </div>
	  </div>
	</div>
<?php endif ?>

<?php wp_footer(); ?>

</body>
</html>
