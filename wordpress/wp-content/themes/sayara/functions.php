<?php
/**
 * sayara functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package sayara
 */


if ( ! function_exists( 'sayara_setup' ) ) :

	function sayara_setup() {

		load_theme_textdomain( 'sayara', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'sayara' ),
			'departments_menu' => esc_html__( 'Departments Menu', 'sayara' )
		) );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		add_theme_support( 'custom-background', apply_filters( 'sayara_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
		add_image_size( 'sayara-1280x720', 1280,720,true );
		add_image_size( 'sayara-1280x650', 1280,650, array( 'center', 'top' ));
		add_image_size( 'sayara-750x430', 750,430, array( 'center', 'top' ));
		add_image_size( 'sayara-600x399', 600,399,true );
		add_image_size( 'sayara-400-400', 400,400,true );
		add_image_size( 'sayara-200-200', 200,200,true );
		add_image_size( 'sayara-360-260', 360,260,true );
		add_image_size( 'sayara-115x115', 115,115,true );
		add_image_size( 'sayara-100x80', 100,80,true );
		add_image_size( 'sayara-80x80', 80,80,true );
		add_image_size( 'sayara-32x32', 32,32,true );
		add_image_size( 'sayara-300x150', 300,150,true );

	}

endif;
add_action( 'after_setup_theme', 'sayara_setup' );

function sayara_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'sayara_content_width', 640 );
}
add_action( 'after_setup_theme', 'sayara_content_width', 0 );

function sayara_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'sayara' ),
		'id'            => 'sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'sayara' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	if ( class_exists( 'WooCommerce' ) ){
		register_sidebar( array(
			'name'          => esc_html__( 'WooCommerce Store Sidebar', 'sayara' ),
			'id'            => 'woocommerce_store_sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'sayara' ),
			'before_widget' => '<div id="%1$s" class="woocommerce-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="woocommerce-widget-title">',
			'after_title'   => '</h5>',
		) );
	}

	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'sayara' ),
		'id'            => 'footer',
		'description'   => esc_html__( 'Add footer widgets here.', 'sayara' ),
		'before_widget' => '<div class="col-xl-3 col-lg-4 col-md-6"><div id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );
	
}
add_action( 'widgets_init', 'sayara_widgets_init' );


// Register Fonts
function sayara_fonts_url() {
    $font_url = '';
    
    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'sayara' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Rubik:300,400,500,700,900&display=swap' ), "//fonts.googleapis.com/css" );
    }

    return $font_url;
}

// Scripts
function sayara_scripts() {
	// CSS
	wp_enqueue_style( 'sayara-fonts', sayara_fonts_url());
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/css/animate.min.css');
	wp_enqueue_style( 'sayara-default', get_template_directory_uri() . '/assets/css/default.css');
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.min.css');
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/css/fontawesome.min.css');
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
	wp_enqueue_style( 'sayara-style', get_stylesheet_uri() );

	// JS
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), wp_get_theme()->get( 'Version' ), true );
	wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array('jquery'), wp_get_theme()->get( 'Version' ), true );
  	wp_enqueue_script( 'sayara-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), wp_get_theme()->get( 'Version' ), true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_script( 'sayara-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), wp_get_theme()->get( 'Version' ), true );

	wp_localize_script( 'sayara-main', 'sayaraAjaxUrlObj', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
  
	//'sayara-style' is main style of the theme
  	wp_add_inline_style( 'sayara-style', sayara_inline_style());
}

add_action( 'wp_enqueue_scripts', 'sayara_scripts' );

// Denqueue scripts and styles.
function sayara_dequeue_script() {
    wp_dequeue_style( 'elementor-animations' );
    wp_deregister_style( 'elementor-animations' );
}
add_action( 'wp_enqueue_scripts', 'sayara_dequeue_script', 20 );

// Includes files
require get_template_directory() . '/inc/inline-script.php';
require get_template_directory() . '/inc/hooks.php';
require get_template_directory() . '/inc/redux-framework.php';
if (empty(get_user_meta( get_current_user_id(), 'licence_activated' , true ))) {
	require get_template_directory() . '/inc/activate-license.php';
}
require get_template_directory() . '/inc/class-tgm-plugin-activation.php';
require get_template_directory() . '/inc/breadcrumb.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/ajax-search.php';
require get_template_directory() . '/inc/ajax-quick-view.php';

if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

