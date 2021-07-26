<div class="<?php echo esc_attr($header_classes); ?>">
    <div class="sticky-wrapper">
        <div id="masthead" class="site-header">
            <?php do_action('nasa_mobile_header'); ?>
            
            <div class="row nasa-hide-for-mobile">
                <div class="large-12 columns nasa-wrap-event-search">
                    <div class="row nasa-elements-wrap">
                        <!-- Group icon header -->
                        <div class="large-4 columns nasa-min-height first-columns rtl-right">
                            <a class="nasa-menu-off nasa-icon fa fa-bars" href="javascript:void(0);" rel="nofollow"></a>
                            <a class="search-icon desk-search nasa-icon nasa-search icon-nasa-search inline-block" href="javascript:void(0);" data-open="0" title="Search" rel="nofollow"></a>
                        </div>

                        <!-- Logo -->
                        <div class="large-4 columns nasa-fixed-height text-center rtl-right">
                            <?php echo elessi_logo(); ?>
                        </div>

                        <!-- Group icon header -->
                        <div class="large-4 columns rtl-right">
                            <?php echo $nasa_header_icons; ?>
                        </div>
                    </div>
                    
                    <div class="nasa-header-search-wrap">
                        <?php echo elessi_search('icon'); ?>
                    </div>
                </div>
            </div>
            
            <!-- Main menu -->
            <div class="nasa-off-canvas hidden-tag">
                <?php elessi_get_main_menu(); ?>
                <?php do_action('nasa_multi_lc'); ?>
            </div>
            
            <?php if (isset($show_cat_top_filter) && $show_cat_top_filter) : ?>
                <div class="nasa-top-cat-filter-wrap">
                    <?php echo elessi_get_all_categories(false, true); ?>
                    <a href="javascript:void(0);" title="<?php esc_attr_e('Close', 'elessi-theme'); ?>" class="nasa-close-filter-cat nasa-stclose nasa-transition" rel="nofollow"></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
