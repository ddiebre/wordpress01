<?php
/**
 * The sidebar for content page
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Makali_Theme
 * @since Makali 1.0
 */
$makali_opt = get_option( 'makali_opt' );
$makali_page_sidebar_extra_class = NULl;
if($makali_opt['sidebarse_pos']=='left') {
	$makali_page_sidebar_extra_class = 'order-lg-first';
}
?>
<?php if ( is_active_sidebar( 'sidebar-page' ) ) : ?>
<div id="secondary" class="col-12 col-lg-3 <?php echo esc_attr($makali_page_sidebar_extra_class);?>">
	<?php dynamic_sidebar( 'sidebar-page' ); ?>
</div>
<?php endif; ?>