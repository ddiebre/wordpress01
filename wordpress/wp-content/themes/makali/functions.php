<?php
/**
 * Makali functions and definitions
 */
/**
* Require files
*/
	//TGM-Plugin-Activation
require_once( get_template_directory().'/class-tgm-plugin-activation.php' );
	//Init the Redux Framework
if ( class_exists( 'ReduxFramework' ) && !isset( $redux_demo ) && file_exists( get_template_directory().'/theme-config.php' ) ) {
	require_once( get_template_directory().'/theme-config.php' );
}
	// Theme files
if ( !class_exists( 'roadthemes_widgets' ) && file_exists( get_template_directory().'/include/roadthemeswidgets.php' ) ) {
	require_once( get_template_directory().'/include/roadthemeswidgets.php' );
}
if ( file_exists( get_template_directory().'/include/wooajax.php' ) ) {
	require_once( get_template_directory().'/include/wooajax.php' );
}
if ( file_exists( get_template_directory().'/include/map_shortcodes.php' ) ) {
	require_once( get_template_directory().'/include/map_shortcodes.php' );
}
if ( file_exists( get_template_directory().'/include/shortcodes.php' ) ) {
	require_once( get_template_directory().'/include/shortcodes.php' );
}
define('PLUGIN_REQUIRED_PATH','http://roadthemes.com/plugins');
Class Makali_Class {
	/**
	* Global values
	*/
	static function makali_post_odd_event(){
		global $wp_session;
		if(!isset($wp_session["makali_postcount"])){
			$wp_session["makali_postcount"] = 0;
		}
		$wp_session["makali_postcount"] = 1 - $wp_session["makali_postcount"];
		return $wp_session["makali_postcount"];
	}
	static function makali_post_thumbnail_size($size){
		global $wp_session;
		if($size!=''){
			$wp_session["makali_postthumb"] = $size;
		}
		return $wp_session["makali_postthumb"];
	}
	static function makali_shop_class($class){
		global $wp_session;
		if($class!=''){
			$wp_session["makali_shopclass"] = $class;
		}
		return $wp_session["makali_shopclass"];
	}
	static function makali_show_view_mode(){
		$makali_opt = get_option( 'makali_opt' );
		$makali_viewmode = 'grid-view'; //default value
		if(isset($makali_opt['default_view']) && $makali_opt['default_view']!= "") {
			$makali_viewmode = $makali_opt['default_view'];
		}
		if(isset($_GET['view']) && $_GET['view']!=''){
			$makali_viewmode = $_GET['view'];
		}
		return $makali_viewmode;
	}
	static function makali_shortcode_products_count(){
		global $wp_session;
		$makali_productsfound = 0;
		if(isset($wp_session["makali_productsfound"])){
			$makali_productsfound = $wp_session["makali_productsfound"];
		}
		return $makali_productsfound;
	}
	/**
	* Constructor
	*/
	function __construct() {
		// Register action/filter callbacks
			//WooCommerce - action/filter
		add_theme_support( 'woocommerce' );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
		add_filter( 'get_product_search_form', array($this, 'makali_woo_search_form'));
		add_filter( 'woocommerce_shortcode_products_query', array($this, 'makali_woocommerce_shortcode_count'));
		add_action( 'woocommerce_share', array($this, 'makali_woocommerce_social_share'), 35 );
		add_action( 'woocommerce_archive_description', array($this, 'makali_woocommerce_category_image'), 2 );
		add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
		    return array(
		        'width'  => 150,
		        'height' => 182,
		        'crop'   => 0,
		    );
		} );
			//move message to top
		remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_show_message', 'wc_print_notices', 10 );
			//remove add to cart button after item
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
			// remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			//Single product organize
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			//remove cart total under cross sell
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
		add_action( 'cart_totals', 'woocommerce_cart_totals', 5 );
			//Theme actions
		add_action( 'after_setup_theme', array($this, 'makali_setup'));
		add_action( 'tgmpa_register', array($this, 'makali_register_required_plugins')); 
		add_action( 'widgets_init', array($this, 'makali_override_woocommerce_widgets'), 15 );
		add_action( 'wp_enqueue_scripts', array($this, 'makali_scripts_styles') );
		add_action( 'wp_head', array($this, 'makali_custom_code_header'));
		add_action( 'widgets_init', array($this, 'makali_widgets_init'));
		add_action( 'save_post', array($this, 'makali_save_meta_box_data'));
		add_action('comment_form_before_fields', array($this, 'makali_before_comment_fields'));
		add_action('comment_form_after_fields', array($this, 'makali_after_comment_fields'));
		add_action( 'customize_register', array($this, 'makali_customize_register'));
		add_action( 'customize_preview_init', array($this, 'makali_customize_preview_js'));
		add_action('admin_enqueue_scripts', array($this, 'makali_admin_style'));
			//Theme filters
		add_filter( 'loop_shop_per_page', array($this, 'makali_woo_change_per_page'), 20 );
		add_filter( 'woocommerce_output_related_products_args', array($this, 'makali_woo_related_products_limit'));
		add_filter( 'get_search_form', array($this, 'makali_search_form'));
		add_filter('excerpt_more', array($this, 'makali_new_excerpt_more'));
		add_filter( 'excerpt_length', array($this, 'makali_change_excerpt_length'), 999 );
		add_filter('wp_nav_menu_objects', array($this, 'makali_first_and_last_menu_class'));
		add_filter( 'wp_page_menu_args', array($this, 'makali_page_menu_args'));
		add_filter('dynamic_sidebar_params', array($this, 'makali_widget_first_last_class'));
		add_filter('dynamic_sidebar_params', array($this, 'makali_mega_menu_widget_change'));
		add_filter( 'dynamic_sidebar_params', array($this, 'makali_put_widget_content'));
		//Adding theme support
		if ( ! isset( $content_width ) ) {
			$content_width = 625;
		}
	}
	/**
	* Filter callbacks
	* ----------------
	*/
	// Change products per page
	function makali_woo_change_per_page() {
		$makali_opt = get_option( 'makali_opt' );
		return $makali_opt['product_per_page'];
	}
	//Change number of related products on product page. Set your own value for 'posts_per_page'
	function makali_woo_related_products_limit( $args ) {
		global $product;
		$makali_opt = get_option( 'makali_opt' );
		$args['posts_per_page'] = $makali_opt['related_amount'];
		return $args;
	}
	// Count number of products from shortcode
	function makali_woocommerce_shortcode_count( $args ) {
		$makali_productsfound = new WP_Query($args);
		$makali_productsfound = $makali_productsfound->post_count;
		global $wp_session;
		$wp_session["makali_productsfound"] = $makali_productsfound;
		return $args;
	}
	//Change search form
	function makali_search_form( $form ) {
		if(get_search_query()!=''){
			$search_str = get_search_query();
		} else {
			$search_str = esc_html__( 'Search... ', 'makali' );
		}
		$form = '<form role="search" method="get" class="searchform blogsearchform" action="' . esc_url(home_url( '/' ) ). '" >
		<div class="form-input">
			<input type="text" placeholder="'.esc_attr($search_str).'" name="s" class="input_text ws">
			<button class="button-search searchsubmit blogsearchsubmit" type="submit"><i class="fa fa-search"></i></button>
			<input type="hidden" name="post_type" value="post" />
			</div>
		</form>';
		return $form;
	}
	//Change woocommerce search form
	function makali_woo_search_form( $form ) {
		global $wpdb;
		if(get_search_query()!=''){
			$search_str = get_search_query();
		} else {
			$search_str = esc_html__( 'Search product...', 'makali' );
		}
		$form = '<form role="search" method="get" class="searchform productsearchform" action="'.esc_url( home_url( '/'  ) ).'">';
			$form .= '<div class="form-input">';
				$form .= '<input type="text" placeholder="'.esc_attr($search_str).'" name="s" class="ws"/>';
				$form .= '<button class="button-search searchsubmit productsearchsubmit" type="submit">' . esc_html__('Search', 'makali') . '</button>';
				$form .= '<input type="hidden" name="post_type" value="product" />';
			$form .= '</div>';
		$form .= '</form>';
		return $form;
	}
	// Replaces the excerpt "more" text by a link
	function makali_new_excerpt_more($more) {
		return '';
	}
	//Change excerpt length
	function makali_change_excerpt_length( $length ) {
		$makali_opt = get_option( 'makali_opt' );
		if(isset($makali_opt['excerpt_length'])){
			return $makali_opt['excerpt_length'];
		}
		return 50;
	}
	//Add 'first, last' class to menu
	function makali_first_and_last_menu_class($items) {
		$items[1]->classes[] = 'first';
		$items[count($items)]->classes[] = 'last';
		return $items;
	}
	/**
	 * Filter the page menu arguments.
	 *
	 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
	 *
	 * @since Makali 1.0
	 */
	function makali_page_menu_args( $args ) {
		if ( ! isset( $args['show_home'] ) )
			$args['show_home'] = true;
		return $args;
	}
	//Add first, last class to widgets
	function makali_widget_first_last_class($params) {
		global $my_widget_num;
		$class = '';
		$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
		$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets	
		if(!$my_widget_num) {// If the counter array doesn't exist, create it
			$my_widget_num = array();
		}
		if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
			return $params; // No widgets in this sidebar... bail early.
		}
		if(isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
			$my_widget_num[$this_id] ++;
		} else { // If not, create it starting with 1
			$my_widget_num[$this_id] = 1;
		}
		if($my_widget_num[$this_id] == 1) { // If this is the first widget
			$class .= ' widget-first ';
		} elseif($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
			$class .= ' widget-last ';
		}
		$params[0]['before_widget'] = str_replace('first_last', ' '.$class.' ', $params[0]['before_widget']);
		return $params;
	}
	//Change mega menu widget from div to li tag
	function makali_mega_menu_widget_change($params) {
		$sidebar_id = $params[0]['id'];
		$pos = strpos($sidebar_id, '_menu_widgets_area_');
		if ( !$pos == false ) {
			$params[0]['before_widget'] = '<li class="widget_mega_menu">'.$params[0]['before_widget'];
			$params[0]['after_widget'] = $params[0]['after_widget'].'</li>';
		}
		return $params;
	}
	// Push sidebar widget content into a div
	function makali_put_widget_content( $params ) {
		global $wp_registered_widgets;
		if( $params[0]['id']=='sidebar-category' ){
			$settings_getter = $wp_registered_widgets[ $params[0]['widget_id'] ]['callback'][0];
			$settings = $settings_getter->get_settings();
			$settings = $settings[ $params[1]['number'] ];
			if($params[0]['widget_name']=="Text" && isset($settings['title']) && $settings['text']=="") { // if text widget and no content => don't push content
				return $params;
			}
			if( isset($settings['title']) && $settings['title']!='' ){
				$params[0][ 'after_title' ] .= '<div class="widget_content">';
				$params[0][ 'after_widget' ] = '</div>'.$params[0][ 'after_widget' ];
			} else {
				$params[0][ 'before_widget' ] .= '<div class="widget_content">';
				$params[0][ 'after_widget' ] = '</div>'.$params[0][ 'after_widget' ];
			}
		}
		return $params;
	}
	/**
	* Action hooks
	* ----------------
	*/
	/**
	 * Makali setup.
	 *
	 * Sets up theme defaults and registers the various WordPress features that
	 * Makali supports.
	 *
	 * @uses load_theme_textdomain() For translation/localization support.
	 * @uses add_editor_style() To add a Visual Editor stylesheet.
	 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
	 * 	custom background, and post formats.
	 * @uses register_nav_menu() To add support for navigation menus.
	 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
	 *
	 * @since Makali 1.0
	 */
	function makali_setup() {
		/*
		 * Makes Makali available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on Makali, use a find and replace
		 * to change 'makali' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'makali', get_template_directory() . '/languages' );
		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();
		// Adds RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );
		// This theme supports a variety of post formats.
		add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio' ) );
		// Register menus
		register_nav_menu( 'primary', esc_html__( 'Primary Menu', 'makali' ) );
		register_nav_menu( 'primary_nd', esc_html__( 'Primary Menu 2', 'makali' ) );
		register_nav_menu( 'mobilemenu', esc_html__( 'Mobile Menu', 'makali' ) );
		register_nav_menu( 'categories', esc_html__( 'Categories Menu', 'makali' ) );
		/*
		 * This theme supports custom background color and image,
		 * and here we also set up the default background color.
		 */
		add_theme_support( 'custom-background', array(
			'default-color' => 'e6e6e6',
		) );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		// This theme uses a custom image size for featured images, displayed on "standard" posts.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1170, 9999 ); // Unlimited height, soft crop
		add_image_size( 'makali-category-thumb', 500, 300, true ); // (cropped) (post carousel)
		add_image_size( 'makali-post-thumb', 700, 570, true ); // (cropped) (blog sidebar)
		add_image_size( 'makali-post-thumbwide', 1170, 700, true ); // (cropped) (blog large img)
	}
	//Override woocommerce widgets
	function makali_override_woocommerce_widgets() {
		//Show mini cart on all pages
		if ( class_exists( 'WC_Widget_Cart' ) ) {
			unregister_widget( 'WC_Widget_Cart' ); 
			include_once( get_template_directory().'/woocommerce/class-wc-widget-cart.php' );
			register_widget( 'Custom_WC_Widget_Cart' );
		}
	}
	// Add image to category description
	function makali_woocommerce_category_image() {
		if ( is_product_category() ){
			global $wp_query;
			$cat = $wp_query->get_queried_object();
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$image = wp_get_attachment_url( $thumbnail_id );
			if ( $image ) {
				echo '<p class="category-image-desc"><img src="' . esc_url($image) . '" alt=" ' . esc_attr( $cat->name ) . ' " /></p>';
			}
		}
	}
	//Display social sharing on product page
	function makali_woocommerce_social_share(){
		$makali_opt = get_option( 'makali_opt' );
	?>
		<?php if (isset($makali_opt['share_code']) && $makali_opt['share_code']!='') { ?>
			<div class="share_buttons">
				<?php 
					echo wp_kses($makali_opt['share_code'], array(
						'div' => array(
							'class' => array()
						),
						'span' => array(
							'class' => array(),
							'displayText' => array()
						),
					));
				?>
			</div>
		<?php } ?>
	<?php
	}
	/**
	 * Enqueue scripts and styles for front-end.
	 *
	 * @since Makali 1.0
	 */
	function makali_scripts_styles() {
		global $wp_styles, $wp_scripts;
		$makali_opt = get_option( 'makali_opt' );
		/*
		 * Adds JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		*/
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
		// Add Bootstrap JavaScript
		wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '4.1.1', true );
		// Add Owl files
		wp_enqueue_script( 'owl-js', get_template_directory_uri() . '/js/owl.carousel.js', array('jquery'), '2.3.4', true );
		wp_enqueue_style( 'owl-css', get_template_directory_uri() . '/css/owl.carousel.min.css', array(), '2.3.4' );
		// Add Chosen js files
		wp_enqueue_script( 'chosen-js', get_template_directory_uri() . '/js/chosen/chosen.jquery.min.js', array('jquery'), '1.3.0', true );
		wp_enqueue_script( 'chosenproto-js', get_template_directory_uri() . '/js/chosen/chosen.proto.min.js', array('jquery'), '1.3.0', true );
		wp_enqueue_style( 'chosen-css', get_template_directory_uri() . '/js/chosen/chosen.min.css', array(), '1.3.0' );
		// Add parallax script files
		// Add Fancybox
		wp_enqueue_script( 'fancybox-js', get_template_directory_uri() . '/js/fancybox/jquery.fancybox.pack.js', array('jquery'), '2.1.5', true );
		wp_enqueue_script( 'fancybox-buttons-js', get_template_directory_uri().'/js/fancybox/helpers/jquery.fancybox-buttons.js', array('jquery'), '1.0.5', true );
		wp_enqueue_script( 'fancybox-media-js', get_template_directory_uri() . '/js/fancybox/helpers/jquery.fancybox-media.js', array('jquery'), '1.0.6', true );
		wp_enqueue_script( 'fancybox-thumbs-js', get_template_directory_uri() . '/js/fancybox/helpers/jquery.fancybox-thumbs.js', array('jquery'), '1.0.7', true );
		wp_enqueue_style( 'fancybox-css', get_template_directory_uri() . '/js/fancybox/jquery.fancybox.css', array(), '2.1.5' );
		wp_enqueue_style( 'fancybox-buttons-css', get_template_directory_uri() . '/js/fancybox/helpers/jquery.fancybox-buttons.css', array(), '1.0.5' );
		wp_enqueue_style( 'fancybox-thumbs-css', get_template_directory_uri() . '/js/fancybox/helpers/jquery.fancybox-thumbs.css', array(), '1.0.7' );
		//Superfish
		wp_enqueue_script( 'superfish-js', get_template_directory_uri() . '/js/superfish/superfish.min.js', array('jquery'), '1.3.15', true );
		//Add Shuffle js
		wp_enqueue_script( 'modernizr-js', get_template_directory_uri() . '/js/modernizr.custom.min.js', array('jquery'), '2.6.2', true );
		wp_enqueue_script( 'shuffle-js', get_template_directory_uri() . '/js/jquery.shuffle.min.js', array('jquery'), '3.0.0', true );
		//Add mousewheel
		wp_enqueue_script( 'mousewheel-js', get_template_directory_uri() . '/js/jquery.mousewheel.min.js', array('jquery'), '3.1.12', true );
		// Add jQuery countdown file
		wp_enqueue_script( 'countdown-js', get_template_directory_uri() . '/js/jquery.countdown.min.js', array('jquery'), '2.0.4', true );
		// Add jQuery counter files
		wp_enqueue_script( 'waypoints-js', get_template_directory_uri() . '/js/waypoints.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'counterup-js', get_template_directory_uri() . '/js/jquery.counterup.min.js', array('jquery'), '1.0', true );
		// Add variables.js file
		wp_enqueue_script( 'variables-js', get_template_directory_uri() . '/js/variables.js', array('jquery'), '20140826', true );
		// Add theme-makali.js file
		wp_enqueue_script( 'makali-js', get_template_directory_uri() . '/js/theme-makali.js', array('jquery'), '20140826', true );
		$font_url = $this->makali_get_font_url();
		if ( ! empty( $font_url ) )
			wp_enqueue_style( 'makali-fonts', esc_url_raw( $font_url ), array(), null );
		// Loads our main stylesheet.
		wp_enqueue_style( 'makali-style', get_stylesheet_uri() );
		// Mega Main Menu
		wp_enqueue_style( 'megamenu-css', get_template_directory_uri() . '/css/megamenu_style.css', array(), '2.0.4' );
		// Load fontawesome css
		wp_enqueue_style( 'fontawesome-css', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.7.0' );
		// Load Pe-icon-7-stroke css
		wp_enqueue_style( 'pe-icon-7-stroke-css', get_template_directory_uri() . '/css/pe-icon-7-stroke.css', array(), '1.2.0' );
		// Load Ionicons css
		wp_enqueue_style( 'ionicons-css', get_template_directory_uri() . '/css/ionicons.min.css', array(), '2.0.0' );
		// Load bootstrap css
		wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '4.1.1' );
		// Compile Less to CSS
		$previewpreset = (isset($_REQUEST['preset']) ? $_REQUEST['preset'] : null);
			//get preset from url (only for demo/preview)
		if($previewpreset){
			$_SESSION["preset"] = $previewpreset;
		}
		$presetopt = 1;
		if(!isset($_SESSION["preset"])){
			$_SESSION["preset"] = 1;
		}
		if($_SESSION["preset"] != 1) {
			$presetopt = $_SESSION["preset"];
		} else { /* if no preset varialbe found in url, use from theme options */
			if(isset($makali_opt['preset_option'])){
				$presetopt = $makali_opt['preset_option'];
			}
		}
		if(!isset($presetopt)) $presetopt = 1; /* in case first time install theme, no options found */
		if(isset($makali_opt['enable_less'])){
			if($makali_opt['enable_less']){
				$themevariables = array(
					'body_font'=> $makali_opt['bodyfont']['font-family'],
					'text_color'=> $makali_opt['bodyfont']['color'],
					'text_selected_bg' => $makali_opt['text_selected_bg'],
					'text_selected_color' => $makali_opt['text_selected_color'],
					'text_size'=> $makali_opt['bodyfont']['font-size'],
					'border_color'=> $makali_opt['border_color']['border-color'],
					'page_content_background' => $makali_opt['page_content_background']['background-color'],
					'row_space' => $makali_opt['row_space'],
					'row_container' => $makali_opt['row_container'],
					'heading_font'=> $makali_opt['headingfont']['font-family'],
					'heading_color'=> $makali_opt['headingfont']['color'],
					'heading_font_weight'=> $makali_opt['headingfont']['font-weight'],
					'dropdown_font'=> $makali_opt['dropdownfont']['font-family'],
					'dropdown_color'=> $makali_opt['dropdownfont']['color'],
					'dropdown_font_size'=> $makali_opt['dropdownfont']['font-size'],
					'dropdown_font_weight'=> $makali_opt['dropdownfont']['font-weight'],
					'dropdown_bg' => $makali_opt['dropdown_bg'],
					'menu_font'=> $makali_opt['menufont']['font-family'],
					'menu_color'=> $makali_opt['menufont']['color'],
					'menu_hover_itemlevel1_color' => $makali_opt['menu_hover_itemlevel1_color'],
					'menu_font_size'=> $makali_opt['menufont']['font-size'],
					'menu_font_weight'=> $makali_opt['menufont']['font-weight'],
					'sub_menu_font'=> $makali_opt['submenufont']['font-family'],
					'sub_menu_color'=> $makali_opt['submenufont']['color'],
					'sub_menu_font_size'=> $makali_opt['submenufont']['font-size'],
					'sub_menu_font_weight'=> $makali_opt['submenufont']['font-weight'],
					'sub_menu_bg' => $makali_opt['sub_menu_bg'],
					'categories_font'=> $makali_opt['categoriesfont']['font-family'],
					'categories_font_size'=> $makali_opt['categoriesfont']['font-size'],
					'categories_font_weight'=> $makali_opt['categoriesfont']['font-weight'],
					'categories_color'=> $makali_opt['categoriesfont']['color'],
					'categories_menu_bg' => $makali_opt['categories_menu_bg'],
					'categories_sub_menu_font'=> $makali_opt['categoriessubmenufont']['font-family'],
					'categories_sub_menu_font_size'=> $makali_opt['categoriessubmenufont']['font-size'],
					'categories_sub_menu_font_weight'=> $makali_opt['categoriessubmenufont']['font-weight'],
					'categories_sub_menu_color'=> $makali_opt['categoriessubmenufont']['color'],
					'categories_sub_menu_bg' => $makali_opt['categories_sub_menu_bg'],
					'link_color' => $makali_opt['link_color']['regular'],
					'link_hover_color' => $makali_opt['link_color']['hover'],
					'link_active_color' => $makali_opt['link_color']['active'],
					'primary_color' => $makali_opt['primary_color'],
					'sale_color' => $makali_opt['sale_color'],
					'saletext_color' => $makali_opt['saletext_color'],
					'rate_color' => $makali_opt['rate_color'],
					'price_font'=> $makali_opt['pricefont']['font-family'],
					'price_color'=> $makali_opt['pricefont']['color'],
					'price_font_size'=> $makali_opt['pricefont']['font-size'],
					'price_font_weight'=> $makali_opt['pricefont']['font-weight'],
					'topbar_color' => $makali_opt['topbar_color'],
					'topbar_link_color' => $makali_opt['topbar_link_color']['regular'],
					'topbar_link_hover_color' => $makali_opt['topbar_link_color']['hover'],
					'topbar_link_active_color' => $makali_opt['topbar_link_color']['active'],
					'header_color' => $makali_opt['header_color'],
					'header_link_color' => $makali_opt['header_link_color']['regular'],
					'header_link_hover_color' => $makali_opt['header_link_color']['hover'],
					'header_link_active_color' => $makali_opt['header_link_color']['active'],
					'bg_btn_mc4wp' => $makali_opt['bg_btn_mc4wp'],
					'bg_h_btn_mc4wp' => $makali_opt['bg_h_btn_mc4wp'],
					'color_btn_mc4wp' => $makali_opt['color_btn_mc4wp'],
					'color_h_btn_mc4wp' => $makali_opt['color_h_btn_mc4wp'],
					'footer_color' => $makali_opt['footer_color'],
					'footer_title_color' => $makali_opt['footer_title_color'],
					'footer_link_color' => $makali_opt['footer_link_color']['regular'],
					'footer_link_hover_color' => $makali_opt['footer_link_color']['hover'],
					'footer_link_active_color' => $makali_opt['footer_link_color']['active'],
					'notification_color' => isset($makali_opt['notification_color']) ? $makali_opt['notification_color'] : "#ffffff",
				);
				if(isset($makali_opt['header_sticky_bg']['rgba']) && $makali_opt['header_sticky_bg']['rgba']!="") {
					$themevariables['header_sticky_bg'] = $makali_opt['header_sticky_bg']['rgba'];
				} else {
					$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
				}
				if(isset($makali_opt['header_bg']['background-color']) && $makali_opt['header_bg']['background-color']!="") {
					$themevariables['header_bg'] = $makali_opt['header_bg']['background-color'];
				} else {
					$themevariables['header_bg'] = '#ffffff';
				}
				if(isset($makali_opt['topbar_bg']['background-color']) && $makali_opt['topbar_bg']['background-color']!="") {
					$themevariables['topbar_bg'] = $makali_opt['topbar_bg']['background-color'];
				} else {
					$themevariables['topbar_bg'] = '#ffffff';
				}
				if(isset($makali_opt['footer_bg']['background-color']) && $makali_opt['footer_bg']['background-color']!="") {
					$themevariables['footer_bg'] = $makali_opt['footer_bg']['background-color'];
				} else {
					$themevariables['footer_bg'] = '#085293';
				}
				if(isset($makali_opt['notification_bg']) && $makali_opt['notification_bg']!="") {
					$themevariables['notification_bg'] = $makali_opt['notification_bg'];
				} else {
					$themevariables['notification_bg'] = '#323232';
				}
				switch ($presetopt) {
					// 2..6 for Cosmetic
					case 2:
						break;
					case 3:
						break;
					case 4:
						break;
					case 5:
						break;
					case 6:
						break;
					// for Funiture 01, 02, 03
					case $presetopt == 7 || $presetopt == 9 || $presetopt == 10:
						$themevariables['primary_color'] = '#f66362';
						$themevariables['menu_hover_itemlevel1_color'] = '#f66362';
						$themevariables['link_hover_color'] = '#f66362';
						$themevariables['link_active_color'] = '#f66362';
						$themevariables['sale_color'] = '#f66362';
						$themevariables['rate_color'] = '#f66362';

						$themevariables['header_link_hover_color'] = '#f66362';
						$themevariables['header_link_active_color'] = '#f66362';
						$themevariables['topbar_link_hover_color'] = '#f66362';
						$themevariables['topbar_link_active_color'] = '#f66362';

						$themevariables['footer_link_hover_color'] = '#f66362';
						$themevariables['footer_link_active_color'] = '#f66362';
						break;
						// for Funiture 04
					case 29:
						$themevariables['primary_color'] = '#f66362';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['menu_hover_itemlevel1_color'] = '#f66362';
						$themevariables['link_hover_color'] = '#f66362';
						$themevariables['link_active_color'] = '#f66362';
						$themevariables['sale_color'] = '#f66362';
						$themevariables['rate_color'] = '#f66362';

						$themevariables['header_link_hover_color'] = '#f66362';
						$themevariables['header_link_active_color'] = '#f66362';
						$themevariables['topbar_link_hover_color'] = '#f66362';
						$themevariables['topbar_link_active_color'] = '#f66362';

						$themevariables['footer_link_hover_color'] = '#f66362';
						$themevariables['footer_link_active_color'] = '#f66362';
						break;
					// for Jewery 01, 03, 04
					case $presetopt == 8 || $presetopt == 12 || $presetopt == 13:
						$themevariables['primary_color'] = '#c09578';
						$themevariables['menu_hover_itemlevel1_color'] = '#c09578';
						$themevariables['link_hover_color'] = '#c09578';
						$themevariables['link_active_color'] = '#c09578';
						$themevariables['sale_color'] = '#c09578';
						$themevariables['rate_color'] = '#c09578';

						$themevariables['header_link_hover_color'] = '#c09578';
						$themevariables['header_link_active_color'] = '#c09578';
						$themevariables['topbar_link_hover_color'] = '#c09578';
						$themevariables['topbar_link_active_color'] = '#c09578';
						
						$themevariables['footer_link_hover_color'] = '#c09578';
						$themevariables['footer_link_active_color'] = '#c09578';
						break;
					// for only Jewery 02
					case 11:
						$themevariables['primary_color'] = '#c09578';
						$themevariables['menu_hover_itemlevel1_color'] = '#c09578';
						$themevariables['link_hover_color'] = '#c09578';
						$themevariables['link_active_color'] = '#c09578';
						$themevariables['sale_color'] = '#c09578';
						$themevariables['rate_color'] = '#c09578';
						$themevariables['topbar_bg'] = '#f5f5f5';

						$themevariables['header_link_hover_color'] = '#c09578';
						$themevariables['header_link_active_color'] = '#c09578';
						$themevariables['topbar_link_hover_color'] = '#c09578';
						$themevariables['topbar_link_active_color'] = '#c09578';
						
						$themevariables['footer_link_hover_color'] = '#c09578';
						$themevariables['footer_link_active_color'] = '#c09578';
						break;
					// for only Oganic 01
					case 14:
						$themevariables['primary_color'] = '#389c3c';
						$themevariables['link_hover_color'] = '#389c3c';
						$themevariables['link_active_color'] = '#389c3c';
						$themevariables['sale_color'] = '#389c3c';
						$themevariables['rate_color'] = '#389c3c';

						$themevariables['header_link_hover_color'] = '#389c3c';
						$themevariables['header_link_active_color'] = '#389c3c';
						$themevariables['topbar_link_hover_color'] = '#389c3c';
						$themevariables['topbar_link_active_color'] = '#389c3c';
						
						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#323232';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#389c3c';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#323232';
						$themevariables['footer_link_active_color'] = '#323232';
						
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#323232';
						$themevariables['header_sticky_bg'] = 'rgba(56, 156, 60, .8)';
						$themevariables['footer_bg'] = '#389c3c';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						break;
					// for Oganic 02
					case 15:
						$themevariables['primary_color'] = '#389c3c';
						$themevariables['link_hover_color'] = '#389c3c';
						$themevariables['link_active_color'] = '#389c3c';
						$themevariables['sale_color'] = '#389c3c';
						$themevariables['rate_color'] = '#389c3c';

						$themevariables['header_link_hover_color'] = '#389c3c';
						$themevariables['header_link_active_color'] = '#389c3c';
						$themevariables['topbar_link_hover_color'] = '#389c3c';
						$themevariables['topbar_link_active_color'] = '#389c3c';
						
						$themevariables['footer_link_hover_color'] = '#389c3c';
						$themevariables['footer_link_active_color'] = '#389c3c';
						
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#389c3c';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, .8)';
						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						break;
					// for Oganic 03
					case 16:
						$themevariables['primary_color'] = '#3b9943';
						$themevariables['link_hover_color'] = '#3b9943';
						$themevariables['link_active_color'] = '#3b9943';
						$themevariables['sale_color'] = '#3b9943';
						$themevariables['rate_color'] = '#3b9943';

						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#323232';
						$themevariables['topbar_link_active_color'] = '#323232';
						$themevariables['topbar_bg'] = '#3b9943';
						
						$themevariables['footer_link_hover_color'] = '#3b9943';
						$themevariables['footer_link_active_color'] = '#3b9943';
						
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#3b9943';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, .8)';
						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						break;
					// for Plant 01, 02
					case $presetopt == 17 || $presetopt == 18:
						$themevariables['primary_color'] = '#3b9943';
						$themevariables['menu_hover_itemlevel1_color'] = '#3b9943';
						$themevariables['link_hover_color'] = '#3b9943';
						$themevariables['link_active_color'] = '#3b9943';
						$themevariables['sale_color'] = '#3b9943';
						$themevariables['rate_color'] = '#3b9943';

						$themevariables['header_link_hover_color'] = '#3b9943';
						$themevariables['header_link_active_color'] = '#3b9943';
						$themevariables['topbar_link_hover_color'] = '#3b9943';
						$themevariables['topbar_link_active_color'] = '#3b9943';

						$themevariables['footer_link_hover_color'] = '#3b9943';
						$themevariables['footer_link_active_color'] = '#3b9943';
						break;
					// for Autopart 01
					case 19:
						$themevariables['primary_color'] = '#fab115';
						$themevariables['menu_hover_itemlevel1_color'] = '#fab115';
						$themevariables['link_hover_color'] = '#fab115';
						$themevariables['link_active_color'] = '#fab115';
						$themevariables['sale_color'] = '#fab115';
						$themevariables['saletext_color'] = '#323232';
						$themevariables['rate_color'] = '#fab115';
						$themevariables['topbar_bg'] = '#f5f5f5';

						$themevariables['header_link_hover_color'] = '#fab115';
						$themevariables['header_link_active_color'] = '#fab115';
						$themevariables['topbar_link_hover_color'] = '#fab115';
						$themevariables['topbar_link_active_color'] = '#fab115';

						$themevariables['footer_link_hover_color'] = '#fab115';
						$themevariables['footer_link_active_color'] = '#fab115';
						break;
					// for Autopart 02
					case 20:
						$themevariables['primary_color'] = '#fab115';
						$themevariables['menu_hover_itemlevel1_color'] = '#ffffff';
						$themevariables['link_hover_color'] = '#fab115';
						$themevariables['link_active_color'] = '#fab115';
						$themevariables['sale_color'] = '#fab115';
						$themevariables['saletext_color'] = '#323232';
						$themevariables['rate_color'] = '#fab115';
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['header_sticky_bg'] = 'rgba(250, 177, 21, 0.95)';

						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#fab115';
						$themevariables['header_link_active_color'] = '#fab115';
						$themevariables['topbar_link_hover_color'] = '#fab115';
						$themevariables['topbar_link_active_color'] = '#fab115';

						$themevariables['bg_btn_mc4wp'] = '#fab115';
						$themevariables['bg_h_btn_mc4wp'] = '#fab115';
						$themevariables['color_btn_mc4wp'] = '#323232';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#fab115';
						$themevariables['footer_bg'] = '#323232';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_active_color'] = '#fab115';
						break;
					// for Digital 01
					case 21:
						$themevariables['primary_color'] = '#0583cc';
						$themevariables['menu_hover_itemlevel1_color'] = '#0583cc';
						$themevariables['link_hover_color'] = '#0583cc';
						$themevariables['link_active_color'] = '#0583cc';
						$themevariables['sale_color'] = '#e70b0b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#0583cc';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_bg'] = '#0583cc';
						$themevariables['topbar_link_color'] = '#ffffff';
						
						$themevariables['header_link_hover_color'] = '#323232';
						$themevariables['header_link_active_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#323232';
						$themevariables['topbar_link_active_color'] = '#323232';

						$themevariables['footer_link_hover_color'] = '#0583cc';
						$themevariables['footer_link_active_color'] = '#0583cc';
						$themevariables['row_space'] = '70px';
						$makali_opt['categories_menu_items'] = '8';
						$makali_opt['categories_menu_home'] = '1';
						break;
					// for Digital 02
					case 22:
						$themevariables['primary_color'] = '#0583cc';
						$themevariables['link_hover_color'] = '#0583cc';
						$themevariables['link_active_color'] = '#0583cc';
						$themevariables['sale_color'] = '#e70b0b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#0583cc';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_bg'] = '#0583cc';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['header_sticky_bg'] = 'rgba(0, 117, 184, 0.8)';
						
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#323232';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#323232';
						$themevariables['header_link_active_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#323232';
						$themevariables['topbar_link_active_color'] = '#323232';

						$themevariables['footer_link_hover_color'] = '#0583cc';
						$themevariables['footer_link_active_color'] = '#0583cc';
						$themevariables['row_space'] = '70px';
						break;
					// for Digital 03
					case 23:
						$themevariables['primary_color'] = '#0583cc';
						$themevariables['menu_hover_itemlevel1_color'] = '#0583cc';
						$themevariables['link_hover_color'] = '#0583cc';
						$themevariables['link_active_color'] = '#0583cc';
						$themevariables['sale_color'] = '#e70b0b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#0583cc';
						$themevariables['topbar_color'] = '#767676';
						$themevariables['topbar_bg'] = '#f5f5f5';
						$themevariables['topbar_link_color'] = '#767676';
						
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#323232';
						$themevariables['header_link_active_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#323232';
						$themevariables['topbar_link_active_color'] = '#323232';

						$themevariables['footer_link_hover_color'] = '#0583cc';
						$themevariables['footer_link_active_color'] = '#0583cc';
						$themevariables['row_space'] = '70px';
						break;
					// for Digital 04
					case 24:
						$themevariables['primary_color'] = '#0583cc';
						$themevariables['menu_hover_itemlevel1_color'] = '#0583cc';
						$themevariables['link_hover_color'] = '#0583cc';
						$themevariables['link_active_color'] = '#0583cc';
						$themevariables['sale_color'] = '#e70b0b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#0583cc';
						$themevariables['topbar_color'] = '#767676';
						$themevariables['topbar_bg'] = '#0583cc';
						$themevariables['topbar_link_color'] = '#767676';
						
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#0583cc';
						$themevariables['header_link_active_color'] = '#0583cc';
						$themevariables['topbar_link_hover_color'] = '#0583cc';
						$themevariables['topbar_link_active_color'] = '#0583cc';

						$themevariables['footer_link_hover_color'] = '#0583cc';
						$themevariables['footer_link_active_color'] = '#0583cc';
						$themevariables['row_space'] = '70px';
						$makali_opt['categories_menu_items'] = '8';
						$makali_opt['categories_menu_home'] = '1';
						break;
					// for Food 01
					case 25:
						$themevariables['primary_color'] = '#e21737';
						$themevariables['menu_hover_itemlevel1_color'] = '#e21737';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['link_hover_color'] = '#e21737';
						$themevariables['link_active_color'] = '#e21737';
						$themevariables['sale_color'] = '#e70b0b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#e21737';
						
						$themevariables['topbar_bg'] = '#ffffff';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#e21737';
						$themevariables['topbar_link_active_color'] = '#e21737';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#e21737';
						$themevariables['header_link_active_color'] = '#e21737';

						$themevariables['footer_link_hover_color'] = '#e21737';
						$themevariables['footer_link_active_color'] = '#e21737';
						break;
					// for Food 02
					case 26:
						$themevariables['primary_color'] = '#e21737';
						$themevariables['menu_hover_itemlevel1_color'] = '#e21737';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['link_hover_color'] = '#e21737';
						$themevariables['link_active_color'] = '#e21737';
						$themevariables['sale_color'] = '#e70b0b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#e21737';
						
						$themevariables['topbar_bg'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#e21737';
						$themevariables['header_link_active_color'] = '#e21737';

						$themevariables['footer_link_hover_color'] = '#e21737';
						$themevariables['footer_link_active_color'] = '#e21737';
						break;
					// for Food 03
					case 27:
						$themevariables['primary_color'] = '#e21737';
						$themevariables['menu_hover_itemlevel1_color'] = '#e21737';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['link_hover_color'] = '#e21737';
						$themevariables['link_active_color'] = '#e21737';
						$themevariables['sale_color'] = '#e70b0b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#e21737';
						
						$themevariables['topbar_bg'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#e21737';
						$themevariables['header_link_active_color'] = '#e21737';

						$themevariables['footer_link_hover_color'] = '#e21737';
						$themevariables['footer_link_active_color'] = '#e21737';
						break;
					// for Food 04
					case 28:
						$themevariables['primary_color'] = '#e21737';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#323232';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['link_hover_color'] = '#e21737';
						$themevariables['link_active_color'] = '#e21737';
						$themevariables['sale_color'] = '#e70b0b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#e21737';
						$themevariables['header_sticky_bg'] = 'rgba(226, 23, 55, 0.95)';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#e21737';
						$themevariables['header_link_active_color'] = '#e21737';

						$themevariables['footer_link_hover_color'] = '#e21737';
						$themevariables['footer_link_active_color'] = '#e21737';
						break;
					// for Handmade 01, 02, 03, 04
					case $presetopt == 30 || $presetopt == 31 || $presetopt == 32 || $presetopt == 33:
						$themevariables['primary_color'] = '#67af7c';
						$themevariables['menu_color'] = '#767676';
						$themevariables['menu_hover_itemlevel1_color'] = '#67af7c';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#67af7c';
						$themevariables['link_active_color'] = '#67af7c';
						$themevariables['sale_color'] = '#67af7c';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#67af7c';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#67af7c';
						$themevariables['topbar_link_active_color'] = '#67af7c';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#67af7c';
						$themevariables['header_link_active_color'] = '#67af7c';

						$themevariables['footer_link_hover_color'] = '#67af7c';
						$themevariables['footer_link_active_color'] = '#67af7c';
						$themevariables['notification_bg'] = '#67af7c';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Fashion 01, 04
					case $presetopt == 34 || $presetopt == 37:
						$themevariables['primary_color'] = '#f53737';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#f53737';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#f53737';
						$themevariables['link_active_color'] = '#f53737';
						$themevariables['sale_color'] = '#f53737';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#f53737';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#f53737';
						$themevariables['topbar_link_active_color'] = '#f53737';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#f53737';
						$themevariables['header_link_active_color'] = '#f53737';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#f53737';
						$themevariables['footer_link_active_color'] = '#f53737';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Fashion 02
					case $presetopt == 35:
						$themevariables['page_content_background'] = '#f5f5f5';
						$themevariables['primary_color'] = '#f53737';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#f53737';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#f53737';
						$themevariables['link_active_color'] = '#f53737';
						$themevariables['sale_color'] = '#f53737';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#f53737';
						
						$themevariables['topbar_bg'] = '#ffffff';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#f53737';
						$themevariables['topbar_link_active_color'] = '#f53737';
						$themevariables['header_bg'] = '#ffffff';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#f53737';
						$themevariables['header_link_active_color'] = '#f53737';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#f53737';
						$themevariables['footer_link_active_color'] = '#f53737';
						break;
					// for Fashion 03
					case $presetopt == 36:
						$themevariables['primary_color'] = '#f53737';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#f53737';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#f53737';
						$themevariables['link_active_color'] = '#f53737';
						$themevariables['sale_color'] = '#f53737';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#f53737';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#f53737';
						$themevariables['topbar_link_active_color'] = '#f53737';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#f53737';
						$themevariables['header_link_active_color'] = '#f53737';
						
						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#f53737';
						$themevariables['footer_link_active_color'] = '#f53737';
						break;
					// for Toy 01
					case $presetopt == 38:
						$themevariables['primary_color'] = '#116aea';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#116aea';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#116aea';
						$themevariables['link_active_color'] = '#116aea';
						$themevariables['sale_color'] = '#116aea';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#116aea';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#116aea';
						$themevariables['topbar_link_active_color'] = '#116aea';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#116aea';
						$themevariables['header_link_active_color'] = '#116aea';

						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#323232';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#116aea';
						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#116aea';
						$themevariables['footer_link_active_color'] = '#116aea';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';
						break;
						// for Toy 02
					case $presetopt == 39:
						$themevariables['primary_color'] = '#116aea';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#116aea';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#116aea';
						$themevariables['link_active_color'] = '#116aea';
						$themevariables['sale_color'] = '#116aea';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#116aea';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#116aea';
						$themevariables['topbar_link_active_color'] = '#116aea';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#116aea';
						$themevariables['header_link_active_color'] = '#116aea';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#116aea';
						$themevariables['footer_link_active_color'] = '#116aea';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Toy 03
					case $presetopt == 40:
						$themevariables['primary_color'] = '#116aea';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#323232';
						$themevariables['menu_font_weight'] = '600';
						$themevariables['link_hover_color'] = '#116aea';
						$themevariables['link_active_color'] = '#116aea';
						$themevariables['sale_color'] = '#116aea';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#116aea';
						$themevariables['header_sticky_bg'] = 'rgba(17, 106, 234, 0.95)';
						
						$themevariables['topbar_bg'] = '#116aea';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#323232';
						$themevariables['topbar_link_active_color'] = '#323232';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#116aea';
						$themevariables['header_link_active_color'] = '#116aea';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#116aea';
						$themevariables['footer_link_active_color'] = '#116aea';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Toy 04
					case $presetopt == 41:
						$themevariables['primary_color'] = '#116aea';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#116aea';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#116aea';
						$themevariables['link_active_color'] = '#116aea';
						$themevariables['sale_color'] = '#116aea';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#116aea';
						
						$themevariables['topbar_bg'] = '#116aea';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#323232';
						$themevariables['topbar_link_active_color'] = '#323232';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#116aea';
						$themevariables['header_link_active_color'] = '#116aea';

						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#323232';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#116aea';
						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#116aea';
						$themevariables['footer_link_active_color'] = '#116aea';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Book 01, 02, 04
					case $presetopt == 42 || $presetopt == 43 || $presetopt == 45:
						$themevariables['primary_color'] = '#df2121';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#df2121';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#df2121';
						$themevariables['link_active_color'] = '#df2121';
						$themevariables['sale_color'] = '#df2121';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#df2121';
						$themevariables['row_space'] = '70px';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#df2121';
						$themevariables['topbar_link_active_color'] = '#df2121';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#df2121';
						$themevariables['header_link_active_color'] = '#df2121';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#df2121';
						$themevariables['footer_link_active_color'] = '#df2121';
						$themevariables['notification_bg'] = '#df2121';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Book 03
					case $presetopt == 44:
						$themevariables['primary_color'] = '#df2121';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#323232';
						$themevariables['header_sticky_bg'] = 'rgba(223, 33, 33, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#df2121';
						$themevariables['link_active_color'] = '#df2121';
						$themevariables['sale_color'] = '#df2121';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#df2121';
						$themevariables['row_space'] = '70px';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#df2121';
						$themevariables['topbar_link_active_color'] = '#df2121';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#df2121';
						$themevariables['header_link_active_color'] = '#df2121';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#df2121';
						$themevariables['footer_link_active_color'] = '#df2121';
						$themevariables['notification_bg'] = '#df2121';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Pet 01
					case $presetopt == 46:
						$themevariables['primary_color'] = '#39bfef';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#39bfef';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#39bfef';
						$themevariables['link_active_color'] = '#39bfef';
						$themevariables['sale_color'] = '#39bfef';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#39bfef';
						$themevariables['row_space'] = '70px';
						
						$themevariables['topbar_bg'] = '#f5f5f5';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#39bfef';
						$themevariables['topbar_link_active_color'] = '#39bfef';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#39bfef';
						$themevariables['header_link_active_color'] = '#39bfef';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#39bfef';
						$themevariables['footer_link_active_color'] = '#39bfef';
						$themevariables['notification_bg'] = '#39bfef';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Pet 02
					case $presetopt == 47:
						$themevariables['primary_color'] = '#39bfef';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#323232';
						$themevariables['header_sticky_bg'] = 'rgba(57, 191, 239, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#39bfef';
						$themevariables['link_active_color'] = '#39bfef';
						$themevariables['sale_color'] = '#39bfef';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#39bfef';
						$themevariables['row_space'] = '70px';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#39bfef';
						$themevariables['topbar_link_active_color'] = '#39bfef';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#39bfef';
						$themevariables['header_link_active_color'] = '#39bfef';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#39bfef';
						$themevariables['footer_link_active_color'] = '#39bfef';
						$themevariables['notification_bg'] = '#39bfef';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Pet 03
					case $presetopt == 48:
						$themevariables['primary_color'] = '#39bfef';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#323232';
						$themevariables['header_sticky_bg'] = 'rgba(57, 191, 239, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#39bfef';
						$themevariables['link_active_color'] = '#39bfef';
						$themevariables['sale_color'] = '#39bfef';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#39bfef';
						$themevariables['row_space'] = '70px';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#39bfef';
						$themevariables['topbar_link_active_color'] = '#39bfef';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#39bfef';
						$themevariables['header_link_active_color'] = '#39bfef';

						$themevariables['footer_bg'] = '#f9f9f9';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#39bfef';
						$themevariables['footer_link_active_color'] = '#39bfef';
						$themevariables['notification_bg'] = '#39bfef';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Pet 04
					case $presetopt == 49:
						$themevariables['primary_color'] = '#39bfef';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#39bfef';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#39bfef';
						$themevariables['link_active_color'] = '#39bfef';
						$themevariables['sale_color'] = '#39bfef';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#39bfef';
						$themevariables['row_space'] = '70px';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#39bfef';
						$themevariables['topbar_link_active_color'] = '#39bfef';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#39bfef';
						$themevariables['header_link_active_color'] = '#39bfef';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#39bfef';
						$themevariables['footer_link_active_color'] = '#39bfef';
						$themevariables['notification_bg'] = '#39bfef';
						$themevariables['notification_color'] = '#ffffff';
						break;
					// for Kitchenware 01
					case $presetopt == 50:
						$themevariables['primary_color'] = '#ee8e12';
						$themevariables['menu_color'] = '#767676';
						$themevariables['menu_hover_itemlevel1_color'] = '#ee8e12';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#ee8e12';
						$themevariables['link_active_color'] = '#ee8e12';
						$themevariables['sale_color'] = '#ee8e12';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#ee8e12';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#767676';
						$themevariables['topbar_link_color'] = '#767676';
						$themevariables['topbar_link_hover_color'] = '#ee8e12';
						$themevariables['topbar_link_active_color'] = '#ee8e12';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#767676';
						$themevariables['header_link_hover_color'] = '#ee8e12';
						$themevariables['header_link_active_color'] = '#ee8e12';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#ee8e12';
						$themevariables['bg_h_btn_mc4wp'] = '#f1a541';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#999999';
						$themevariables['footer_link_hover_color'] = '#ee8e12';
						$themevariables['footer_link_active_color'] = '#ee8e12';
						break;
					// for Kitchenware 02
					case $presetopt == 51:
						$themevariables['primary_color'] = '#ee8e12';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#ee8e12';
						$themevariables['header_sticky_bg'] = 'rgba(0, 0, 0, 0.95)';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['link_hover_color'] = '#ee8e12';
						$themevariables['link_active_color'] = '#ee8e12';
						$themevariables['sale_color'] = '#ee8e12';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#ee8e12';
						$themevariables['row_space'] = '70px';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#767676';
						$themevariables['topbar_link_color'] = '#767676';
						$themevariables['topbar_link_hover_color'] = '#ee8e12';
						$themevariables['topbar_link_active_color'] = '#ee8e12';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#ee8e12';
						$themevariables['header_link_active_color'] = '#ee8e12';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#ee8e12';
						$themevariables['bg_h_btn_mc4wp'] = '#f1a541';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#999999';
						$themevariables['footer_link_hover_color'] = '#ee8e12';
						$themevariables['footer_link_active_color'] = '#ee8e12';
					break;
					// for Kitchenware 03
					case $presetopt == 52:
						$themevariables['primary_color'] = '#ee8e12';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#ee8e12';
						$themevariables['header_sticky_bg'] = 'rgba(0, 0, 0, 0.95)';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['link_hover_color'] = '#ee8e12';
						$themevariables['link_active_color'] = '#ee8e12';
						$themevariables['sale_color'] = '#ee8e12';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#ee8e12';
						$themevariables['row_space'] = '70px';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#767676';
						$themevariables['topbar_link_color'] = '#767676';
						$themevariables['topbar_link_hover_color'] = '#ee8e12';
						$themevariables['topbar_link_active_color'] = '#ee8e12';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#ee8e12';
						$themevariables['header_link_active_color'] = '#ee8e12';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#ee8e12';
						$themevariables['bg_h_btn_mc4wp'] = '#f1a541';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#999999';
						$themevariables['footer_link_hover_color'] = '#ee8e12';
						$themevariables['footer_link_active_color'] = '#ee8e12';
						$makali_opt['categories_menu_items'] = '7';
						$makali_opt['categories_menu_home'] = '1';
					break;
					// for Kitchenware 04
					case $presetopt == 53:
						$themevariables['primary_color'] = '#ee8e12';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#ee8e12';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['link_hover_color'] = '#ee8e12';
						$themevariables['link_active_color'] = '#ee8e12';
						$themevariables['sale_color'] = '#ee8e12';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#ee8e12';
						$themevariables['row_space'] = '70px';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#767676';
						$themevariables['topbar_link_color'] = '#767676';
						$themevariables['topbar_link_hover_color'] = '#ee8e12';
						$themevariables['topbar_link_active_color'] = '#ee8e12';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#ee8e12';
						$themevariables['header_link_active_color'] = '#ee8e12';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#ee8e12';
						$themevariables['bg_h_btn_mc4wp'] = '#f1a541';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#999999';
						$themevariables['footer_link_hover_color'] = '#ee8e12';
						$themevariables['footer_link_active_color'] = '#ee8e12';
					break;
					// for Sportwear 01
					case $presetopt == 54:
						$themevariables['primary_color'] = '#7fb82b';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#7fb82b';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#7fb82b';
						$themevariables['link_active_color'] = '#7fb82b';
						$themevariables['sale_color'] = '#7fb82b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#7fb82b';
						
						$themevariables['topbar_bg'] = '#f4f4f4';
						$themevariables['topbar_color'] = '#777777';
						$themevariables['topbar_link_color'] = '#777777';
						$themevariables['topbar_link_hover_color'] = '#7fb82b';
						$themevariables['topbar_link_active_color'] = '#7fb82b';
						$themevariables['header_bg'] = '#ffffff';
						$themevariables['header_color'] = '#777777';
						$themevariables['header_link_color'] = '#777777';
						$themevariables['header_link_hover_color'] = '#7fb82b';
						$themevariables['header_link_active_color'] = '#7fb82b';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#7fb82b';
						$themevariables['bg_h_btn_mc4wp'] = '#8cbf40';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#7fb82b';
						$themevariables['footer_link_active_color'] = '#7fb82b';
						$themevariables['notification_bg'] = '#f4f4f4';
						$themevariables['notification_color'] = '#777777';
					break;
					// for Sportwear 02
					case $presetopt == 55:
						$themevariables['primary_color'] = '#7fb82b';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#7fb82b';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#7fb82b';
						$themevariables['link_active_color'] = '#7fb82b';
						$themevariables['sale_color'] = '#7fb82b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#7fb82b';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#7fb82b';
						$themevariables['topbar_link_active_color'] = '#7fb82b';
						$themevariables['header_bg'] = '#ffffff';
						$themevariables['header_color'] = '#777777';
						$themevariables['header_link_color'] = '#777777';
						$themevariables['header_link_hover_color'] = '#7fb82b';
						$themevariables['header_link_active_color'] = '#7fb82b';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#7fb82b';
						$themevariables['bg_h_btn_mc4wp'] = '#8cbf40';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#7fb82b';
						$themevariables['footer_link_active_color'] = '#7fb82b';
					break;
					// for Sportwear 03
					case $presetopt == 56:
						$themevariables['primary_color'] = '#7fb82b';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#7fb82b';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#7fb82b';
						$themevariables['link_active_color'] = '#7fb82b';
						$themevariables['sale_color'] = '#7fb82b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#7fb82b';
						
						$themevariables['topbar_bg'] = '#f4f4f4';
						$themevariables['topbar_color'] = '#777777';
						$themevariables['topbar_link_color'] = '#777777';
						$themevariables['topbar_link_hover_color'] = '#7fb82b';
						$themevariables['topbar_link_active_color'] = '#7fb82b';
						$themevariables['header_bg'] = '#ffffff';
						$themevariables['header_color'] = '#777777';
						$themevariables['header_link_color'] = '#777777';
						$themevariables['header_link_hover_color'] = '#7fb82b';
						$themevariables['header_link_active_color'] = '#7fb82b';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#7fb82b';
						$themevariables['bg_h_btn_mc4wp'] = '#8cbf40';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#7fb82b';
						$themevariables['footer_link_active_color'] = '#7fb82b';
					break;
					// for Sportwear 03
					case $presetopt == 56:
						$themevariables['primary_color'] = '#7fb82b';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_hover_itemlevel1_color'] = '#7fb82b';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['link_hover_color'] = '#7fb82b';
						$themevariables['link_active_color'] = '#7fb82b';
						$themevariables['sale_color'] = '#7fb82b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#7fb82b';
						
						$themevariables['topbar_bg'] = '#f4f4f4';
						$themevariables['topbar_color'] = '#777777';
						$themevariables['topbar_link_color'] = '#777777';
						$themevariables['topbar_link_hover_color'] = '#7fb82b';
						$themevariables['topbar_link_active_color'] = '#7fb82b';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#777777';
						$themevariables['header_link_color'] = '#777777';
						$themevariables['header_link_hover_color'] = '#7fb82b';
						$themevariables['header_link_active_color'] = '#7fb82b';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#7fb82b';
						$themevariables['bg_h_btn_mc4wp'] = '#8cbf40';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#7fb82b';
						$themevariables['footer_link_active_color'] = '#7fb82b';
					break;
					// for Sportwear 04
					case $presetopt == 57:
						$themevariables['primary_color'] = '#7fb82b';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_hover_itemlevel1_color'] = '#7fb82b';
						$themevariables['header_sticky_bg'] = 'rgba(0, 0, 0, 0.95)';
						$themevariables['menu_font_weight'] = '700';
						$themevariables['link_hover_color'] = '#7fb82b';
						$themevariables['link_active_color'] = '#7fb82b';
						$themevariables['sale_color'] = '#7fb82b';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#7fb82b';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#7fb82b';
						$themevariables['topbar_link_active_color'] = '#7fb82b';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#7fb82b';
						$themevariables['header_link_active_color'] = '#7fb82b';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#7fb82b';
						$themevariables['bg_h_btn_mc4wp'] = '#8cbf40';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#7fb82b';
						$themevariables['footer_link_active_color'] = '#7fb82b';
					break;
					// for Supermarket 01
					case $presetopt == 58:
						$themevariables['primary_color'] = '#fedc19';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['menu_hover_itemlevel1_color'] = '#fedc19';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['link_hover_color'] = '#fedc19';
						$themevariables['link_active_color'] = '#fedc19';
						$themevariables['sale_color'] = '#fedc19';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#fedc19';
						$themevariables['categories_sub_menu_color'] = '#323232';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#fedc19';
						$themevariables['topbar_link_active_color'] = '#fedc19';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#555555';
						$themevariables['header_link_color'] = '#555555';
						$themevariables['header_link_hover_color'] = '#fedc19';
						$themevariables['header_link_active_color'] = '#fedc19';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['bg_btn_mc4wp'] = '#fedc19';
						$themevariables['bg_h_btn_mc4wp'] = '#323232';
						$themevariables['color_btn_mc4wp'] = '#323232';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#fedc19';
						$themevariables['footer_link_active_color'] = '#fedc19';
						$makali_opt['categories_menu_items'] = '11';
						$makali_opt['categories_menu_home'] = '1';
						$themevariables['row_space'] = '60px';
						$themevariables['row_container'] = '1440px';
					break;
					// for Supermarket 02
					case $presetopt == 59:
						$themevariables['primary_color'] = '#fedc19';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['menu_hover_itemlevel1_color'] = '#323232';
						$themevariables['header_sticky_bg'] = 'rgba(254, 220, 25, 0.95)';
						$themevariables['link_hover_color'] = '#fedc19';
						$themevariables['link_active_color'] = '#fedc19';
						$themevariables['sale_color'] = '#fedc19';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#fedc19';
						$themevariables['categories_sub_menu_color'] = '#323232';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#fedc19';
						$themevariables['topbar_link_active_color'] = '#fedc19';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#555555';
						$themevariables['header_link_color'] = '#555555';
						$themevariables['header_link_hover_color'] = '#fedc19';
						$themevariables['header_link_active_color'] = '#fedc19';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['bg_btn_mc4wp'] = '#fedc19';
						$themevariables['bg_h_btn_mc4wp'] = '#323232';
						$themevariables['color_btn_mc4wp'] = '#323232';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#fedc19';
						$themevariables['footer_link_active_color'] = '#fedc19';
						$themevariables['row_space'] = '60px';
						$themevariables['row_container'] = '1440px';
					break;
					// for Supermarket 03
					case $presetopt == 60:
						$themevariables['primary_color'] = '#fedc19';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['menu_hover_itemlevel1_color'] = '#fedc19';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['link_hover_color'] = '#fedc19';
						$themevariables['link_active_color'] = '#fedc19';
						$themevariables['sale_color'] = '#fedc19';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#fedc19';
						$themevariables['categories_sub_menu_color'] = '#323232';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#fedc19';
						$themevariables['topbar_link_active_color'] = '#fedc19';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#555555';
						$themevariables['header_link_color'] = '#555555';
						$themevariables['header_link_hover_color'] = '#fedc19';
						$themevariables['header_link_active_color'] = '#fedc19';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['bg_btn_mc4wp'] = '#fedc19';
						$themevariables['bg_h_btn_mc4wp'] = '#fce145';
						$themevariables['color_btn_mc4wp'] = '#323232';
						$themevariables['color_h_btn_mc4wp'] = '#323232';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#fedc19';
						$themevariables['footer_link_active_color'] = '#fedc19';
						$themevariables['row_space'] = '60px';
						$themevariables['row_container'] = '1440px';
					break;
					// for Supermarket 04
					case $presetopt == 61:
						$themevariables['primary_color'] = '#fedc19';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['menu_hover_itemlevel1_color'] = '#fedc19';
						$themevariables['header_sticky_bg'] = 'rgba(50, 50, 50, 0.95)';
						$themevariables['link_hover_color'] = '#fedc19';
						$themevariables['link_active_color'] = '#fedc19';
						$themevariables['sale_color'] = '#fedc19';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#fedc19';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#fedc19';
						$themevariables['topbar_link_active_color'] = '#fedc19';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#fedc19';
						$themevariables['header_link_active_color'] = '#fedc19';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['bg_btn_mc4wp'] = '#fedc19';
						$themevariables['bg_h_btn_mc4wp'] = '#fce145';
						$themevariables['color_btn_mc4wp'] = '#323232';
						$themevariables['color_h_btn_mc4wp'] = '#323232';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#fedc19';
						$themevariables['footer_link_active_color'] = '#fedc19';
						$themevariables['row_space'] = '60px';
						$themevariables['row_container'] = '1440px';
					break;
					// for Flower 01
					case $presetopt == 62:
						$themevariables['text_color'] = '#777777';
						$themevariables['body_font'] = 'Playfair Display';
						$themevariables['heading_font'] = 'Playfair Display';
						$themevariables['menu_font'] = 'Playfair Display';
						$themevariables['sub_menu_font'] = 'Playfair Display';
						$themevariables['dropdown_font'] = 'Playfair Display';
						$themevariables['categories_font'] = 'Playfair Display';
						$themevariables['categories_sub_menu_font'] = 'Playfair Display';
						$themevariables['price_font'] = 'Playfair Display';
					
						$themevariables['primary_color'] = '#db8678';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['menu_hover_itemlevel1_color'] = '#db8678';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['link_hover_color'] = '#db8678';
						$themevariables['link_active_color'] = '#db8678';
						$themevariables['sale_color'] = '#db8678';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#db8678';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#db8678';
						$themevariables['topbar_link_active_color'] = '#db8678';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#db8678';
						$themevariables['header_link_active_color'] = '#db8678';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['bg_btn_mc4wp'] = '#db8678';
						$themevariables['bg_h_btn_mc4wp'] = '#de9184';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#db8678';
						$themevariables['footer_link_active_color'] = '#db8678';
					break;
					// for Flower 02
					case $presetopt == 63:
						$themevariables['text_color'] = '#777777';
						$themevariables['body_font'] = 'Playfair Display';
						$themevariables['heading_font'] = 'Playfair Display';
						$themevariables['menu_font'] = 'Playfair Display';
						$themevariables['sub_menu_font'] = 'Playfair Display';
						$themevariables['dropdown_font'] = 'Playfair Display';
						$themevariables['categories_font'] = 'Playfair Display';
						$themevariables['categories_sub_menu_font'] = 'Playfair Display';
						$themevariables['price_font'] = 'Playfair Display';
					
						$themevariables['primary_color'] = '#db8678';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['menu_hover_itemlevel1_color'] = '#db8678';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['link_hover_color'] = '#db8678';
						$themevariables['link_active_color'] = '#db8678';
						$themevariables['sale_color'] = '#db8678';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#db8678';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#767676';
						$themevariables['topbar_link_color'] = '#767676';
						$themevariables['topbar_link_hover_color'] = '#db8678';
						$themevariables['topbar_link_active_color'] = '#db8678';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#db8678';
						$themevariables['header_link_active_color'] = '#db8678';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['bg_btn_mc4wp'] = '#db8678';
						$themevariables['bg_h_btn_mc4wp'] = '#de9184';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#db8678';
						$themevariables['footer_link_active_color'] = '#db8678';
					break;
					// for Flower 03
					case $presetopt == 64:
						$themevariables['text_color'] = '#777777';
						$themevariables['body_font'] = 'Playfair Display';
						$themevariables['heading_font'] = 'Playfair Display';
						$themevariables['menu_font'] = 'Playfair Display';
						$themevariables['sub_menu_font'] = 'Playfair Display';
						$themevariables['dropdown_font'] = 'Playfair Display';
						$themevariables['categories_font'] = 'Playfair Display';
						$themevariables['categories_sub_menu_font'] = 'Playfair Display';
						$themevariables['price_font'] = 'Playfair Display';
					
						$themevariables['primary_color'] = '#db8678';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['menu_hover_itemlevel1_color'] = '#db8678';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['link_hover_color'] = '#db8678';
						$themevariables['link_active_color'] = '#db8678';
						$themevariables['sale_color'] = '#db8678';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#db8678';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#767676';
						$themevariables['topbar_link_color'] = '#767676';
						$themevariables['topbar_link_hover_color'] = '#db8678';
						$themevariables['topbar_link_active_color'] = '#db8678';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#db8678';
						$themevariables['header_link_active_color'] = '#db8678';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['bg_btn_mc4wp'] = '#db8678';
						$themevariables['bg_h_btn_mc4wp'] = '#de9184';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#db8678';
						$themevariables['footer_link_active_color'] = '#db8678';
					break;
					// for Flower 04
					case $presetopt == 65:
						$themevariables['text_color'] = '#777777';
						$themevariables['body_font'] = 'Playfair Display';
						$themevariables['heading_font'] = 'Playfair Display';
						$themevariables['menu_font'] = 'Playfair Display';
						$themevariables['sub_menu_font'] = 'Playfair Display';
						$themevariables['dropdown_font'] = 'Playfair Display';
						$themevariables['categories_font'] = 'Playfair Display';
						$themevariables['categories_sub_menu_font'] = 'Playfair Display';
						$themevariables['price_font'] = 'Playfair Display';
					
						$themevariables['primary_color'] = '#db8678';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['menu_hover_itemlevel1_color'] = '#323232';
						$themevariables['header_sticky_bg'] = 'rgba(219, 134, 120, 0.95)';
						$themevariables['link_hover_color'] = '#db8678';
						$themevariables['link_active_color'] = '#db8678';
						$themevariables['sale_color'] = '#db8678';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#db8678';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#db8678';
						$themevariables['topbar_link_active_color'] = '#db8678';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#db8678';
						$themevariables['header_link_active_color'] = '#db8678';

						$themevariables['footer_bg'] = '#ffffff';
						$themevariables['bg_btn_mc4wp'] = '#db8678';
						$themevariables['bg_h_btn_mc4wp'] = '#de9184';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#db8678';
						$themevariables['footer_link_active_color'] = '#db8678';
					break;
					// for Bicycle 01
					case $presetopt == 66:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#91b70d';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#91b70d';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['link_hover_color'] = '#91b70d';
						$themevariables['link_active_color'] = '#91b70d';
						$themevariables['sale_color'] = '#91b70d';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#91b70d';
						
						$themevariables['topbar_bg'] = '#ffffff';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#91b70d';
						$themevariables['topbar_link_active_color'] = '#91b70d';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#91b70d';
						$themevariables['header_link_active_color'] = '#91b70d';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#91b70d';
						$themevariables['bg_h_btn_mc4wp'] = '#9bbd24';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#91b70d';
						$themevariables['footer_link_active_color'] = '#91b70d';
					break;
					// for Bicycle 02
					case $presetopt == 67:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#91b70d';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '400';
						$themevariables['menu_hover_itemlevel1_color'] = '#91b70d';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['link_hover_color'] = '#91b70d';
						$themevariables['link_active_color'] = '#91b70d';
						$themevariables['sale_color'] = '#91b70d';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#91b70d';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#91b70d';
						$themevariables['topbar_link_active_color'] = '#91b70d';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#91b70d';
						$themevariables['header_link_active_color'] = '#91b70d';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#91b70d';
						$themevariables['bg_h_btn_mc4wp'] = '#9bbd24';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#91b70d';
						$themevariables['footer_link_active_color'] = '#91b70d';
					break;
					// for Bicycle 03
					case $presetopt == 68:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#91b70d';
						$themevariables['menu_color'] = '#767676';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#91b70d';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['link_hover_color'] = '#91b70d';
						$themevariables['link_active_color'] = '#91b70d';
						$themevariables['sale_color'] = '#91b70d';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#91b70d';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#91b70d';
						$themevariables['topbar_link_active_color'] = '#91b70d';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#91b70d';
						$themevariables['header_link_active_color'] = '#91b70d';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#91b70d';
						$themevariables['bg_h_btn_mc4wp'] = '#9bbd24';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#91b70d';
						$themevariables['footer_link_active_color'] = '#91b70d';
					break;
					// for Bicycle 04
					case $presetopt == 69:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#91b70d';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#91b70d';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['link_hover_color'] = '#91b70d';
						$themevariables['link_active_color'] = '#91b70d';
						$themevariables['sale_color'] = '#91b70d';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#91b70d';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#91b70d';
						$themevariables['topbar_link_active_color'] = '#91b70d';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#91b70d';
						$themevariables['header_link_active_color'] = '#91b70d';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#91b70d';
						$themevariables['bg_h_btn_mc4wp'] = '#9bbd24';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#91b70d';
						$themevariables['footer_link_active_color'] = '#91b70d';
					break;
					// for Barber 01
					case $presetopt == 70:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#cc9933';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#cc9933';
						$themevariables['header_sticky_bg'] = 'rgba(0, 0, 0, 0.6)';
						$themevariables['link_hover_color'] = '#cc9933';
						$themevariables['link_active_color'] = '#cc9933';
						$themevariables['sale_color'] = '#cc9933';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#cc9933';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#cc9933';
						$themevariables['topbar_link_active_color'] = '#cc9933';
						$themevariables['header_bg'] = 'rgba(0, 0, 0, 0.6)';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#cc9933';
						$themevariables['header_link_active_color'] = '#cc9933';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#cc9933';
						$themevariables['bg_h_btn_mc4wp'] = '#cc9966';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#cc9933';
						$themevariables['footer_link_active_color'] = '#cc9933';
					break;
					// for Barber 02
					case $presetopt == 71:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#cc9933';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#cc9933';
						$themevariables['header_sticky_bg'] = 'rgba(0, 0, 0, 0.6)';
						$themevariables['link_hover_color'] = '#cc9933';
						$themevariables['link_active_color'] = '#cc9933';
						$themevariables['sale_color'] = '#cc9933';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#cc9933';
						
						$themevariables['topbar_bg'] = '#cc9933';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#323232';
						$themevariables['topbar_link_active_color'] = '#323232';
						$themevariables['header_bg'] = '#333333';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#cc9933';
						$themevariables['header_link_active_color'] = '#cc9933';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#cc9933';
						$themevariables['bg_h_btn_mc4wp'] = '#cc9966';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#cc9933';
						$themevariables['footer_link_active_color'] = '#cc9933';
						$themevariables['notification_bg'] = '#cc9933';
						$themevariables['notification_color'] = '#ffffff';
					break;
					// for Barber 03
					case $presetopt == 72:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#cc9933';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#cc9933';
						$themevariables['header_sticky_bg'] = 'rgba(0, 0, 0, 0.6)';
						$themevariables['link_hover_color'] = '#cc9933';
						$themevariables['link_active_color'] = '#cc9933';
						$themevariables['sale_color'] = '#cc9933';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#cc9933';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#323232';
						$themevariables['topbar_link_active_color'] = '#323232';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#cc9933';
						$themevariables['header_link_active_color'] = '#cc9933';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#cc9933';
						$themevariables['bg_h_btn_mc4wp'] = '#cc9966';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#cc9933';
						$themevariables['footer_link_active_color'] = '#cc9933';
						$themevariables['notification_bg'] = '#cc9933';
						$themevariables['notification_color'] = '#ffffff';
					break;
					// for Barber 04
					case $presetopt == 73:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#cc9933';
						$themevariables['menu_color'] = '#666666';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#cc9933';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#cc9933';
						$themevariables['link_active_color'] = '#cc9933';
						$themevariables['sale_color'] = '#cc9933';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#cc9933';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#cc9933';
						$themevariables['topbar_link_active_color'] = '#cc9933';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#cc9933';
						$themevariables['header_link_active_color'] = '#cc9933';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#cc9933';
						$themevariables['bg_h_btn_mc4wp'] = '#cc9966';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#ffffff';
						$themevariables['footer_link_hover_color'] = '#cc9933';
						$themevariables['footer_link_active_color'] = '#cc9933';
						$themevariables['notification_bg'] = '#cc9933';
						$themevariables['notification_color'] = '#ffffff';
					break;
					// for Watches 01
					case $presetopt == 74:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#e55022';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#e55022';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#e55022';
						$themevariables['link_active_color'] = '#e55022';
						$themevariables['sale_color'] = '#e55022';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#e55022';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#e55022';
						$themevariables['topbar_link_active_color'] = '#e55022';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#e55022';
						$themevariables['header_link_active_color'] = '#e55022';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#e55022';
						$themevariables['bg_h_btn_mc4wp'] = '#e86138';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#999999';
						$themevariables['footer_link_hover_color'] = '#e55022';
						$themevariables['footer_link_active_color'] = '#e55022';
						$themevariables['notification_bg'] = '#e55022';
						$themevariables['notification_color'] = '#ffffff';
					break;
					// for Watches 02
					case $presetopt == 75:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#e55022';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#e55022';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#e55022';
						$themevariables['link_active_color'] = '#e55022';
						$themevariables['sale_color'] = '#e55022';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#e55022';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#e55022';
						$themevariables['topbar_link_active_color'] = '#e55022';
						$themevariables['header_bg'] = '#ffffff';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#e55022';
						$themevariables['header_link_active_color'] = '#e55022';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#e55022';
						$themevariables['bg_h_btn_mc4wp'] = '#e86138';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#999999';
						$themevariables['footer_link_hover_color'] = '#e55022';
						$themevariables['footer_link_active_color'] = '#e55022';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';
					break;
					// for Watches 03
					case $presetopt == 76:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#e55022';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#e55022';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#e55022';
						$themevariables['link_active_color'] = '#e55022';
						$themevariables['sale_color'] = '#e55022';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#e55022';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#e55022';
						$themevariables['topbar_link_active_color'] = '#e55022';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#e55022';
						$themevariables['header_link_active_color'] = '#e55022';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#e55022';
						$themevariables['bg_h_btn_mc4wp'] = '#e86138';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#999999';
						$themevariables['footer_link_color'] = '#999999';
						$themevariables['footer_link_hover_color'] = '#e55022';
						$themevariables['footer_link_active_color'] = '#e55022';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';
					break;
					// for Watches 04
					case $presetopt == 77:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#e55022';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#e55022';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#e55022';
						$themevariables['link_active_color'] = '#e55022';
						$themevariables['sale_color'] = '#e55022';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#e55022';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#e55022';
						$themevariables['topbar_link_active_color'] = '#e55022';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#e55022';
						$themevariables['header_link_active_color'] = '#e55022';

						$themevariables['footer_bg'] = '#323232';
						$themevariables['bg_btn_mc4wp'] = '#e55022';
						$themevariables['bg_h_btn_mc4wp'] = '#e86138';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#ffffff';
						$themevariables['footer_color'] = '#ffffff';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#e55022';
						$themevariables['footer_link_active_color'] = '#e55022';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';
						$makali_opt['categories_menu_home'] = '1';
						$makali_opt['categories_menu_items'] = '5';
						$themevariables['categories_font_size'] = '14px';
						$themevariables['categories_font_weight'] = '500';
						$themevariables['categories_color'] = '#323232';
					break;
					// for Bag 01
					case $presetopt == 78:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#33bcf5';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#33bcf5';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#33bcf5';
						$themevariables['link_active_color'] = '#33bcf5';
						$themevariables['sale_color'] = '#33bcf5';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#33bcf5';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#33bcf5';
						$themevariables['topbar_link_active_color'] = '#33bcf5';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#33bcf5';
						$themevariables['header_link_active_color'] = '#33bcf5';

						$themevariables['footer_bg'] = '#f8f8f8';
						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#33bcf5';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#33bcf5';
						$themevariables['footer_link_active_color'] = '#33bcf5';
					break;
					// for Bag 02
					case $presetopt == 79:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#33bcf5';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#33bcf5';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#33bcf5';
						$themevariables['link_active_color'] = '#33bcf5';
						$themevariables['sale_color'] = '#33bcf5';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#33bcf5';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#33bcf5';
						$themevariables['topbar_link_active_color'] = '#33bcf5';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#33bcf5';
						$themevariables['header_link_active_color'] = '#33bcf5';
						$themevariables['notification_bg'] = '#f4f5f7';
						$themevariables['notification_color'] = '#323232';

						$themevariables['footer_bg'] = '#f8f8f8';
						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#33bcf5';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#33bcf5';
						$themevariables['footer_link_active_color'] = '#33bcf5';
					break;
					// for Bag 03
					case $presetopt == 80:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#33bcf5';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#33bcf5';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#33bcf5';
						$themevariables['link_active_color'] = '#33bcf5';
						$themevariables['sale_color'] = '#33bcf5';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#33bcf5';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#cccccc';
						$themevariables['topbar_link_color'] = '#cccccc';
						$themevariables['topbar_link_hover_color'] = '#33bcf5';
						$themevariables['topbar_link_active_color'] = '#33bcf5';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#33bcf5';
						$themevariables['header_link_active_color'] = '#33bcf5';

						$themevariables['footer_bg'] = '#f8f8f8';
						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#33bcf5';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#33bcf5';
						$themevariables['footer_link_active_color'] = '#33bcf5';
					break;
					// for Bag 04
					case $presetopt == 81:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#33bcf5';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#33bcf5';
						$themevariables['header_sticky_bg'] = 'rgba(0, 0, 0, 0.5)';
						$themevariables['link_hover_color'] = '#33bcf5';
						$themevariables['link_active_color'] = '#33bcf5';
						$themevariables['sale_color'] = '#33bcf5';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#33bcf5';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#cccccc';
						$themevariables['topbar_link_color'] = '#cccccc';
						$themevariables['topbar_link_hover_color'] = '#33bcf5';
						$themevariables['topbar_link_active_color'] = '#33bcf5';
						$themevariables['header_bg'] = '#323232';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#33bcf5';
						$themevariables['header_link_active_color'] = '#33bcf5';

						$themevariables['footer_bg'] = '#f8f8f8';
						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#33bcf5';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#33bcf5';
						$themevariables['footer_link_active_color'] = '#33bcf5';
					break;
					// for Lighting 01
					case $presetopt == 82:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#45b5b1';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#45b5b1';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#45b5b1';
						$themevariables['link_active_color'] = '#45b5b1';
						$themevariables['sale_color'] = '#45b5b1';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#45b5b1';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#45b5b1';
						$themevariables['topbar_link_active_color'] = '#45b5b1';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#45b5b1';
						$themevariables['header_link_active_color'] = '#45b5b1';

						$themevariables['footer_bg'] = '#f8f8f8';
						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#45b5b1';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#45b5b1';
						$themevariables['footer_link_active_color'] = '#45b5b1';
					break;
					// for Lighting 02
					case $presetopt == 83:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#45b5b1';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#45b5b1';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#45b5b1';
						$themevariables['link_active_color'] = '#45b5b1';
						$themevariables['sale_color'] = '#45b5b1';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#45b5b1';
						
						$themevariables['topbar_bg'] = '#323232';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#45b5b1';
						$themevariables['topbar_link_active_color'] = '#45b5b1';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#45b5b1';
						$themevariables['header_link_active_color'] = '#45b5b1';

						$themevariables['footer_bg'] = '#f8f8f8';
						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#45b5b1';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#45b5b1';
						$themevariables['footer_link_active_color'] = '#45b5b1';
						$themevariables['notification_bg'] = '#323232';
						$themevariables['notification_color'] = '#ffffff';
					break;
					// for Lighting 04
					case $presetopt == 84:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#45b5b1';
						$themevariables['menu_color'] = '#323232';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#45b5b1';
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.9)';
						$themevariables['link_hover_color'] = '#45b5b1';
						$themevariables['link_active_color'] = '#45b5b1';
						$themevariables['sale_color'] = '#45b5b1';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#45b5b1';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#323232';
						$themevariables['topbar_link_color'] = '#323232';
						$themevariables['topbar_link_hover_color'] = '#45b5b1';
						$themevariables['topbar_link_active_color'] = '#45b5b1';
						$themevariables['header_bg'] = 'transparent';
						$themevariables['header_color'] = '#323232';
						$themevariables['header_link_color'] = '#323232';
						$themevariables['header_link_hover_color'] = '#45b5b1';
						$themevariables['header_link_active_color'] = '#45b5b1';

						$themevariables['footer_bg'] = '#f8f8f8';
						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#45b5b1';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#45b5b1';
						$themevariables['footer_link_active_color'] = '#45b5b1';
					break;
					// for Lighting 05
					case $presetopt == 85:
						$themevariables['text_color'] = '#777777';
						$themevariables['primary_color'] = '#45b5b1';
						$themevariables['menu_color'] = '#ffffff';
						$themevariables['menu_font_weight'] = '500';
						$themevariables['menu_hover_itemlevel1_color'] = '#45b5b1';
						$themevariables['header_sticky_bg'] = 'rgba(0, 0, 0, 0.8)';
						$themevariables['link_hover_color'] = '#45b5b1';
						$themevariables['link_active_color'] = '#45b5b1';
						$themevariables['sale_color'] = '#45b5b1';
						$themevariables['saletext_color'] = '#ffffff';
						$themevariables['rate_color'] = '#45b5b1';
						
						$themevariables['topbar_bg'] = 'transparent';
						$themevariables['topbar_color'] = '#ffffff';
						$themevariables['topbar_link_color'] = '#ffffff';
						$themevariables['topbar_link_hover_color'] = '#45b5b1';
						$themevariables['topbar_link_active_color'] = '#45b5b1';
						$themevariables['header_bg'] = '#323232';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#45b5b1';
						$themevariables['header_link_active_color'] = '#45b5b1';

						$themevariables['footer_bg'] = '#f8f8f8';
						$themevariables['bg_btn_mc4wp'] = '#323232';
						$themevariables['bg_h_btn_mc4wp'] = '#45b5b1';
						$themevariables['color_btn_mc4wp'] = '#ffffff';
						$themevariables['color_h_btn_mc4wp'] = '#ffffff';
						$themevariables['footer_title_color'] = '#323232';
						$themevariables['footer_color'] = '#777777';
						$themevariables['footer_link_color'] = '#777777';
						$themevariables['footer_link_hover_color'] = '#45b5b1';
						$themevariables['footer_link_active_color'] = '#45b5b1';
					break;
				}
				if(function_exists('compileLessFile')){
					compileLessFile('theme.less', 'theme'.$presetopt.'.css', $themevariables);
				}
			}
		}
		// Load main theme css style files
		wp_enqueue_style( 'makali-css-theme', get_template_directory_uri() . '/css/theme'.$presetopt.'.css', array('bootstrap-css'), '1.0.0' );
		wp_enqueue_style( 'makali-css-custom', get_template_directory_uri() . '/css/opt_css.css', array('makali-css-theme'), '1.0.0' );
		if(function_exists('WP_Filesystem')){
			if ( ! WP_Filesystem() ) {
				$url = wp_nonce_url();
				request_filesystem_credentials($url, '', true, false, null);
			}
			global $wp_filesystem;
			//add custom css, sharing code to header
			if($wp_filesystem->exists(get_template_directory(). '/css/opt_css.css')){
				$customcss = $wp_filesystem->get_contents(get_template_directory(). '/css/opt_css.css');
				if(isset($makali_opt['custom_css']) && $customcss!=$makali_opt['custom_css']){ //if new update, write file content
					$wp_filesystem->put_contents(
						get_template_directory(). '/css/opt_css.css',
						$makali_opt['custom_css'],
						FS_CHMOD_FILE // predefined mode settings for WP files
					);
				}
			} else {
				$wp_filesystem->put_contents(
					get_template_directory(). '/css/opt_css.css',
					$makali_opt['custom_css'],
					FS_CHMOD_FILE // predefined mode settings for WP files
				);
			}
		}
		//add javascript variables
		ob_start(); ?>
		"use strict";
		var makali_brandnumber = <?php if(isset($makali_opt['brandnumber'])) { echo esc_js($makali_opt['brandnumber']); } else { echo '6'; } ?>,
			makali_brandscrollnumber = <?php if(isset($makali_opt['brandscrollnumber'])) { echo esc_js($makali_opt['brandscrollnumber']); } else { echo '2';} ?>,
			makali_brandpause = <?php if(isset($makali_opt['brandpause'])) { echo esc_js($makali_opt['brandpause']); } else { echo '3000'; } ?>,
			makali_brandanimate = <?php if(isset($makali_opt['brandanimate'])) { echo esc_js($makali_opt['brandanimate']); } else { echo '700';} ?>;
		var makali_brandscroll = false;
			<?php if(isset($makali_opt['brandscroll'])){ ?>
				makali_brandscroll = <?php echo esc_js($makali_opt['brandscroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var makali_categoriesnumber = <?php if(isset($makali_opt['categoriesnumber'])) { echo esc_js($makali_opt['categoriesnumber']); } else { echo '6'; } ?>,
			makali_categoriesscrollnumber = <?php if(isset($makali_opt['categoriesscrollnumber'])) { echo esc_js($makali_opt['categoriesscrollnumber']); } else { echo '2';} ?>,
			makali_categoriespause = <?php if(isset($makali_opt['categoriespause'])) { echo esc_js($makali_opt['categoriespause']); } else { echo '3000'; } ?>,
			makali_categoriesanimate = <?php if(isset($makali_opt['categoriesanimate'])) { echo esc_js($makali_opt['categoriesanimate']); } else { echo '700';} ?>;
		var makali_categoriesscroll = 'false';
			<?php if(isset($makali_opt['categoriesscroll'])){ ?>
				makali_categoriesscroll = <?php echo esc_js($makali_opt['categoriesscroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var makali_blogpause = <?php if(isset($makali_opt['blogpause'])) { echo esc_js($makali_opt['blogpause']); } else { echo '3000'; } ?>,
			makali_bloganimate = <?php if(isset($makali_opt['bloganimate'])) { echo esc_js($makali_opt['bloganimate']); } else { echo '700'; } ?>;
		var makali_blogscroll = false;
			<?php if(isset($makali_opt['blogscroll'])){ ?>
				makali_blogscroll = <?php echo esc_js($makali_opt['blogscroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var makali_testipause = <?php if(isset($makali_opt['testipause'])) { echo esc_js($makali_opt['testipause']); } else { echo '3000'; } ?>,
			makali_testianimate = <?php if(isset($makali_opt['testianimate'])) { echo esc_js($makali_opt['testianimate']); } else { echo '700'; } ?>;
		var makali_testiscroll = false;
			<?php if(isset($makali_opt['testiscroll'])){ ?>
				makali_testiscroll = <?php echo esc_js($makali_opt['testiscroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var makali_catenumber = <?php if(isset($makali_opt['catenumber'])) { echo esc_js($makali_opt['catenumber']); } else { echo '6'; } ?>,
			makali_catescrollnumber = <?php if(isset($makali_opt['catescrollnumber'])) { echo esc_js($makali_opt['catescrollnumber']); } else { echo '2';} ?>,
			makali_catepause = <?php if(isset($makali_opt['catepause'])) { echo esc_js($makali_opt['catepause']); } else { echo '3000'; } ?>,
			makali_cateanimate = <?php if(isset($makali_opt['cateanimate'])) { echo esc_js($makali_opt['cateanimate']); } else { echo '700';} ?>;
		var makali_catescroll = false;
			<?php if(isset($makali_opt['catescroll'])){ ?>
				makali_catescroll = <?php echo esc_js($makali_opt['catescroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var makali_menu_number = <?php if(isset($makali_opt['categories_menu_items'])) { echo esc_js((int)$makali_opt['categories_menu_items']); } else { echo '9';} ?>;
		var makali_show_catmenu_home = <?php if(isset($makali_opt['categories_menu_home'])) { echo esc_js((int)$makali_opt['categories_menu_home']); } else { echo '0';} ?>;
		var makali_sticky_header = false;
			<?php if(isset($makali_opt['sticky_header'])){ ?>
				makali_sticky_header = <?php echo esc_js($makali_opt['sticky_header'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		jQuery(document).ready(function(){
			jQuery(".ws").on('focus', function(){
				if(jQuery(this).val()=="<?php esc_html__( 'Search product...', 'makali' );?>"){
					jQuery(this).val("");
				}
			});
			jQuery(".ws").on('focusout', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("<?php esc_html__( 'Search product...', 'makali' );?>");
				}
			});
			jQuery(".wsearchsubmit").on('click', function(){
				if(jQuery("#ws").val()=="<?php esc_html__( 'Search product...', 'makali' );?>" || jQuery("#ws").val()==""){
					jQuery("#ws").focus();
					return false;
				}
			});
			jQuery(".search_input").on('focus', function(){
				if(jQuery(this).val()=="<?php esc_html__( 'Search...', 'makali' );?>"){
					jQuery(this).val("");
				}
			});
			jQuery(".search_input").on('focusout', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("<?php esc_html__( 'Search...', 'makali' );?>");
				}
			});
			jQuery(".blogsearchsubmit").on('click', function(){
				if(jQuery("#search_input").val()=="<?php esc_html__( 'Search...', 'makali' );?>" || jQuery("#search_input").val()==""){
					jQuery("#search_input").focus();
					return false;
				}
			});
		});
		<?php
		$jsvars = ob_get_contents();
		ob_end_clean();
		if(function_exists('WP_Filesystem')){
			if($wp_filesystem->exists(get_template_directory(). '/js/variables.js')){
				$jsvariables = $wp_filesystem->get_contents(get_template_directory(). '/js/variables.js');
				if($jsvars!=$jsvariables){ //if new update, write file content
					$wp_filesystem->put_contents(
						get_template_directory(). '/js/variables.js',
						$jsvars,
						FS_CHMOD_FILE // predefined mode settings for WP files
					);
				}
			} else {
				$wp_filesystem->put_contents(
					get_template_directory(). '/js/variables.js',
					$jsvars,
					FS_CHMOD_FILE // predefined mode settings for WP files
				);
			}
		}
		//add css for footer, header templates
		$jscomposer_templates_args = array(
			'orderby'          => 'title',
			'order'            => 'ASC',
			'post_type'        => 'templatera',
			'post_status'      => 'publish',
			'posts_per_page'   => 100,
		);
		$jscomposer_templates = get_posts( $jscomposer_templates_args );
		if(count($jscomposer_templates) > 0) {
			foreach($jscomposer_templates as $jscomposer_template){
				if($jscomposer_template->post_title == $makali_opt['header_layout'] || $jscomposer_template->post_title == $makali_opt['footer_layout'] || $jscomposer_template->post_title == $makali_opt['header_mobile_layout']){
					$jscomposer_template_css = get_post_meta ( $jscomposer_template->ID, '_wpb_shortcodes_custom_css', false );
					if(isset($jscomposer_template_css[0]))
					wp_add_inline_style( 'makali-css-custom', $jscomposer_template_css[0] );
				}
			}
		}
		//page width
		$makali_opt = get_option( 'makali_opt' );
		if(isset($makali_opt['box_layout_width'])){
			wp_add_inline_style( 'makali-css-custom', '.wrapper.box-layout {max-width: '.$makali_opt['box_layout_width'].'px;}' );
		}
	}
	//add sharing code to header
	function makali_custom_code_header() {
		global $makali_opt;
		if ( isset($makali_opt['share_head_code']) && $makali_opt['share_head_code']!='') {
			echo wp_kses($makali_opt['share_head_code'], array(
				'script' => array(
					'type' => array(),
					'src' => array(),
					'async' => array()
				),
			));
		}
	}
	/**
	 * Register sidebars.
	 *
	 * Registers our main widget area and the front page widget areas.
	 *
	 * @since Makali 1.0
	 */
	function makali_widgets_init() {
		$makali_opt = get_option( 'makali_opt' );
		register_sidebar( array(
			'name' => esc_html__( 'Blog Sidebar', 'makali' ),
			'id' => 'sidebar-1',
			'description' => esc_html__( 'Sidebar on blog page', 'makali' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>',
		) );
		register_sidebar( array(
			'name' => esc_html__( 'Shop Sidebar', 'makali' ),
			'id' => 'sidebar-shop',
			'description' => esc_html__( 'Sidebar on shop page (only sidebar shop layout)', 'makali' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>',
		) );
		register_sidebar( array(
			'name' => esc_html__( 'Single product Sidebar', 'makali' ),
			'id' => 'sidebar-single_product',
			'description' => esc_html__( 'Sidebar on product details page', 'makali' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>',
		) );
		register_sidebar( array(
			'name' => esc_html__( 'Pages Sidebar', 'makali' ),
			'id' => 'sidebar-page',
			'description' => esc_html__( 'Sidebar on content pages', 'makali' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>',
		) );
		if(isset($makali_opt['custom-sidebars']) && $makali_opt['custom-sidebars']!=""){
			foreach($makali_opt['custom-sidebars'] as $sidebar){
				$sidebar_id = str_replace(' ', '-', strtolower($sidebar));
				if($sidebar_id!='') {
					register_sidebar( array(
						'name' => $sidebar,
						'id' => $sidebar_id,
						'description' => $sidebar,
						'before_widget' => '<aside id="%1$s" class="widget %2$s">',
						'after_widget' => '</aside>',
						'before_title' => '<h3 class="widget-title"><span>',
						'after_title' => '</span></h3>',
					) );
				}
			}
		}
	}
	static function makali_meta_box_callback( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'makali_meta_box', 'makali_meta_box_nonce' );
		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$value = get_post_meta( $post->ID, '_makali_post_intro', true );
		echo '<label for="makali_post_intro">';
		esc_html_e( 'This content will be used to replace the featured image, use shortcode here', 'makali' );
		echo '</label><br />';
		wp_editor( $value, 'makali_post_intro', $settings = array() );
	}
	static function makali_custom_sidebar_callback( $post ) {
		global $wp_registered_sidebars;
		$makali_opt = get_option( 'makali_opt' );
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'makali_meta_box', 'makali_meta_box_nonce' );
		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		//show sidebar dropdown select
		$csidebar = get_post_meta( $post->ID, '_makali_custom_sidebar', true );
		echo '<label for="makali_custom_sidebar">';
		esc_html_e( 'Select a custom sidebar for this post/page', 'makali' );
		echo '</label><br />';
		echo '<select id="makali_custom_sidebar" name="makali_custom_sidebar">';
			echo '<option value="">'.esc_html__('- None -', 'makali').'</option>';
			foreach($wp_registered_sidebars as $sidebar){
				$sidebar_id = $sidebar['id'];
				if($csidebar == $sidebar_id){
					echo '<option value="'.$sidebar_id.'" selected="selected">'.$sidebar['name'].'</option>';
				} else {
					echo '<option value="'.$sidebar_id.'">'.$sidebar['name'].'</option>';
				}
			}
		echo '</select><br />';
		//show custom sidebar position
		$csidebarpos = get_post_meta( $post->ID, '_makali_custom_sidebar_pos', true );
		echo '<label for="makali_custom_sidebar_pos">';
		esc_html_e( 'Sidebar position', 'makali' );
		echo '</label><br />';
		echo '<select id="makali_custom_sidebar_pos" name="makali_custom_sidebar_pos">'; ?>
			<option value="left" <?php if($csidebarpos == 'left') {echo 'selected="selected"';}?>><?php echo esc_html__('Left', 'makali'); ?></option>
			<option value="right" <?php if($csidebarpos == 'right') {echo 'selected="selected"';}?>><?php echo esc_html__('Right', 'makali'); ?></option>
		<?php echo '</select>';
	}
	function makali_save_meta_box_data( $post_id ) {
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
		// Check if our nonce is set.
		if ( ! isset( $_POST['makali_meta_box_nonce'] ) ) {
			return;
		}
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['makali_meta_box_nonce'], 'makali_meta_box' ) ) {
			return;
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		/* OK, it's safe for us to save the data now. */
		// Make sure that it is set.
		if ( ! ( isset( $_POST['makali_post_intro'] ) || isset( $_POST['makali_custom_sidebar'] ) ) )  {
			return;
		}
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['makali_post_intro'] );
		// Update the meta field in the database.
		update_post_meta( $post_id, '_makali_post_intro', $my_data );
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['makali_custom_sidebar'] );
		// Update the meta field in the database.
		update_post_meta( $post_id, '_makali_custom_sidebar', $my_data );
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['makali_custom_sidebar_pos'] );
		// Update the meta field in the database.
		update_post_meta( $post_id, '_makali_custom_sidebar_pos', $my_data );
	}
	//Change comment form
	function makali_before_comment_fields() {
		echo '<div class="comment-input">';
	}
	function makali_after_comment_fields() {
		echo '</div>';
	}
	/**
	 * Register postMessage support.
	 *
	 * Add postMessage support for site title and description for the Customizer.
	 *
	 * @since Makali 1.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 */
	function makali_customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	}
	/**
	 * Enqueue Javascript postMessage handlers for the Customizer.
	 *
	 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
	 *
	 * @since Makali 1.0
	 */
	function makali_customize_preview_js() {
		wp_enqueue_script( 'makali-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130301', true );
	}
	function makali_admin_style() {
	  wp_enqueue_style('admin-styles', get_template_directory_uri().'/css/admin.css');
	}
	/**
	* Utility methods
	* ---------------
	*/
	//Add breadcrumbs
	static function makali_breadcrumb() {
		global $post;
		$makali_opt = get_option( 'makali_opt' );
		$brseparator = '<span class="separator">/</span>';
		if (!is_home()) {
			echo '<div class="breadcrumbs">';
			echo '<a href="';
			echo esc_url( home_url( '/' ));
			echo '">';
			echo esc_html__('Home', 'makali');
			echo '</a>'.$brseparator;
			if (is_category() || is_single()) {
				$categories = get_the_category();
				if ( count( $categories ) > 0 ) {
					echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
				}
				if (is_single()) {
					if ( count( $categories ) > 0 ) { echo ''.$brseparator; }
					the_title();
				}
			} elseif (is_page()) {
				if($post->post_parent){
					$anc = get_post_ancestors( $post->ID );
					$title = get_the_title();
					foreach ( $anc as $ancestor ) {
						$output = '<a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a>'.$brseparator;
					}
					echo wp_kses($output, array(
							'a'=>array(
								'href' => array(),
								'title' => array()
							),
							'span'=>array(
								'class'=>array()
							)
						)
					);
					echo '<span title="'.$title.'"> '.$title.'</span>';
				} else {
					echo '<span> '.get_the_title().'</span>';
				}
			}
			elseif (is_tag()) {single_tag_title();}
			elseif (is_day()) {printf( esc_html__( 'Archive for: %s', 'makali' ), '<span>' . get_the_date() . '</span>' );}
			elseif (is_month()) {printf( esc_html__( 'Archive for: %s', 'makali' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'makali' ) ) . '</span>' );}
			elseif (is_year()) {printf( esc_html__( 'Archive for: %s', 'makali' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'makali' ) ) . '</span>' );}
			elseif (is_author()) {echo "<span>".esc_html__('Archive for','makali'); echo'</span>';}
			elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<span>".esc_html__('Blog Archives','makali'); echo'</span>';}
			elseif (is_search()) {echo "<span>".esc_html__('Search Results','makali'); echo'</span>';}
			echo '</div>';
		} else {
			echo '<div class="breadcrumbs">';
			echo '<a href="';
			echo esc_url( home_url( '/' ) );
			echo '">';
			echo esc_html__('Home', 'makali');
			echo '</a>'.$brseparator;
			if(isset($makali_opt['blog_header_text']) && $makali_opt['blog_header_text']!=""){
				echo esc_html($makali_opt['blog_header_text']);
			} else {
				echo esc_html__('Blog', 'makali');
			}
			echo '</div>';
		}
	}
	static function makali_limitStringByWord ($string, $maxlength, $suffix = '') {
		if(function_exists( 'mb_strlen' )) {
			// use multibyte functions by Iysov
			if(mb_strlen( $string )<=$maxlength) return $string;
			$string = mb_substr( $string, 0, $maxlength );
			$index = mb_strrpos( $string, ' ' );
			if($index === FALSE) {
				return $string;
			} else {
				return mb_substr( $string, 0, $index ).$suffix;
			}
		} else { // original code here
			if(strlen( $string )<=$maxlength) return $string;
			$string = substr( $string, 0, $maxlength );
			$index = strrpos( $string, ' ' );
			if($index === FALSE) {
				return $string;
			} else {
				return substr( $string, 0, $index ).$suffix;
			}
		}
	}
	static function makali_excerpt_by_id($post, $length = 25, $tags = '<a><span><em><strong>') {
		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		} elseif( ! is_object( $post ) ) {
			return false;
		}
		if ( has_excerpt( $post->ID ) ) {
			$the_excerpt = $post->post_excerpt;
			return apply_filters( 'the_content', $the_excerpt );
		} else {
			$the_excerpt = $post->post_content;
		}

		$the_excerpt = strip_shortcodes( strip_tags( $the_excerpt, $tags ) );
		$the_excerpt = preg_split( '/\b/', $the_excerpt, $length * 2 + 1 );
		$excerpt_waste = array_pop( $the_excerpt );
		$the_excerpt = implode( $the_excerpt );
		return apply_filters( 'the_content', $the_excerpt );
	}
	/**
	 * Return the Google font stylesheet URL if available.
	 *
	 * The use of Libre Franklin by default is localized. For languages that use
	 * characters not supported by the font, the font can be disabled.
	 *
	 * @since Makali 1.2
	 *
	 * @return string Font stylesheet or empty string if disabled.
	 */
	function makali_get_font_url() {
		$fonts_url = '';
		/* Translators: If there are characters in your language that are not
		* supported by Libre Franklin, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$libre_franklin = _x( 'on', 'Libre Franklin font: on or off', 'makali' );
		$playfair_display = _x( 'on', 'Playfair Display font: on or off', 'makali' );
		if ( 'off' !== $libre_franklin || 'off' !== $playfair_display ) {
			$font_families = array();
			if ( 'off' !== $libre_franklin ) {
				$font_families[] = 'Libre Franklin:400,500,700,900';
			}
			if ( 'off' !== $playfair_display ) {
				$font_families[] = 'Playfair Display:400,700,900';
			}
			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);
			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}
		return esc_url_raw( $fonts_url );
	}
	/**
	 * Displays navigation to next/previous pages when applicable.
	 *
	 * @since Makali 1.0
	 */
	static function makali_content_nav( $html_id ) {
		global $wp_query;
		$html_id = esc_attr( $html_id );
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="<?php echo esc_attr($html_id); ?>" class="navigation" role="navigation">
				<h3 class="assistive-text"><?php esc_html_e( 'Post navigation', 'makali' ); ?></h3>
				<div class="nav-previous"><?php next_posts_link( wp_kses(__( '<span class="meta-nav">&larr;</span> Older posts', 'makali' ),array('span'=>array('class'=>array())) )); ?></div>
				<div class="nav-next"><?php previous_posts_link( wp_kses(__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'makali' ), array('span'=>array('class'=>array())) )); ?></div>
			</nav>
		<?php endif;
	}
	/* Pagination */
	static function makali_pagination() {
		global $wp_query, $paged;
		if(empty($paged)) $paged = 1;
		$pages = $wp_query->max_num_pages;
			if(!$pages || $pages == '') {
			   	$pages = 1;
			}
		if(1 != $pages) {
			echo '<div class="pagination">';
			$big = 999999999; // need an unlikely integer
			echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $wp_query->max_num_pages,
				'prev_text'    => esc_html__('Previous', 'makali'),
				'next_text'    =>esc_html__('Next', 'makali')
			) );
			echo '</div>';
		}
	}
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own makali_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Makali 1.0
	 */
	static function makali_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			// Display trackbacks differently than normal comments.
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<p><?php esc_html_e( 'Pingback:', 'makali' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'makali' ), '<span class="edit-link">', '</span>' ); ?></p>
		<?php
				break;
			default :
			// Proceed with normal comments.
			global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<div class="comment-avatar">
					<?php echo get_avatar( $comment, 50 ); ?>
				</div>
				<div class="comment-info">
					<header class="comment-meta comment-author vcard">
						<?php
							printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
								get_comment_author_link(),
								// If current post author is also comment author, make it known visually.
								( $comment->user_id === $post->post_author ) ? '<span>' . esc_html__( 'Post author', 'makali' ) . '</span>' : ''
							);
							printf( '<time datetime="%1$s">%2$s</time>',
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( esc_html__( '%1$s at %2$s', 'makali' ), get_comment_date(), get_comment_time() )
							);
						?>
						<div class="reply">
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'makali' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</div><!-- .reply -->
					</header><!-- .comment-meta -->
					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'makali' ); ?></p>
					<?php endif; ?>
					<section class="comment-content comment">
						<?php comment_text(); ?>
						<?php edit_comment_link( esc_html__( 'Edit', 'makali' ), '<p class="edit-link">', '</p>' ); ?>
					</section><!-- .comment-content -->
				</div>
			</article><!-- #comment-## -->
		<?php
			break;
		endswitch; // end comment_type check
	}
	/**
	 * Set up post entry meta.
	 *
	 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
	 *
	 * Create your own makali_entry_meta() to override in a child theme.
	 *
	 * @since Makali 1.0
	 */
	static function makali_entry_meta() {
		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', ', ' );
		$num_comments = (int)get_comments_number();
		$write_comments = '';
		if ( comments_open() ) {
			if ( $num_comments == 0 ) {
				$comments = esc_html__('0 comments', 'makali');
			} elseif ( $num_comments > 1 ) {
				$comments = $num_comments . esc_html__(' comments', 'makali');
			} else {
				$comments = esc_html__('1 comment', 'makali');
			}
			$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
		}
		$utility_text = null;
		if ( ( post_password_required() || !comments_open() ) && ($tag_list!=false && isset($tag_list) ) ) {
			$utility_text = esc_html__( 'Tags: %2$s', 'makali' );
		} elseif($tag_list!=false && isset($tag_list) && $num_comments !=0 ){
			$utility_text = esc_html__( '%1$s / Tags: %2$s', 'makali' );
		} elseif ( ($num_comments ==0 || !isset($num_comments) ) && $tag_list==true ) {
			$utility_text = esc_html__( 'Tags: %2$s', 'makali' );
		} else {
			$utility_text = esc_html__( '%1$s', 'makali' );
		}
		printf( $utility_text, $write_comments, $tag_list);
	}
	static function makali_entry_meta_small() {
		// Translators: used between list items, there is a space after the comma.
		$categories_list = get_the_category_list(', ');
		$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( wp_kses(__( 'View all posts by %s', 'makali' ), array('a'=>array())), get_the_author() ) ),
			get_the_author()
		);
		$utility_text = esc_html__( 'Posted by %1$s / %2$s', 'makali' );
		printf( $utility_text, $author, $categories_list );
	}
	static function makali_entry_comments() {
		$date = sprintf( '<time class="entry-date" datetime="%3$s">%4$s</time>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
		$num_comments = (int)get_comments_number();
		$write_comments = '';
		if ( comments_open() ) {
			if ( $num_comments == 0 ) {
				$comments = wp_kses(__('<span>0</span> comments', 'makali'), array('span'=>array()));
			} elseif ( $num_comments > 1 ) {
				$comments = '<span>'.$num_comments .'</span>'. esc_html__(' comments', 'makali');
			} else {
				$comments = wp_kses(__('<span>1</span> comment', 'makali'), array('span'=>array()));
			}
			$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
		}
		$utility_text = esc_html__( '%1$s', 'makali' );
		printf( $utility_text, $write_comments );
	}
	/**
	* TGM-Plugin-Activation
	*/
	function makali_register_required_plugins() {
		$plugins = array(
			array(
				'name'               => esc_html__('RoadThemes Helper', 'makali'),
				'slug'               => 'roadthemes-helper',
				'source'             => get_template_directory() . '/plugins/roadthemes-helper.zip',
				'required'           => true,
				'version'            => '1.0.0',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Mega Main Menu', 'makali'),
				'slug'               => 'mega_main_menu',
				'source'             => PLUGIN_REQUIRED_PATH . '/mega_main_menu.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Revolution Slider', 'makali'),
				'slug'               => 'revslider',
				'source'             => PLUGIN_REQUIRED_PATH . '/revslider.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Import Sample Data', 'makali'),
				'slug'               => 'road-importdata',
				'source'             => get_template_directory() . '/plugins/road-importdata.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('WPBakery Page Builder', 'makali'),
				'slug'               => 'js_composer',
				'source'             => PLUGIN_REQUIRED_PATH . '/js_composer.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Templatera', 'makali'),
				'slug'               => 'templatera',
				'source'             => PLUGIN_REQUIRED_PATH . '/templatera.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Essential Grid', 'makali'),
				'slug'               => 'essential-grid',
				'source'             => PLUGIN_REQUIRED_PATH . '/essential-grid.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'      => esc_html__('Testimonials', 'makali'),
				'slug'      => 'testimonials-by-woothemes',
				'source'             => PLUGIN_REQUIRED_PATH . '/testimonials-by-woothemes.zip',
				'required'  => true,
				'external_url'       => '',
			),
			// Plugins from the WordPress Plugin Repository.
			array(
				'name'               => esc_html__('Redux Framework', 'makali'),
				'slug'               => 'redux-framework',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'      => esc_html__('Contact Form 7', 'makali'),
				'slug'      => 'contact-form-7',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('MailChimp for WordPress', 'makali'),
				'slug'      => 'mailchimp-for-wp',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('Shortcodes Ultimate', 'makali'),
				'slug'      => 'shortcodes-ultimate',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('Simple Local Avatars', 'makali'),
				'slug'      => 'simple-local-avatars',
				'required'  => false,
			),
			array(
				'name'      => esc_html__('TinyMCE Advanced', 'makali'),
				'slug'      => 'tinymce-advanced',
				'required'  => false,
			),
			array(
				'name'      => esc_html__('Widget Importer & Exporter', 'makali'),
				'slug'      => 'widget-importer-exporter',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('WooCommerce', 'makali'),
				'slug'      => 'woocommerce',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('YITH WooCommerce Compare', 'makali'),
				'slug'      => 'yith-woocommerce-compare',
				'required'  => false,
			),
			array(
				'name'      => esc_html__('YITH WooCommerce Wishlist', 'makali'),
				'slug'      => 'yith-woocommerce-wishlist',
				'required'  => false,
			),
			array(
				'name'      => esc_html__('YITH WooCommerce Zoom Magnifier', 'makali'),
				'slug'      => 'yith-woocommerce-zoom-magnifier',
				'required'  => false,
			),
			array(
				'name'      => esc_html__('Smash Balloon Instagram Feed', 'makali'),
				'slug'      => 'instagram-feed',
				'required'  => true,
			),
		);
		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
			'default_path' => '',                      // Default absolute path to pre-packaged plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'makali' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'makali' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'makali' ), // %s = plugin name.
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'makali' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'makali' ), // %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'makali' ), // %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'makali' ), // %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'makali' ), // %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'makali' ), // %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'makali' ), // %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'makali' ), // %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'makali' ), // %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'makali' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'makali' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'makali' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'makali' ),
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'makali' ), // %s = dashboard link.
				'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
		);
		tgmpa( $plugins, $config );
	}
}
// Instantiate theme
$Makali_Class = new Makali_Class();
//Fix duplicate id of mega menu
function makali_mega_menu_id_change($params) {
	ob_start('makali_mega_menu_id_change_call_back');
}
function makali_mega_menu_id_change_call_back($html){
	$html = preg_replace('/id="mega_main_menu"/', 'id="mega_main_menu_first"', $html, 1);
	$html = preg_replace('/id="mega_main_menu_ul"/', 'id="mega_main_menu_ul_first"', $html, 1);
	return $html;
}
add_action('wp_loaded', 'makali_mega_menu_id_change');
function theme_prefix_enqueue_script() {
	wp_add_inline_script( 'makali-js', 'var ajaxurl = "'.admin_url('admin-ajax.php').'";','before' );
}
add_action( 'wp_enqueue_scripts', 'theme_prefix_enqueue_script' );
// Wishlist count
if( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_ajax_update_count' ) ){
	function yith_wcwl_ajax_update_count(){
		wp_send_json( array(
			'count' => yith_wcwl_count_all_products()
		));
	}
	add_action( 'wp_ajax_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
	add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
}
function makali_add_peicon_dashboard() {
   wp_enqueue_style( 'pe-icon-7-stroke-css', get_template_directory_uri() . '/css/pe-icon-7-stroke.css', array(), '1.2.0' );
}

add_action('admin_init', 'makali_add_peicon_dashboard');