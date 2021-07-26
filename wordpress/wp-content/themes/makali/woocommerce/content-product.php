<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 30.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product, $woocommerce_loop;
$makali_opt = get_option( 'makali_opt' );
$makali_viewmode = Makali_Class::makali_show_view_mode();
$makali_productsfound = Makali_Class::makali_shortcode_products_count();
// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;
// Extra post classes
$classes = array();
$count   = $product->get_rating_count();
$colwidth = 3;
if($woocommerce_loop['columns'] > 0){
	$colwidth = round(12/$woocommerce_loop['columns']);
}
$classes[] = ' item-col col-12 col-full-hd col-md-'.$colwidth ;?>
<?php if ( ( 0 == ( $woocommerce_loop['loop'] ) % 2 ) && ( $woocommerce_loop['columns'] == 2 ) ) {
	echo '<div class="group">';
} ?>
<?php if ( ( 0 == ( $woocommerce_loop['loop'] ) % 3 ) && ( $woocommerce_loop['columns'] == 3 ) ) {
	echo '<div class="group">';
} ?>
<?php if ( ( 0 == ( $woocommerce_loop['loop'] ) % 4 ) && ( $woocommerce_loop['columns'] == 4 ) ) {
	echo '<div class="group">';
} ?>
<?php if ( ( 0 == ( $woocommerce_loop['loop'] ) % 5 ) && ( $woocommerce_loop['columns'] == 5 ) ) {
	echo '<div class="group">';
} ?>
<div <?php post_class( $classes ); ?>>
	<div class="product-wrapper gridview">
		<div class="list-col4">
			<div class="count-down">
				<?php
				$countdown = false;
				$sale_end = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
				/* simple product */
				if($sale_end){
					$countdown = true;
					$sale_end = date('Y/m/d', (int)$sale_end);
					?>
					<div class="countbox hastime" data-time="<?php echo esc_attr($sale_end); ?>"></div>
				<?php } ?>
				<?php /* variable product */
				if($product->has_child()){
					$vsale_end = array();
					$pvariables = $product->get_children();
					foreach($pvariables as $pvariable){
						$vsale_end[] = (int)get_post_meta( $pvariable, '_sale_price_dates_to', true );
						if( get_post_meta( $pvariable, '_sale_price_dates_to', true ) ){
							$countdown = true;
						}
					}
					if($countdown){
						/* get the latest time */
						$vsale_end_date = max($vsale_end);
						$vsale_end_date = date('Y/m/d', $vsale_end_date);
						?>
						<div class="countbox hastime" data-time="<?php echo esc_attr($vsale_end_date); ?>"></div>
					<?php
					}
				}
				?>
			</div>
			<div class="product-image">
				<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
				<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
				<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
				<div class="wishlist-compare">
					<ul>
						<li class="add-to-cart">
							<?php echo do_shortcode('[add_to_cart id="'.$product->get_id().'"]') ?>
						</li>
						<li class="add-to-wishlist"> 
							<?php if ( class_exists( 'YITH_WCWL' ) ) {
								echo preg_replace("/<img[^>]+\>/i", " ", do_shortcode('[yith_wcwl_add_to_wishlist]'));
							} ?>
						</li>
						<li class="add-to-compare">
							<?php if( class_exists( 'YITH_Woocompare' ) ) {
								echo do_shortcode('[yith_compare_button]');
							} ?>
						</li>
						<?php if ( isset($makali_opt['quickview']) && $makali_opt['quickview'] ) { ?>
							<li class="quickviewbtn">
								<a class="detail-link quickview fa fa-external-link" data-quick-id="<?php the_ID();?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo esc_html($makali_opt['detail_link_text']);?></a>
							</li>
						<?php } ?>
					</ul>
				</div>
				<?php if ( isset($makali_opt['quickview']) && $makali_opt['quickview'] ) { ?>
					<div class="quickviewbtn">
						<a class="detail-link quickview fa fa-external-link" data-quick-id="<?php the_ID();?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo esc_html($makali_opt['detail_link_text']);?></a>
					</div>
				<?php } ?> 
				<div class="add-to-cart">
					<?php echo do_shortcode('[add_to_cart id="'.$product->get_id().'"]') ?>
				</div>
			</div>
		</div>
		<div class="list-col8">
			<div class="product-name">
				<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</div>
			<?php if ( $product->get_price() != '' )  { ?>
				<div class="price-box">
					<div class="price-box-inner">
						<?php echo ''.$product->get_price_html(); ?>
					</div>
				</div>
			<?php } ?>
			<!-- end price -->
			<div class="cart-rating">
				<div class="add-to-cart">
					<?php echo do_shortcode('[add_to_cart id="'.$product->get_id().'"]') ?>
				</div>
				<?php if ($count) { ?>
					<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
					<!-- hook rating -->
				<?php } ?>
			</div>
			<?php
			if ( $product->is_in_stock() ) {
				$units_sold = get_post_meta( $product->get_id(), 'total_sales', true );
			?>
			<div class="p-stock-quanlity">
				<div class="pst-inner">
					<div class="product-available">
						<?php echo sprintf( __( 'Available: <span>%s</span>', 'makali' ), $product->get_stock_quantity() ); ?>
					</div>
					<?php if ( $units_sold ) { ?>
						<div class="product-sold">
							<?php echo sprintf( __( 'Sold: <span>%s</span>', 'makali' ), $units_sold ); ?>
						</div>
					<?php } ?>
				</div>
				<?php 
					printf("<div data-pid='%s' data-psold='%s' data-pqty='%s' class='progressBar progressBar-%s'><div></div></div>", $product->get_id(), $units_sold,$product->get_stock_quantity(), $product->get_id())
				?>
			</div>
			<div class="count-down">
				<?php
				$countdown = false;
				$sale_end = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
				/* simple product */
				if($sale_end){
					$countdown = true;
					$sale_end = date('Y/m/d', (int)$sale_end);
					?>
					<div class="countbox hastime" data-time="<?php echo esc_attr($sale_end); ?>"></div>
				<?php } ?>
				<?php /* variable product */
				if($product->has_child()){
					$vsale_end = array();
					$pvariables = $product->get_children();
					foreach($pvariables as $pvariable){
						$vsale_end[] = (int)get_post_meta( $pvariable, '_sale_price_dates_to', true );
						if( get_post_meta( $pvariable, '_sale_price_dates_to', true ) ){
							$countdown = true;
						}
					}
					if($countdown){
						/* get the latest time */
						$vsale_end_date = max($vsale_end);
						$vsale_end_date = date('Y/m/d', $vsale_end_date);
						?>
						<div class="countbox hastime" data-time="<?php echo esc_attr($vsale_end_date); ?>"></div>
					<?php
					}
				}
				?>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="product-wrapper listview">
		<div class="row">
			<div class="list-col4 <?php if($makali_viewmode=='list-view'){ echo ' col-12 col-md-4';} ?>">
				<div class="product-image">
					<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
					<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
					<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
					<?php if ( isset($makali_opt['quickview']) && $makali_opt['quickview'] ) { ?>
						<div class="quickviewbtn">
							<a class="detail-link quickview fa fa-external-link" data-quick-id="<?php the_ID();?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo esc_html($makali_opt['detail_link_text']);?></a>
						</div>
					<?php } ?> 
				</div>
			</div>
			<div class="list-col8 <?php if($makali_viewmode=='list-view'){ echo ' col-12 col-md-8';} ?>">
				<div class="product-name">
					<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</div>
				<?php if ( $product->get_price() != '' )  { ?>
					<div class="price-box">
						<?php echo ''.$product->get_price_html(); ?>
					</div>
				<?php } ?>
				<!-- end price -->
				<?php if ($count) { ?>
					<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
					<!-- hook rating -->
				<?php } ?>
				<?php if ( has_excerpt() ) { ?>
					<div class="product-desc">
						<?php the_excerpt(); ?>
					</div>
				<?php } ?>
				<!-- end desc -->
				<div class="actions">
					<ul>
						<li class="add-to-cart">
							<?php echo do_shortcode('[add_to_cart id="'.$product->get_id().'"]') ?>
						</li>
						<li class="add-to-wishlist"> 
							<?php if ( class_exists( 'YITH_WCWL' ) ) {
								echo preg_replace("/<img[^>]+\>/i", " ", do_shortcode('[yith_wcwl_add_to_wishlist]'));
							} ?>
						</li>
						<li class="add-to-compare">
							<?php if( class_exists( 'YITH_Woocompare' ) ) {
								echo do_shortcode('[yith_compare_button]');
							} ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ( ( ( 0 == $woocommerce_loop['loop'] % 2 || $makali_productsfound == $woocommerce_loop['loop'] ) && $woocommerce_loop['columns'] == 2 )  ) { /* for odd case: $makali_productsfound == $woocommerce_loop['loop'] */
	echo '</div>';
} ?>
<?php if ( ( ( 0 == $woocommerce_loop['loop'] % 3 || $makali_productsfound == $woocommerce_loop['loop'] ) && $woocommerce_loop['columns'] == 3 )  ) { /* for odd case: $makali_productsfound == $woocommerce_loop['loop'] */
	echo '</div>';
} ?>
<?php if ( ( ( 0 == $woocommerce_loop['loop'] % 4 || $makali_productsfound == $woocommerce_loop['loop'] ) && $woocommerce_loop['columns'] == 4 )  ) { /* for odd case: $makali_productsfound == $woocommerce_loop['loop'] */
	echo '</div>';
} ?>
<?php if ( ( ( 0 == $woocommerce_loop['loop'] % 5 || $makali_productsfound == $woocommerce_loop['loop'] ) && $woocommerce_loop['columns'] == 5 )  ) { /* for odd case: $makali_productsfound == $woocommerce_loop['loop'] */
	echo '</div>';
} ?>