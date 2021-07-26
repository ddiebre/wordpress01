<?php

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    $opt_name = "sayara_opt";
    $theme = wp_get_theme();

    $args = array(
        'opt_name'             => $opt_name,
        'display_name'         => $theme->get( 'Name' ),
        'display_version'      => $theme->get( 'Version' ),
        'menu_type'            => 'menu',
        'allow_sub_menu'       => true,
        'menu_title'           => esc_html__( 'Sayara', 'sayara' ),
        'page_title'           => esc_html__( 'Sayara', 'sayara' ),
        'google_api_key'       => '',
        'google_update_weekly' => false,
        'async_typography'     => false,
        'admin_bar'            => true,
        'admin_bar_icon'       => 'dashicons-portfolio',
        'admin_bar_priority'   => 50,
        'global_variable'      => '',
        'dev_mode'             => false,
        'update_notice'        => true,
        'customizer'           => true,
        'page_priority'        => null,
        'page_parent'          => 'themes.php',
        'page_permissions'     => 'manage_options',
        'menu_icon'            => '',
        'last_tab'             => '',
        'page_icon'            => 'icon-themes',
        'page_slug'            => '_options',
        'save_defaults'        => true,
        'default_show'         => false,
        'default_mark'         => '',
        'show_import_export'   => true,
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        'output_tag'           => true,
        'database'             => '',
        'use_cdn'              => true,

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'light',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    Redux::setArgs( $opt_name, $args );


    // General
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'General', 'sayara' ),
        'id'     => 'general',
        'desc'   => esc_html__( 'General theme options.', 'sayara' ),
        'icon'   => 'el el-home',
        'fields' => array(
            array(
                'id'       => 'site_preloader',
                'type'     => 'switch',
                'title'    => esc_html__( 'Preloader', 'sayara' ),
                'default'  => true,
            ),
            array(
                'id'       => 'site_preloader_image',
                'type'     => 'media', 
                'url'      => true,
                'title'    => __('Preloader image', 'sayara'),
                'default'  => array(
                    'url'=> get_template_directory_uri().'/assets/images/preloader.gif'
                )
            )
        )
    ));

    // Style
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Style', 'sayara' ),
        'id'     => 'style',
        'desc'   => esc_html__( 'Header menu options.', 'sayara' ),
        'icon'   => 'el el-edit',
        'fields' => array(
            array(
                'id'       => 'primary_color',
                'type'     => 'color_gradient',
                'title'    => esc_html__('Primary Color', 'sayara'), 
                'subtitle' => esc_html__('Pick a color for the theme (default: #e52727 and #e52727).', 'sayara'),
                'validate' => 'color',
                'default'  => array(
                    'from' => '#e52727',
                    'to'   => '#e52727',
                ),

            ),  
        )
    ));

    // Typography
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Typography', 'sayara' ),
        'id'               => 'page_title_typography',  
        'icon'   => 'el el-pencil',
        'fields'           => array(
            array(
                'id'          => 'sayara_heading_typography',
                'type'        => 'typography',
                'title'       => esc_html__( 'Heading Typography', 'sayara' ),
                'subtitle'    => esc_html__('H1, H2, H3,H4, H5, H6  Tags', 'sayara'),
                'google'      => true, 
                'font-backup' => true,
                'output'      => array('h1,h2,h3,h4,h5,h6'),
                'units'       =>'px',
                'default'     => array(
                    'color'       => '#333',
                    'font-weight' => '500', 
                    'font-family' => 'Rubik', 
                    'google'      => true,
                ),
            ),
            array(
                'id'          => 'sayara_typography',
                'type'        => 'typography',
                'title'       => esc_html__( 'Typography', 'sayara' ),
                'subtitle'    => esc_html__('body, p Tags', 'sayara'),
                'google'      => true, 
                'font-backup' => true,
                'output'      => array('body,p'),
                'units'       =>'px',
                'default'     => array(
                    'color'       => '#808080', 
                    'font-weight'  => 'normal', 
                    'line-height' => '26px',
                    'font-family' => 'Rubik', 
                    'google'      => true,
                    'font-size'   => '16px',
                ),
            )
        )
    ) );

    // Header
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Header', 'sayara' ),
        'id'     => 'header',
        'desc'   => esc_html__( 'Header menu options.', 'sayara' ),
        'icon'   => 'el el-heart-empty',
        'fields' => array(
            array(
                'id'       => 'header_style',
                'type'     => 'select',
                'title'    => esc_html__( 'Header Style', 'sayara' ),
                'options'  => array(
                    'style1' => esc_html__( 'Style one','sayara' ), 
                    'style2' => esc_html__( 'Style two','sayara' )
                ),
                'default'  => 'style2',
            ),
            array(
                'id'       => 'sayara_top_header',
                'type'     => 'switch',
                'title'    => esc_html__( 'Top Header', 'sayara' ),
                'default'  => true,
                'required' => array( 'header_style','equals', 'style2' )
            ),
            array(
                'id'       => 'sayara_header_full_width',
                'type'     => 'switch',
                'title'    => esc_html__( 'Full Width Header', 'sayara' ),
                'subtitle' => esc_html__( 'Controls the width of the header area. ', 'sayara' ),
                'default'  => false
            ),
            array(
                'id'       => 'sayara_header_sticky',
                'type'     => 'switch',
                'title'    => esc_html__( 'Sticky Header', 'sayara' ),
                'subtitle' => esc_html__( 'Turn on to activate the sticky header.', 'sayara' ),
                'default'  => false
            ),
            array(
                'id'       => 'sayara_departments_menu',
                'type'     => 'switch',
                'title'    => esc_html__( 'Navbar departments menu', 'sayara' ),
                'default'  => true,
                'required' => array( 'header_style','equals', 'style2' )
            ),
            array(
                'id'       => 'sayara_departments_menu_text',
                'type'     => 'text',
                'title'    => esc_html__( 'Navbar departments menu text', 'sayara' ),
                'default'  => esc_html__( 'Shop By Category', 'sayara' ),
                'required' => array('sayara_departments_menu','equals', true)
            ),
            array(
                'id'       => 'sayara_navbar_button',
                'type'     => 'switch',
                'title'    => esc_html__( 'Navbar button', 'sayara' ),
                'default'  => true,
                'required' => array( 'header_style','equals', 'style2' )
            ),
            array(
                'id'       => 'sayara_navbar_button_text',
                'type'     => 'text',
                'title'    => esc_html__( 'Navbar button text', 'sayara' ),
                'default'  => esc_html__( 'Buy This theme', 'sayara' ),
                'required' => array('sayara_navbar_button','equals', true)

            ),
            array(
                'id'       => 'sayara_navbar_button_url',
                'type'     => 'text',
                'title'    => esc_html__('Navbar button URL', 'sayara'),
                'default'   => '#',
                'required' => array('sayara_navbar_button','equals', true)
            ),
            array(         
                'id'       => 'breadcrumbs',
                'type'     => 'background',
                'output'     => array('.breadcrumbs'),
                'title'    => esc_html__('Breadcrumbs', 'sayara'),
                'subtitle' => esc_html__('Set breadcrumbs background with image, color', 'sayara'),
                'default'  => array(
                    'background-color' => '#000',
                )
            )
        )
    ) );

    // Blog Page
    Redux::setSection( $opt_name, array(
        'title' => esc_html__( 'Blog Page', 'sayara' ),
        'id'    => 'blog_page',
        'icon'  => 'el el-wordpress',
        'fields'     => array(         
            array(
                'id'       => 'blog_breadcrumb_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Breadcrumb Title', 'sayara' ),
                'default'  => esc_html__( 'Latest Blog', 'sayara' ),
            ),   
            array(
                'id'               => 'sayara_excerpt_length',
                'type'             => 'slider',
                'title'            => esc_html__('Excerpt Length', 'sayara'),
                'subtitle'         => esc_html__('Controls the excerpt length on blog page','sayara'),
                "default"          => 55,
                "min"              => 10,
                "step"             => 2,
                "max"              => 130,
                'display_value'    => 'text'
            )
            
        )
    ) );

    // Single Blog
    Redux::setSection( $opt_name, array(
        'title' => esc_html__( 'Single Blog Page', 'sayara' ),
        'id'    => 'single_blog_page',
        'icon'  => 'el el-wordpress',
        'subsection' => true,
        'fields'     => array(              
            array(
                'id'       => 'social_share',
                'type'     => 'switch',
                'title'    => esc_html__( 'Social Share', 'sayara' ),
                'default'  => true,
            ),
            array(
                'id'       => 'sayara_blog_details_post_navigation',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Navigation (Next/Previous)', 'sayara' ),
                'default'  => true,
            ),
            array(
                'id'       => 'related_posts',
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Related Post', 'sayara' ),
                'default'  => true,
            ),
            array(
                'id'       => 'related_post_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Related Post Title', 'sayara' ),
                'required' => array( 'related_posts','equals', true ),
                'default'  => esc_html__( 'Related Post', 'sayara' ),
            ),
            array(
                'id' => 'posts_per_page',
                'type' => 'slider',
                'title' => esc_html__( 'Related Posts', 'sayara' ),
                'subtitle' => esc_html__( 'Related posts per page', 'sayara' ),
                'desc' => esc_html__('Number of related posts to display. Min: 1, max: Unlimited, step: 1, default value: 4', 'sayara'),
                "default" => 3,
                "min" => 1,
                "step" => 1,
                "max" => 10000,
                'required' => array( 'related_posts','equals', true ),
                'display_value' => 'text'
            ),
            array(
                'id'       => 'related_posts_columns',
                'type'     => 'select',
                'title'    => esc_html__( 'Posts Column', 'sayara' ), 
                'subtitle' => esc_html__( 'Number of column', 'sayara' ),
                'desc'     => esc_html__( 'Specify the number of related posts column.', 'sayara' ),
                'required' => array( 'related_posts','equals', true ),
                'options'  => array(
                    '12' => esc_html__( 'One Column','sayara' ), 
                     '6' => esc_html__( 'Two Columns','sayara' ), 
                     '4' => esc_html__( 'Three Columns','sayara' ), 
                     '3' => esc_html__( 'Four Columns','sayara' ), 
                     '2' => esc_html__( 'Six Columns','sayara' ),
                ),
                'default'  => '4',
            )
        )
    ) );


    // WooCommerce
    Redux::setSection( $opt_name, array(
        'title' => esc_html__( 'WooCommerce', 'sayara' ),
        'id'    => 'woocommerce',
        'icon'  => 'el el-shopping-cart',
        'fields'     => array(
            array(
                'id' => 'products_per_page',
                'type' => 'slider',
                'title' => esc_html__( 'Products Per Page', 'sayara' ),
                'subtitle' => esc_html__( 'Product per page', 'sayara' ),
                'desc' => esc_html__('Number of products to display. Min: 1, max: Unlimited, step: 1, default value: 4', 'sayara'),
                "default" => 9,
                "min" => 1,
                "step" => 1,
                "max" => 10000,
                'display_value' => 'text'
            ),
            array(
                'id'       => 'shop_layout',
                'type'     => 'select',
                'title'    => esc_html__( 'Store layout', 'sayara' ),
                'desc'     => esc_html__( 'Specify the number of related products column.', 'sayara' ),
                'options'  => array(
                    'full_width' => esc_html__( 'Full width','sayara' ), 
                    'left_sidebar' => esc_html__( 'Left sidebar','sayara' ), 
                    'right_sidebar' => esc_html__( 'Right sidebar','sayara' )
                ),
                'default'  => 'full_width',
            ),
            array(
                'id'       => 'shop_columns',
                'type'     => 'select',
                'title'    => esc_html__( 'Products Column', 'sayara' ), 
                'subtitle' => esc_html__( 'Number of column', 'sayara' ),
                'desc'     => esc_html__( 'Specify the number of related products column.', 'sayara' ),
                'options'  => array(
                    '12' => esc_html__( 'One Column','sayara' ), 
                     '6' => esc_html__( 'Two Columns','sayara' ), 
                     '4' => esc_html__( 'Three Columns','sayara' ), 
                     '3' => esc_html__( 'Four Columns','sayara' ), 
                     '2' => esc_html__( 'Six Columns','sayara' ),
                ),
                'default'  => '3',
            ),
            array(
                'id'       => 'product_title_length',
                'type'     => 'slider',
                'title'    => esc_html__( 'Product title length', 'sayara' ),
                "default" => 25,
                "min" => 1,
                "step" => 1,
                "max" => 100
            ),
            array(
                'id'       => 'product_title_trimmarker',
                'type'     => 'text',
                'title'    => esc_html__( 'Product title trimmarker', 'sayara' ),
                'desc'    => esc_html__( 'End character of the title', 'sayara' ),
                "default" => '...'
            )
        )
    ) );

    // WooCommerce Single
    Redux::setSection( $opt_name, array(
        'title' => esc_html__( 'WooCommerce Single', 'sayara' ),
        'id'    => 'woocommerce_single',
        'icon'  => 'el el-shopping-cart',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'woocommerce_social_share',
                'type'     => 'switch',
                'title'    => esc_html__( 'Social Share', 'sayara' ),
                'default'  => true,
            ),
            array(
                'id'       => 'related_products',
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Related Products', 'sayara' ),
                'default'  => true,
            ),
            array(
                'id'       => 'related_products_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Related Product Title', 'sayara' ),
                'required' => array( 'related_products','equals', true ),
                'default'  => esc_html__( 'Related products', 'sayara' ),
            ),
            array(
                'id' => 'related_products_per_page',
                'type' => 'slider',
                'title' => esc_html__( 'Related Products', 'sayara' ),
                'subtitle' => esc_html__( 'Related product per page', 'sayara' ),
                'desc' => esc_html__('Number of related products to display. Min: 1, max: Unlimited, step: 1, default value: 4', 'sayara'),
                "default" => 4,
                "min" => 1,
                "step" => 1,
                "max" => 12,
                'required' => array( 'related_products','equals', true ),
                'display_value' => 'text'
            ),
            array(
                'id'       => 'related_products_columns',
                'type'     => 'select',
                'title'    => esc_html__( 'Products Column', 'sayara' ), 
                'subtitle' => esc_html__( 'Number of column', 'sayara' ),
                'desc'     => esc_html__( 'Specify the number of related products column.', 'sayara' ),
                'required' => array( 'related_products','equals', true ),
                'options'  => array(
                    '12' => esc_html__( 'One Column','sayara' ), 
                     '6' => esc_html__( 'Two Columns','sayara' ), 
                     '4' => esc_html__( 'Three Columns','sayara' ), 
                     '3' => esc_html__( 'Four Columns','sayara' ), 
                     '2' => esc_html__( 'Six Columns','sayara' ),
                ),
                'default'  => '3',
            )
        )
    ) );

    // Newsletter Modal
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Newsletter Modal', 'sayara' ),
        'id'     => 'newsletter_modal',
        'icon'   => 'el el-envelope',
        'fields' => array(
            array(
                'id'          => 'newsletter_modal_switch',
                'type'        => 'switch',
                'title'       => esc_html__( 'Newsletter Modal', 'sayara' ),
                'default'  => true,
            ),
            array(
                'id'          => 'modal_image',
                'type'        => 'media',
                'title'       => esc_html__( 'Modal image', 'sayara' ),
                'default'  => '#',
                'required' => array( 'newsletter_modal_switch','equals', true ),
            ),
            array(
                'id'          => 'modal_title',
                'type'        => 'text',
                'title'       => esc_html__( 'Modal title', 'sayara' ),
                'default'     => esc_html__( 'Subscribe And Get 30% Discount!', 'sayara' ),
                'required'    => array( 'newsletter_modal_switch','equals', true ),
            ),
            array(
                'id'          => 'modal_description',
                'type'        => 'textarea',
                'title'       => esc_html__( 'Modal description', 'sayara' ),
                'default'     => esc_html__( 'Subscribe to our newsletter to get updates and big discount offer!.', 'sayara' ),
                'required'    => array( 'newsletter_modal_switch','equals', true ),
            ),
            array(
                'id'          => 'modal_shortcode',
                'type'        => 'text',
                'title'       => esc_html__( 'Modal shortcode', 'sayara' ),
                'default'  => '[mc4wp_form id="302"]',
                'required' => array( 'newsletter_modal_switch','equals', true ),
            ),
            array(
                'id'          => 'modal_timeout',
                'type'        => 'text',
                'title'       => esc_html__( 'Modal timeout', 'sayara' ),
                'default'  => 5000,
                'required' => array( 'newsletter_modal_switch','equals', true ),
            )
        )
    ) );


    // Footer
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Footer', 'sayara' ),
        'id'     => 'footer',
        'icon'   => 'el el-arrow-down',
        'fields' => array(
            array(
                'id'          => 'footer_widget_display',
                'type'        => 'switch',
                'title'       => esc_html__( 'Footer widget display', 'sayara' ),
                'default'  => true,
            ),
            array(
                'id'          => 'backtotop',
                'type'        => 'switch',
                'title'       => esc_html__( 'Back to top', 'sayara' ),
                'default'  => true,
            ),
            array(
                'id'              => 'sayara_copyright_info',
                'type'            => 'editor',
                'title'           => esc_html__( 'Copyright text', 'sayara' ),
                'subtitle'        => esc_html__( 'Enter your company information here. HTML tags allowed: a, br, em, strong', 'sayara' ),
                'default'         => esc_html__( 'Copyright © 2020 sayara All Rights Reserved.', 'sayara' ),
                'args'            => array(
                'wpautop'         => false,
                'teeny'           => true,
                'textarea_rows'   => 5
                )
            ),
            array(
                'id'          => 'supported_currency',
                'type'        => 'slides',
                'title'       => esc_html__('Supported currency', 'sayara'),
                'subtitle'    => esc_html__('Unlimited currency with drag and drop sortings.', 'sayara')
            )
        )
    ) );

    // 404 
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( '404 Error', 'sayara' ),
        'id'     => 'error-page',
        'icon'   => 'el el-error-alt',
        'fields' => array(
            array(
                'id'          => 'sayara_error_title',
                'type'        => 'text',
                'title'       => esc_html__( 'Error title', 'sayara' ),
                'default'     => esc_html__( 'Oops! That page can’t be found.', 'sayara' ),
                ),
            array(
                'id'          => 'sayara_error_text',
                'type'        => 'textarea',
                'title'       => esc_html__('Error message', 'sayara'),
                'subtitle'    => esc_html__('Enter "not found" error message.', 'sayara'),
                'default'     => esc_html__('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'sayara'),
                )
            ),
    ) );