<?php
if( ! function_exists( 'makali_get_slider_setting' ) ) {
	function makali_get_slider_setting() {
		return array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'makali' ),
				'param_name'  => 'style',
				'value'       => array(
					__( 'Grid view', 'makali' )     => 'product-grid',
					__( 'List view', 'makali' )     => 'product-list',
					__( 'Countdown', 'makali' )     => 'product-countdown',
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Enable slider', 'makali' ),
				'description' => __( 'If slider is enabled, the "column" ins General group is the number of rows ', 'makali' ),
				'param_name'  => 'enable_slider',
				'value'       => true,
				'save_always' => true, 
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: over 1201px)', 'makali' ),
				'param_name' => 'items_1201up',
				'group'      => __( 'Slider Options', 'makali' ),
				'value'      => esc_html__( '4', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: 993px - 1200px)', 'makali' ),
				'param_name' => 'items_993_1200',
				'group'      => __( 'Slider Options', 'makali' ),
				'value'      => esc_html__( '4', 'makali' ),
			), 
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: 769px - 992px)', 'makali' ),
				'param_name' => 'items_769_992',
				'group'      => __( 'Slider Options', 'makali' ),
				'value'      => esc_html__( '3', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: 641px - 768px)', 'makali' ),
				'param_name' => 'items_641_768',
				'group'      => __( 'Slider Options', 'makali' ),
				'value'      => esc_html__( '2', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: 481px - 640px)', 'makali' ),
				'param_name' => 'items_481_640',
				'group'      => __( 'Slider Options', 'makali' ),
				'value'      => esc_html__( '2', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: under 480px)', 'makali' ),
				'param_name' => 'items_0_480',
				'group'      => __( 'Slider Options', 'makali' ),
				'value'      => esc_html__( '1', 'makali' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Navigation', 'makali' ),
				'param_name'  => 'navigation',
				'save_always' => true,
				'group'       => __( 'Slider Options', 'makali' ),
				'value'       => array(
					__( 'Yes', 'makali' ) => true,
					__( 'No', 'makali' )  => false,
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Pagination', 'makali' ),
				'param_name'  => 'pagination',
				'save_always' => true,
				'group'       => __( 'Slider Options', 'makali' ),
				'value'       => array(
					__( 'No', 'makali' )  => false,
					__( 'Yes', 'makali' ) => true,
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Item Margin (unit:pixel)', 'makali' ),
				'param_name'  => 'item_margin',
				'value'       => 30,
				'save_always' => true,
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Slider speed number (unit: second)', 'makali' ),
				'param_name'  => 'speed',
				'value'       => '500',
				'save_always' => true,
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Slider loop', 'makali' ),
				'param_name'  => 'loop',
				'value'       => true,
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Slider Auto', 'makali' ),
				'param_name'  => 'auto',
				'value'       => true,
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Navigation style', 'makali' ),
				'param_name'  => 'navigation_style',
				'group'       => __( 'Slider Options', 'makali' ),
				'value'       => array(
					'Navigation center horizontal'	=> 'navigation-style1',
					'Navigation top-right'	        => 'navigation-style2',
				),
			),
		);
	}
}
//Shortcodes for Visual Composer
add_action( 'vc_before_init', 'makali_vc_shortcodes' );
function makali_vc_shortcodes() { 
	//Main Menu
	vc_map( array(
		'name'        => esc_html__( 'Main Menu', 'makali'),
		'description' => __( 'Set Primary Menu in Apperance - Menus - Manage Locations', 'makali' ),
		'base'        => 'roadmainmenu',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => 'attach_image',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Upload sticky logo image', 'makali' ),
				'param_name' => 'sticky_logoimage',
				'value'      => '',
			),
		)
	) );
	//Main Menu 2
	vc_map( array(
		'name'        => esc_html__( 'Main Menu 2', 'makali'),
		'description' => __( 'Set Primary Menu 2 in Apperance - Menus - Manage Locations', 'makali' ),
		'base'        => 'roadmainmenu2',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => 'attach_image',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Upload sticky logo image', 'makali' ),
				'param_name' => 'sticky_logoimage',
				'value'      => '',
			),
		)
	) );
	//Mobile Menu
	vc_map( array(
		'name'        => esc_html__( 'Mobile Menu', 'makali'),
		'description' => esc_html__( 'Set Mobile Menu in Apperance - Menus - Manage Locations', 'makali' ),
		'base'        => 'roadmobilemenu',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Set Mobile Menu in Apperance - Menus - Manage Locations', 'makali' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Wishlist
	vc_map( array(
		'name'        => esc_html__( 'Wishlist', 'makali'),
		'description' => esc_html__( 'Wishlist', 'makali' ),
		'base'        => 'roadwishlist',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'This widget does not have settings', 'makali' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Categories Menu
	vc_map( array(
		'name'        => esc_html__( 'Categories Menu', 'makali'),
		'description' => __( 'Set Categories Menu in Apperance - Menus - Manage Locations', 'makali' ),
		'base'        => 'roadcategoriesmenu',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(),
	) );
	//Social Icons
	vc_map( array(
		'name'        => esc_html__( 'Social Icons', 'makali'),
		'description' => __( 'Configure icons and links in Theme Options', 'makali' ),
		'base'        => 'roadsocialicons',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(),
	) );
	//Mini Cart
	vc_map( array(
		'name'        => esc_html__( 'Mini Cart', 'makali'),
		'description' => __( 'Mini Cart', 'makali' ),
		'base'        => 'roadminicart',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(),
	) );
	//Products Search without dropdown
	vc_map( array(
		'name'        => esc_html__( 'Product Search (No dropdown)', 'makali'),
		'description' => __( 'Product Search (No dropdown)', 'makali' ),
		'base'        => 'roadproductssearch',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(),
	) );
	//Products Search with dropdown
	vc_map( array(
		'name'        => esc_html__( 'Product Search (Dropdown)', 'makali'),
		'description' => __( 'Product Search (Dropdown)', 'makali' ),
		'base'        => 'roadproductssearchdropdown',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(),
	) );
	//Image slider
	vc_map( array(
		'name'        => esc_html__( 'Image slider', 'makali' ),
		'description' => __( 'Upload images and links in Theme Options', 'makali' ),
		'base'        => 'image_slider',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of rows', 'makali' ),
				'param_name' => 'rows',
				'value'      => array(
					'1'	=> '1',
					'2'	=> '2',
					'3'	=> '3',
					'4'	=> '4',
				),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: over 1201px)', 'makali' ),
				'param_name' => 'items_1201up',
				'value'      => esc_html__( '4', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 993px - 1200px)', 'makali' ),
				'param_name' => 'items_993_1200',
				'value'      => esc_html__( '4', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 769px - 992px)', 'makali' ),
				'param_name' => 'items_769_992',
				'value'      => esc_html__( '3', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 641px - 768px)', 'makali' ),
				'param_name' => 'items_641_768',
				'value'      => esc_html__( '2', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 481px - 640px)', 'makali' ),
				'param_name' => 'items_481_640',
				'value'      => esc_html__( '2', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: under 480px)', 'makali' ),
				'param_name' => 'items_0_480',
				'value'      => esc_html__( '1', 'makali' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Navigation', 'makali' ),
				'param_name' => 'navigation',
				'value'      => array(
					__( 'Yes', 'makali' ) => true,
					__( 'No', 'makali' )  => false,
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Pagination', 'makali' ),
				'param_name' => 'pagination',
				'value'      => array(
					__( 'No', 'makali' )  => false,
					__( 'Yes', 'makali' ) => true,
				),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Item Margin (unit:pixel)', 'makali' ),
				'param_name' => 'item_margin',
				'value'      => 30,
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Slider speed number (unit: second)', 'makali' ),
				'param_name' => 'speed',
				'value'      => '500',
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => __( 'Slider loop', 'makali' ),
				'param_name' => 'loop',
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => __( 'Slider Auto', 'makali' ),
				'param_name' => 'auto',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Style', 'makali' ),
				'param_name' => 'style',
				'value'      => array(
					__( 'Style 1', 'makali' )  => 'style1',
					__( 'Style 2', 'makali' )  => 'style2',
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Navigation style', 'makali' ),
				'param_name'  => 'navigation_style',
				'value'       => array(
					__( 'Navigation center horizontal', 'makali' )  => 'navigation-style1',
					__( 'Navigation top-right', 'makali' )          => 'navigation-style2',
				),
			),
		),
	) );
	//Brand logos
	vc_map( array(
		'name'        => esc_html__( 'Brand Logos', 'makali' ),
		'description' => __( 'Upload images and links in Theme Options', 'makali' ),
		'base'        => 'ourbrands',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of rows', 'makali' ),
				'param_name' => 'rows',
				'value'      => array(
					'1'	=> '1',
					'2'	=> '2',
					'3'	=> '3',
					'4'	=> '4',
				),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: over 1201px)', 'makali' ),
				'param_name' => 'items_1201up',
				'value'      => esc_html__( '5', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 993px - 1200px)', 'makali' ),
				'param_name' => 'items_993_1200',
				'value'      => esc_html__( '5', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 769px - 992px)', 'makali' ),
				'param_name' => 'items_769_992',
				'value'      => esc_html__( '4', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 641px - 768px)', 'makali' ),
				'param_name' => 'items_641_768',
				'value'      => esc_html__( '3', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 481px - 640px)', 'makali' ),
				'param_name' => 'items_481_640',
				'value'      => esc_html__( '2', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: under 480px)', 'makali' ),
				'param_name' => 'items_0_480',
				'value'      => esc_html__( '1', 'makali' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Navigation', 'makali' ),
				'param_name' => 'navigation',
				'value'      => array(
					__( 'Yes', 'makali' ) => true,
					__( 'No', 'makali' )  => false,
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Pagination', 'makali' ),
				'param_name' => 'pagination',
				'value'      => array(
					__( 'No', 'makali' )  => false,
					__( 'Yes', 'makali' ) => true,
				),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Item Margin (unit:pixel)', 'makali' ),
				'param_name' => 'item_margin',
				'value'      => 0,
			),
			array(
				'type'       => 'textfield',
				'heading'    =>  __( 'Slider speed number (unit: second)', 'makali' ),
				'param_name' => 'speed',
				'value'      => '500',
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => __( 'Slider loop', 'makali' ),
				'param_name' => 'loop',
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => __( 'Slider Auto', 'makali' ),
				'param_name' => 'auto',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Style', 'makali' ),
				'param_name' => 'style',
				'value'      => array(
					__( 'Style 1', 'makali' )                          => 'style1',
					__( 'Style 2 (border top and bottom)', 'makali' )  => 'style2',
					__( 'Style 3 (border top)', 'makali' )             => 'style3',
					__( 'Style 4', 'makali' )             => 'style4',
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Navigation style', 'makali' ),
				'param_name'  => 'navigation_style',
				'value'       => array(
					__( 'Navigation center horizontal', 'makali' )  => 'navigation-style1',
					__( 'Navigation top-right', 'makali' )          => 'navigation-style2',
				),
			),
		),
	) );
	//Latest posts
	vc_map( array(
		'name'        => esc_html__( 'Latest posts', 'makali' ),
		'description' => __( 'List posts', 'makali' ),
		'base'        => 'latestposts',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'makali'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of posts', 'makali' ),
				'param_name' => 'posts_per_page',
				'value'      => esc_html__( '10', 'makali' ),
			),
			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => '',
				'heading'     => esc_html__( 'Category', 'makali' ),
				'param_name'  => 'category',
				'value'       => esc_html__( '0', 'makali' ),
				'description' => esc_html__( 'ID/slug of the category. Default is 0 : show all posts', 'makali' ),
			),
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Image scale', 'makali' ),
				'param_name' => 'image',
				'value'      => array(
					'Wide'	=> 'wide',
					'Square'=> 'square',
				),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Excerpt length', 'makali' ),
				'param_name' => 'length',
				'value'      => esc_html__( '20', 'makali' ),
			),
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns', 'makali' ),
				'param_name' => 'colsnumber',
				'value'      => array(
					'1'	=> '1',
					'2'	=> '2',
					'3'	=> '3',
					'4'	=> '4',
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'makali' ),
				'param_name'  => 'style',
				'value'       => array(
					__( 'Style 1', 'makali' )  => 'style1',
					__( 'Style 2', 'makali' )  => 'style2',
					__( 'Style 3', 'makali' )  => 'style3',
					__( 'Style 4', 'makali' )  => 'style4',
					__( 'Style 5', 'makali' )  => 'style5',
					__( 'Style 6', 'makali' )  => 'style6',
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Enable slider', 'makali' ),
				'param_name'  => 'enable_slider',
				'value'       => true,
				'save_always' => true, 
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of rows', 'makali' ),
				'param_name' => 'rowsnumber',
				'group'      => __( 'Slider Options', 'makali' ),
				'value'      => array(
						'1'	=> '1',
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4',
					),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 993px - 1200px)', 'makali' ),
				'param_name' => 'items_993_1200',
				'value'      => esc_html__( '3', 'makali' ),
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 769px - 992px)', 'makali' ),
				'param_name' => 'items_769_992',
				'value'      => esc_html__( '3', 'makali' ),
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 641px - 768px)', 'makali' ),
				'param_name' => 'items_641_768',
				'value'      => esc_html__( '2', 'makali' ),
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: 481px - 640px)', 'makali' ),
				'param_name' => 'items_481_640',
				'value'      => esc_html__( '2', 'makali' ),
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => __( 'Number of columns (screen: under 480px)', 'makali' ),
				'param_name' => 'items_0_480',
				'value'      => esc_html__( '1', 'makali' ),
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Navigation', 'makali' ),
				'param_name'  => 'navigation',
				'save_always' => true,
				'group'       => __( 'Slider Options', 'makali' ),
				'value'       => array(
					__( 'Yes', 'makali' ) => true,
					__( 'No', 'makali' )  => false,
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Pagination', 'makali' ),
				'param_name'  => 'pagination',
				'save_always' => true,
				'group'       => __( 'Slider Options', 'makali' ),
				'value'       => array(
					__( 'No', 'makali' )  => false,
					__( 'Yes', 'makali' ) => true,
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Item Margin (unit:pixel)', 'makali' ),
				'param_name'  => 'item_margin',
				'value'       => 30,
				'save_always' => true,
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Slider speed number (unit: second)', 'makali' ),
				'param_name'  => 'speed',
				'value'       => '500',
				'save_always' => true,
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Slider loop', 'makali' ),
				'param_name'  => 'loop',
				'value'       => true,
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Slider Auto', 'makali' ),
				'param_name'  => 'auto',
				'value'       => true,
				'group'       => __( 'Slider Options', 'makali' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Navigation style', 'makali' ),
				'param_name'  => 'navigation_style',
				'group'       => __( 'Slider Options', 'makali' ),
				'value'       => array(
					__( 'Navigation center horizontal', 'makali' )  => 'navigation-style1',
					__( 'Navigation top-right', 'makali' )          => 'navigation-style2',
				),
			),
		),
	) );
	//Counter
	vc_map( array(
		'name'     => esc_html__( 'Counter', 'makali' ),
		'description' => __( 'Counter', 'makali' ),
		'base'     => 'makali_counter',
		'class'    => '',
		'category' => esc_html__( 'Theme', 'makali'),
		"icon"     => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'   => array(
			array(
				'type'        => 'attach_image',
				'holder'      => 'div',
				'class'       => '',
				'heading'     => esc_html__( 'Image icon', 'makali' ),
				'param_name'  => 'image',
				'value'       => '',
				'description' => esc_html__( 'Upload icon image', 'makali' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number', 'makali' ),
				'param_name' => 'number',
				'value'      => '',
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Text', 'makali' ),
				'param_name' => 'text',
				'value'      => '',
			),
		),
	) );
	//Heading title
	vc_map( array(
		'name'     => esc_html__( 'Heading Title', 'makali' ),
		'description' => __( 'Heading Title', 'makali' ),
		'base'     => 'roadthemes_title',
		'class'    => '',
		'category' => esc_html__( 'Theme', 'makali'),
		"icon"     => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'   => array(
			array(
				'type'       => 'textarea',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Heading title element', 'makali' ),
				'param_name' => 'heading_title',
				'value'      => 'Title',
			),
			array(
				'type'       => 'textarea',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Heading sub-title element', 'makali' ),
				'param_name' => 'sub_heading_title',
				'value'      => '',
			),
			array(
				'type'        => 'dropdown',
				'holder'     => 'div',
				'heading'     => esc_html__( 'Style', 'makali' ),
				'param_name'  => 'style',
				'value'       => array(
					__( 'Style 1 (Default)', 'makali' )                         => 'style1',
					__( 'Style 2 (Product coundown title layout1)', 'makali' )  => 'style2',
					__( 'Style 3 (Testimonial title layout1)', 'makali' )       => 'style3',
					__( 'Style 4 (Footer title)', 'makali' )                    => 'style4',
					__( 'Style 5 (List product title)', 'makali' )              => 'style5',
					__( 'Style 6 (Sidebar title)', 'makali' )              		=> 'style6',
					__( 'Style 7 (Layout 5 title)', 'makali' )              	=> 'style7',
					__( 'Style 8 (Layout 7+8 title - no icon)', 'makali' )      => 'style8',
					__( 'Style 8 with "White" color', 'makali' )      => 'style8-white',
					__( 'Style 9 (List product title 2)', 'makali' )              => 'style9',
					__( 'Style 10 (Layout 19 title)', 'makali' )              => 'style10',
					__( 'Style 11 (Sidebar title 2)', 'makali' )              => 'style11',
					__( 'Style 12', 'makali' )              => 'style12',
					__( 'Style 13', 'makali' )              => 'style13',
				),
			),
		),
	) );
	//Countdown
	vc_map( array(
		'name'     => esc_html__( 'Countdown', 'makali' ),
		'description' => __( 'Countdown', 'makali' ),
		'base'     => 'roadthemes_countdown',
		'class'    => '',
		'category' => esc_html__( 'Theme', 'makali'),
		"icon"     => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'   => array(
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'End date (day)', 'makali' ),
				'param_name' => 'countdown_day',
				'value'      => '1',
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'End date (month)', 'makali' ),
				'param_name' => 'countdown_month',
				'value'      => '1',
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'End date (year)', 'makali' ),
				'param_name' => 'countdown_year',
				'value'      => '2020',
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'makali' ),
				'param_name'  => 'style',
				'value'       => array(
					__( 'Style 1', 'makali' )      => 'style1',
					__( 'Style 2', 'makali' )      => 'style2',
				),
			),
		),
	) );
}
?>