// TGM required plugins
function sayara_register_required_plugins() {
	$plugins = array(

		array(
			'name'        => esc_html__('Redux Framework', 'sayara'),
			'slug'        => 'redux-framework',
			'required' 	  => true,
		),

		array(
			'name'        =>  esc_html__('Elementor', 'sayara'),
			'slug'        => 'elementor',
			'
			required'    => true,
		),

		array(
			'name'        => esc_html__('WooCommerce', 'sayara'),
			'slug'        => 'woocommerce',
			'required' 	  => true,
		),

		array(
			'name'        => esc_html__('YITH WooCommerce Wishlist', 'sayara'),
			'slug'        => 'yith-woocommerce-wishlist',
			'required' 	  => true,
		),

		array(
			'name'        => esc_html__('Sayara Element (licence key required)', 'sayara'),
			'slug'        => 'sayara-element',
			'source'      => 'https://themebing.com/wp-json/download/purchase_code='.get_user_meta( get_current_user_id(), 'licence_activated' , true ).'/name=sayara-element',
			'required' 	  => true,
		),

		array(
			'name'        => esc_html__('Contact Form 7', 'sayara'),
			'slug'        => 'contact-form-7',
			'required' 	  => true,
		),

		array(
			'name'        => esc_html__('Mailchimp for WordPress', 'sayara'),
			'slug'        => 'mailchimp-for-wp',
			'required' 	  => true,
		),

		array(
			'name'        => esc_html__('One Click Demo Import', 'sayara'),
			'slug'        => 'one-click-demo-import',
			'required' 	  => true,
		)
	);

	$config = array(
		'id'           => 'sayara',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '', 
		'is_automatic' => false,
		'message'      => '',  
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'sayara_register_required_plugins' );

// One click demo import
function sayara_import_files() {
	return array(
		array(
			'import_file_name'             => __( 'Default', 'sayara' ),
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo/default/content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo/default/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'inc/demo/default/customizer.dat',
			'local_import_redux'           => array(
				array(
					'file_path'   => trailingslashit( get_template_directory() ) . 'inc/demo/redux.json',
					'option_name' => 'sayara_opt',
				),
			),
			'import_preview_image_url'     => get_template_directory_uri(). '/inc/demo/default/demo.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'sayara' ),
			'preview_url'                  => 'https://themebing.com/wp/sayara/',
		),
		array(
			'import_file_name'             => __( 'RTL', 'sayara' ),
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo/rtl/content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo/rtl/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'inc/demo/rtl/customizer.dat',
			'local_import_redux'           => array(
				array(
					'file_path'   => trailingslashit( get_template_directory() ) . 'inc/demo/redux.json',
					'option_name' => 'sayara_opt',
				),
			),
			'import_preview_image_url'     => get_template_directory_uri(). '/inc/demo/rtl/demo.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'sayara' ),
			'preview_url'                  => 'https://themebing.com/wp/sayara/rtl',
		)
	);
}

if (!empty(get_user_meta( get_current_user_id(), 'licence_activated' , true ))) {
	add_filter( 'pt-ocdi/import_files', 'sayara_import_files' );
}


// Default Home and Blog Setup
function sayara_after_import_setup() {
    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Primary', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
}
add_action( 'pt-ocdi/after_import', 'sayara_after_import_setup' );

// Related Posts
function sayara_related_posts(){

    global $sayara_opt;

    if (!empty($sayara_opt['related_posts']) && $sayara_opt['related_posts']!='') {
         $posts_per_page = !empty( $sayara_opt['posts_per_page'] ) ? $sayara_opt['posts_per_page'] : '';
         $related_posts_columns = !empty( $sayara_opt['related_posts_columns'] ) ? $sayara_opt['related_posts_columns'] : '';
         $related_post_title = !empty( $sayara_opt['related_post_title'] ) ? $sayara_opt['related_post_title'] : '';
        
        global $post;

        $related = get_posts( array( 
            'category__in' => wp_get_post_categories($post->ID),
            'posts_per_page' => $posts_per_page,
            'post_type' => 'post', 
            'post__not_in' => array($post->ID) 
        ) ); ?>

      <?php if ($related): ?>
        <div class="related-posts">
          <h4><?php echo esc_html( $related_post_title ) ?></h4>
          <div class="row">
              <?php
                  if( $related ) foreach( $related as $post ) { 
                  setup_postdata($post); ?>
                  <div class="col-md-12 col-xl-<?php echo esc_attr( $related_posts_columns ) ?>">
                      <div class="single-related-post">
                      <?php if ( has_post_thumbnail() ) : ?>
                          <a href="<?php the_permalink(); ?>"> 
                              <?php the_post_thumbnail('sayara-600x399');  ?> 
                          </a>
                      <?php endif; ?>

                          <div class="related-post-title">
                              <a href="<?php the_permalink(); ?>"><?php echo mb_strimwidth(get_the_title(), 0, 50, '...'); ?></a>
                              <span><?php the_time('F j, Y') ?></span>
                          </div>

                      </div>
                  </div>
              <?php } wp_reset_postdata(); ?> 
          </div>
      </div><!-- .related-posts --> 

      <?php endif ?>
    <?php } 
}


// Comment List
function sayara_comment_list($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'article' == $args['style'] ) {
		$tag = 'article';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'comment';
	}
?>

<<?php echo esc_html( $tag ) ?> <?php comment_class(empty( $args['has_children'] ) ? '' :'parent') ?> id="comment-<?php comment_ID() ?>" itemscope itemtype="http://schema.org/Comment">
	<div class="row">
		<?php
		$avatar = get_avatar( $comment, 90 );
		if ($avatar): ?>
			<div class="col-md-2 col-xs-3">
		        <?php echo get_avatar( $comment, 90 ); ?>
		    </div>
		<?php endif ?>	    
	    <div class="<?php if( $avatar =='' ){ echo 'col-md-12'; } else { echo'col-md-10 col-xs-9'; } ?>">
	        <div class="commenter">
	            <?php echo get_comment_author_link(); ?>
	            <span><?php comment_date('jS F Y , ').comment_time() ?></span>
	        </div>
	        <?php comment_text() ?>
	        <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>	        
	        <?php if ($comment->comment_approved == '0') : ?>
			<p class="comment-meta-item"><?php echo esc_html__( 'Your comment is awaiting moderation.', 'sayara' ) ?></p>
			<?php endif; ?>
			<?php edit_comment_link('<p class="comment-meta-item">Edit this comment</p>','',''); ?>
	    </div>
	</div>
<?php }


//Comment Field to Bottom
function sayara_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}
add_filter( 'comment_form_fields', 'sayara_comment_field_to_bottom' );

// Archive count on rightside
function sayara_archive_count_on_rightside($links) {
    $links = str_replace('</a>&nbsp;(', '</a> <span class="float-right">(', $links);
    $links = str_replace(')', ')</span>', $links);
    return $links;
}

add_filter( 'get_archives_link', 'sayara_archive_count_on_rightside' );