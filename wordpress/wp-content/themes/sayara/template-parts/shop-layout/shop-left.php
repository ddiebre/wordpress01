<?php
if ( woocommerce_product_loop() ) { ?>
	<div class="products-filter-area">
		<div class="row">
			<div class="col-md-9 my-auto"><?php woocommerce_result_count(); ?></div>
			<div class="col-md-3 my-auto text-right"><?php woocommerce_catalog_ordering(); ?></div>
		</div>
	</div>

	<div class="row">
		<?php if ( is_active_sidebar('woocommerce_store_sidebar') ){ ?>
		<div class="col-xl-3 order-2 order-lg-0">
			<aside id="secondary" class="woocommerce-widget-area">
				<?php dynamic_sidebar( 'woocommerce_store_sidebar' ); ?>
			</aside>
		</div>
        <?php } ?>
		<div class="<?php if ( is_active_sidebar('woocommerce_store_sidebar') ){ echo'col-xl-9'; } else { echo'col-lg-12'; } ?>">
			<?php 
			woocommerce_output_all_notices();
			woocommerce_product_loop_start();
			
			if ( have_posts() ) { while ( have_posts() ) { the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action( 'woocommerce_shop_loop' ); ?>

					<div class="col-xl-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) )?> col-md-6>">
						<?php wc_get_template_part( 'content', 'product' ); ?>
					</div>
				<?php 
				}
			}

			woocommerce_product_loop_end(); ?>
			<div class="text-left mt-5">
				<?php
				the_posts_pagination( array(
				    'mid_size'  => 2,
				    'prev_text' => esc_html__( '&#10094; Prev', 'sayara' ),
				    'next_text' => esc_html__( 'Next &#10095;', 'sayara' ),
				) ); ?>
			</div>
		</div>
		
	</div>
	
	
<?php 

} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
} ?>