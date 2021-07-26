<?php
/**
 * The sidebar for shop page
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Makali_Theme
 * @since Makali 1.0
 */
$makali_opt = get_option( 'makali_opt' );
$shopsidebar = 'left';
if(isset($makali_opt['sidebarshop_pos']) && $makali_opt['sidebarshop_pos']!=''){
	$shopsidebar = $makali_opt['sidebarshop_pos'];
}
if(isset($_GET['sidebar']) && $_GET['sidebar']!=''){
	$shopsidebar = $_GET['sidebar'];
}
$makali_shop_sidebar_extra_class = NULl;
if($shopsidebar=='left') {
	$makali_shop_sidebar_extra_class = 'order-lg-first';
}
?>
<?php if ( is_active_sidebar( 'sidebar-shop' ) ) : ?>
	<div id="secondary" class="col-12 col-lg-3 sidebar-shop <?php echo esc_attr($makali_shop_sidebar_extra_class);?>">
		<?php dynamic_sidebar( 'sidebar-shop' ); ?>
	</div>
<?php endif; ?>