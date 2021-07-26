<div class="<?php echo esc_attr($header_classes); ?>">
    <?php
    /**
     * Hook - top bar header
     */
    do_action('nasa_topbar_header');
    ?>
    <div class="sticky-wrapper">
        <div id="masthead" class="site-header">
            <?php do_action('nasa_mobile_header'); ?>
            
            <div class="row nasa-hide-for-mobile">
                <div class="large-12 columns nasa-wrap-event-search">
                    <div class="nasa-header-flex nasa-elements-wrap">
                        <!-- Logo -->
                        <div class="nasa-flex-item logo-wrapper">
                            <?php echo elessi_logo(); ?>
                        </div>

                        <!-- Search form in header -->
                        <div class="nasa-flex-item fgr-1 nasa-header-search-wrap nasa-search-relative">
                            <?php echo elessi_search('full'); ?>
                        </div>
                        
                        <!-- Group icon header -->
                        <div class="nasa-flex-item icons-wrapper">
                            <?php echo $nasa_header_icons; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main menu -->
            <div class="nasa-elements-wrap nasa-elements-wrap-main-menu nasa-hide-for-mobile nasa-elements-wrap-bg">
                <div class="row">
                    <div class="large-12 columns">
                        <div class="wide-nav nasa-wrap-width-main-menu nasa-bg-wrap<?php echo esc_attr($menu_warp_class); ?>">
                            <div class="nasa-menus-wrapper-reponsive nasa-loading" data-padding_y="<?php echo (int) $data_padding_y; ?>" data-padding_x="<?php echo (int) $data_padding_x; ?>">
                                <div id="nasa-menu-vertical-header" class="nasa-menu-vertical-header rtl-right">
                                    <?php elessi_get_vertical_menu(); ?>
                                </div>
                                
                                <?php elessi_get_main_menu(); ?>
                            </div>
                        </div>
                    </div>
                </div>
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
