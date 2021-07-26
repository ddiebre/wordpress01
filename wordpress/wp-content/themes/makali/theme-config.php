<?php
/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */
if (!class_exists('makali_Theme_Config')) {
    class makali_Theme_Config {
        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;
        public function __construct() {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }
        public function initSettings() {
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();
            // Set the default arguments
            $this->setArguments();
            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();
            // Create the sections and fields
            $this->setSections();
            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }
            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }
        /**
          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field   set with compiler=>true is changed.
         * */
        function compiler_action($options, $css, $changed_values) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r($changed_values); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
        /**
          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.
          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons
         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => esc_html__('Section via hook', 'makali'),
                'desc' => esc_html__('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'makali'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );
            return $sections;
        }
        /**
          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;
            return $args;
        }
        /**
          Filter hook for filtering the default value of any given field. Very useful in development mode.
         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';
            return $defaults;
        }
        public function setSections() {
            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();
            ob_start();
            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';
            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'makali'), $this->theme->display('Name'));
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
                <?php if ($screenshot) : ?>
                    <?php if (current_user_can('edit_theme_options')) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                                <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'makali'); ?>" />
                            </a>
                    <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'makali'); ?>" />
                <?php endif; ?>
                <h4><?php echo ''.$this->theme->display('Name'); ?></h4>
                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'makali'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'makali'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' .__('Tags', 'makali') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo ''.$this->theme->display('Description'); ?></p>
                    <?php
                        if ($this->theme->parent()) {
                            printf(' <p class="howto">' .__('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'makali') . '</p>',__('http://codex.wordpress.org/Child_Themes', 'makali'), $this->theme->parent()->display('Name'));
                    } ?>
                </div>
            </div>
            <?php
            $item_info = ob_get_contents();
            ob_end_clean();
            $sampleHTML = '';
            // Layout
            $this->sections[] = array(
                'title'     => esc_html__('Layout', 'makali'),
                'desc'      => esc_html__('Select page layout: Box or Full Width', 'makali'),
                'icon'      => 'el-icon-align-justify',
                'fields'    => array(
                    array(
                        'id'       => 'page_layout',
                        'type'     => 'select',
                        'multi'    => false,
                        'title'    => esc_html__('Page Layout', 'makali'),
                        'options'  => array(
                            'full'     => 'Full Width',
                            'box'      => 'Box',
                            'box_body' => 'Box Body',
                        ),
                        'default'  => 'full'
                    ),
                    array(
                        'id'            => 'box_layout_width',
                        'type'          => 'slider',
                        'title'         => esc_html__('Box layout width', 'makali'),
                        'desc'          => esc_html__('Box layout width in pixels, default value: 1230', 'makali'),
                        "default"       => 1230,
                        "min"           => 960,
                        "step"          => 1,
                        "max"           => 1920,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'box_layout_width_body',
                        'type'          => 'slider',
                        'title'         => esc_html__('Box layout main content and footer', 'makali'),
                        'desc'          => esc_html__('Box layout width in pixels, default value: 1250', 'makali'),
                        "default"       => 1250,
                        "min"           => 960,
                        "step"          => 1,
                        "max"           => 1920,
                        'display_value' => 'text'
                    ),
                ),
            );
            // General
            $this->sections[] = array(
                'title'     => esc_html__('General', 'makali'),
                'desc'      => esc_html__('General theme options', 'makali'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
                    array(
                        'id'        => 'background_opt',
                        'type'      => 'background',
                        'output'    => array('body'),
                        'title'     => esc_html__('Body background', 'makali'),
                        'subtitle'  => esc_html__('Upload image or select color. Only work with box layout', 'makali'),
                        'default'   => array('background-color' => '#e8e8e8'),
                    ),
                    array(
                        'id'        => 'background_boxbody_opt',
                        'type'      => 'background',
                        'output'    => array('.box-body .page-wrapper'),
                        'title'     => esc_html__('Body background (box body)', 'makali'),
                        'subtitle'  => esc_html__('Upload image or select color. Only work with box body layout', 'makali'),
                        'default'   => array('background-color' => '#f4f4f4'),
                    ),
                    array(
                        'id'        => 'page_content_background',
                        'type'      => 'background',
                        'title'     => esc_html__('Page content background', 'makali'),
                        'subtitle'  => esc_html__('Select background for page content (default: #ffffff).', 'makali'),
                        'default'   => array('background-color' => '#ffffff'),
                    ),
                    array(
                        'id'        => 'page_content_background_boxbody',
                        'type'      => 'background',
                        'output'    => array('.box-body-inner'),
                        'title'     => esc_html__('Page content background (box body)', 'makali'),
                        'subtitle'  => esc_html__('Select background for page content (default: #ffffff). Only work with box body layout', 'makali'),
                        'default'   => array('background-color' => '#ffffff'),
                    ),
                    array( 
                        'id'       => 'border_color',
                        'type'     => 'border',
                        'title'    => esc_html__('Border Option', 'makali'),
                        'subtitle' => esc_html__('Only color validation can be done on this field type', 'makali'),
                        'default'  => array('border-color' => '#e0e0e0'),
                    ), 
                    array(
                        'id'        => 'back_to_top',
                        'type'      => 'switch',
                        'title'     => esc_html__('Back To Top', 'makali'),
                        'desc'      => esc_html__('Show back to top button on all pages', 'makali'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'row_space',
                        'type'      => 'text',
                        'title'     => esc_html__('Row space', 'makali'),
                        'desc'      => esc_html__('Space between row, default value: 95px', 'makali'),
                        "default"   => '95px',
                        'display_value' => 'text',
                    ),
					array(
                        'id'        => 'row_container',
                        'type'      => 'text',
                        'title'     => esc_html__('Width Container', 'makali'),
                        'desc'      => esc_html__('Width of container.', 'makali'),
                        'default'   => '1200px',
                        'display_value' => 'text',
                    ),
                ),
            );
            // Colors
            $this->sections[] = array(
                'title'     => esc_html__('Colors', 'makali'),
                'desc'      => esc_html__('Color options', 'makali'),
                'icon'      => 'el-icon-tint',
                'fields'    => array(
                    array(
                        'id'        => 'primary_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Primary Color', 'makali'),
                        'subtitle'  => esc_html__('Pick a color for primary color (default: #c1b17e).', 'makali'),
                        'transparent' => false,
                        'default'   => '#c1b17e',
                        'validate'  => 'color',
                    ),
					array(
                        'id'        => 'menu_hover_itemlevel1_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Hover Color for Item Menu', 'makali'),
                        'subtitle'  => esc_html__('Pick a color for hover/active color of item level 1 (Horizontal Menu).', 'makali'),
                        'transparent' => false,
                        'default'   => '#c1b17e',
                        'validate'  => 'color',
                    ),
                    array(
                        'id'        => 'sale_color',
                        'type'      => 'color',
                        //'output'    => array(),
                        'title'     => esc_html__('Sale Label BG Color', 'makali'),
                        'subtitle'  => esc_html__('Pick a color for bg sale label (default: #c1b17e).', 'makali'),
                        'transparent' => true,
                        'default'   => '#c1b17e',
                        'validate'  => 'color',
                    ),
                    array(
                        'id'          => 'saletext_color',
                        'type'        => 'color',
                        //'output'    => array(),
                        'title'       => esc_html__('Sale Label Text Color', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for sale label text (default: #ffffff).', 'makali'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'rate_color',
                        'type'        => 'color',
                        //'output'    => array(),
                        'title'       => esc_html__('Rating Star Color', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for star of rating (default: #c1b17e).', 'makali'),
                        'transparent' => false,
                        'default'     => '#c1b17e',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'link_color',
                        'type'        => 'link_color',
                        //'output'    => array('a'),
                        'title'       => esc_html__('Link Color', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for link (default: #323232).', 'makali'),
                        'default'     => array(
                            'regular'  => '#323232',
                            'hover'    => '#c1b17e',
                            'active'   => '#c1b17e',
                            'visited'  => '#c1b17e',
                        )
                    ),
                    array(
                        'id'          => 'text_selected_bg',
                        'type'        => 'color',
                        'title'       => esc_html__('Text selected background', 'makali'),
                        'subtitle'    => esc_html__('Select background for selected text (default: #91b2c3).', 'makali'),
                        'transparent' => false,
                        'default'     => '#91b2c3',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'text_selected_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Text selected color', 'makali'),
                        'subtitle'    => esc_html__('Select color for selected text (default: #ffffff).', 'makali'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                ),
            );
            //Header
            $header_layouts = array();
			$header_default = '';
			
			$header_mobile_layouts = array();
			$header_mobile_default = '';
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
                    $header_layouts[$jscomposer_template->post_title] = $jscomposer_template->post_title;
                    $header_mobile_layouts[$jscomposer_template->post_title] = $jscomposer_template->post_title;
                }
                // $header_default = $jscomposer_templates[0]->post_title;
                $header_default = esc_html__('Header 1', 'makali');
                $header_mobile_default = esc_html__('Header Mobile 1', 'makali');
            }
            $this->sections[] = array(
                'title'     => esc_html__('Header', 'makali'),
                'desc'      => esc_html__('Header options', 'makali'),
                'icon'      => 'el-icon-tasks',
                'fields'    => array(
                    array(
                        'id'                => 'header_layout',
                        'type'              => 'select',
                        'title'             => esc_html__('Header Layout', 'makali'),
                        'customizer_only'   => false,
                        'desc'              => esc_html__('Go to Visual Composer => Templates to create/edit layout', 'makali'),
                        //Must provide key   => value pairs for select options
                        'options'           => $header_layouts,
                        'default'           => $header_default
                    ),
					array(
                        'id'        => 'header_mobile_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Header Mobile Layout', 'makali'),
                        'customizer_only'   => false,
                        'desc'      => esc_html__('Go to WPBakery Page Builder => Templates to create/edit layout', 'makali'),
                        //Must provide key => value pairs for select options
                        'options'   => $header_mobile_layouts,
                        'default'   => $header_mobile_default,
                    ),
                    array(
                        'id'        => 'header_bg',
                        'type'      => 'background',
                        'output'    => array(), 
                        'title'     => esc_html__('Header background', 'makali'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'makali'), 
                        'default'   => array('background-color' => '#ffffff'),
                    ),
                    array(
                        'id'          => 'header_color',
                        'type'        => 'color',
                        'output'      => array('.header'),
                        'title'       => esc_html__('Header text color', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for header color (default: #767676).', 'makali'),
                        'transparent' => false,
                        'default'     => '#767676',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'        => 'header_link_color',
                        'type'      => 'link_color',
                        'title'     => esc_html__('Header link color', 'makali'),
                        'subtitle'  => esc_html__('Pick a color for header link color (default: #767676).', 'makali'),
                        'default'   => array(
                            'regular'  => '#767676',
                            'hover'    => '#c1b17e',
                            'active'   => '#c1b17e',
                            'visited'  => '#c1b17e',
                        )
                    ),
                    array(
                        'id'          => 'dropdown_bg',
                        'type'        => 'color',
                        //'output'    => array(),
                        'title'       => esc_html__('Dropdown menu background', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for dropdown menu background (default: #ffffff).', 'makali'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    )
                ),
            );
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Sticky header', 'makali' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'        => 'sticky_header',
                        'type'      => 'switch',
                        'title'     => esc_html__('Use sticky header', 'makali'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'header_sticky_bg',
                        'type'      => 'color_rgba',
                        'title'     => esc_html__('Header sticky background', 'makali'),
                        'subtitle'  => 'Set color and alpha channel',
                        'output'    => array('background-color' => '.header-sticky.ontop'),
                        'default'   => array(
                            'color'     => '#ffffff',
                            'alpha'     => 0.95,
                        ),
                        'options'       => array(
                            'show_input'                => true,
                            'show_initial'              => true,
                            'show_alpha'                => true,
                            'show_palette'              => true,
                            'show_palette_only'         => false,
                            'show_selection_palette'    => true,
                            'max_palette_size'          => 10,
                            'allow_empty'               => true,
                            'clickout_fires_change'     => false,
                            'choose_text'               => 'Choose',
                            'cancel_text'               => 'Cancel',
                            'show_buttons'              => true,
                            'use_extended_classes'      => true,
                            'palette'                   => null,
                            'input_text'                => 'Select Color'
                        ),                        
                    ),
                )
            );
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Top Bar', 'makali' ),
                'subsection' => true,
                'fields'     => array(
					array(
                        'id'        => 'topbar_bg',
                        'type'      => 'background',
                        'output'    => array(), 
                        'title'     => esc_html__('Topbar background', 'makali'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'makali'), 
                        'default'   => array('background-color' => '#ffffff'),
                    ),
                    array(
                        'id'          => 'topbar_color',
                        'type'        => 'color',
                        'output'      => array('.top-bar'),
                        'title'       => esc_html__('Top bar text color', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for top bar text color (default: #767676).', 'makali'),
                        'transparent' => false,
                        'default'     => '#767676',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'        => 'topbar_link_color',
                        'type'      => 'link_color',
                        'output'    => array('.top-bar a'),
                        'title'     => esc_html__('Top bar link color', 'makali'),
                        'subtitle'  => esc_html__('Pick a color for top bar link color (default: #767676).', 'makali'),
                        'default'   => array(
                            'regular'  => '#767676',
                            'hover'    => '#c1b17e',
                            'active'   => '#c1b17e',
                            'visited'  => '#c1b17e',
                        )
                    ), 
                )
            );
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Menu', 'makali' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'        => 'mobile_menu_label',
                        'type'      => 'text',
                        'title'     => esc_html__('Mobile menu label', 'makali'),
                        'subtitle'  => esc_html__('The label for mobile menu (example: Menu, Go to...', 'makali'),
                        'default'   => 'Menu'
                    ), 
                    array(
                        'id'          => 'sub_menu_bg',
                        'type'        => 'color',
                        //'output'    => array(),
                        'title'       => esc_html__('Submenu background', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for sub menu bg (default: #ffffff).', 'makali'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                )
            );
			$this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Notification', 'makali' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'          => 'notification_bg',
                        'type'        => 'color',
                        'title'       => esc_html__('Background of Notification', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for background of notification', 'makali'),
                        'transparent' => false,
                        'default'     => '#323232',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'notification_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Notification text color', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for notification color.', 'makali'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    )
                )
            ); 
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Categories Menu', 'makali' ),
                'fields'     => array(
                    array(
                        'id'          => 'categories_menu_bg',
                        'type'        => 'color',
                        //'output'    => array(),
                        'title'       => esc_html__('Category menu background', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for category menu background (default: #ffffff).', 'makali'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'categories_sub_menu_bg',
                        'type'        => 'color',
                        //'output'    => array(),
                        'title'       => esc_html__('Sub category menu background', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for category sub menu background (default: #ffffff).', 'makali'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'        => 'categories_menu_label',
                        'type'      => 'text',
                        'title'     => esc_html__('Category menu label', 'makali'),
                        'subtitle'  => esc_html__('The label for category menu', 'makali'),
                        'default'   => 'ALL CATEGORIES'
                    ),
                    array(
                        'id'            => 'categories_menu_items',
                        'type'          => 'slider',
                        'title'         => esc_html__('Number of items', 'makali'),
                        'desc'          => esc_html__('Number of menu items level 1 to show, default value: 9', 'makali'),
                        "default"       => 9,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 30,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'        => 'categories_more_label',
                        'type'      => 'text',
                        'title'     => esc_html__('More items label', 'makali'),
                        'subtitle'  => esc_html__('The label for more items button', 'makali'),
                        'default'   => 'More Categories'
                    ),
                    array(
                        'id'        => 'categories_less_label',
                        'type'      => 'text',
                        'title'     => esc_html__('Less items label', 'makali'),
                        'subtitle'  => esc_html__('The label for less items button', 'makali'),
                        'default'   => 'Less Categories'
                    ),
                    array(
                        'id'        => 'categories_menu_home',
                        'type'      => 'switch',
                        'title'     => esc_html__('Home Category Menu', 'makali'),
                        'subtitle'  => esc_html__('Always show category menu on home page', 'makali'),
                        'default'   => false,
                    ),
                )
            );
            //Footer
            $footer_layouts = array();
            $footer_default = '';
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
                    $footer_layouts[$jscomposer_template->post_title] = $jscomposer_template->post_title;
                }
                // $footer_default = $jscomposer_templates[0]->post_title;
                $footer_default = 'Footer 1';
            }
            $this->sections[] = array(
                'title'     => esc_html__('Footer', 'makali'),
                'desc'      => esc_html__('Footer options', 'makali'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
                    array(
                        'id'                => 'footer_layout',
                        'type'              => 'select',
                        'title'             => esc_html__('Footer Layout', 'makali'),
                        'customizer_only'   => false,
                        'desc'              => esc_html__('Go to Visual Composer => Templates to create/edit layout', 'makali'),
                        //Must provide key  => value pairs for select options
                        'options'           => $footer_layouts,
                        'default'           => $footer_default
                    ),
                    array(
                        'id'        => 'footer_bg',
                        'type'      => 'background',
                        'output'    => array(), 
                        'title'     => esc_html__('Footer background', 'makali'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'makali'), 
                        'default'   => array('background-color' => '#ffffff'),
                    ), 
                    array(
                        'id'          => 'footer_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Footer text color', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for footer color (default: #777777).', 'makali'),
                        'transparent' => false,
                        'default'     => '#777777',
                        'validate'    => 'color',
                    ),
					array(
                        'id'          => 'footer_title_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Footer title color', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for footer title color (default: #323232).', 'makali'),
                        'transparent' => false,
                        'default'     => '#323232',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'        => 'footer_link_color',
                        'type'      => 'link_color',
                        'title'     => esc_html__('Footer link color', 'makali'),
                        'subtitle'  => esc_html__('Pick a color for footer link color (default: #777777).', 'makali'),
                        'default'   => array(
                            'regular'  => '#777777',
                            'hover'    => '#c1b17e',
                            'active'   => '#c1b17e',
                            'visited'  => '#c1b17e',
                        )
                    ),
					array(
                        'id'          => 'bg_btn_mc4wp',
                        'type'        => 'color',
                        'title'       => esc_html__('Button Newsletter Background', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for button', 'makali'),
                        'transparent' => false,
						'default'     => '#c1b17e',
                        'validate'    => 'color',
                    ),
					array(
                        'id'          => 'bg_h_btn_mc4wp',
                        'type'        => 'color',
                        'title'       => esc_html__('Button Newsletter Background on hover', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for background on hover', 'makali'),
                        'transparent' => false,
                        'default'     => '#323232',
                        'validate'    => 'color',
                    ),
					array(
                        'id'          => 'color_btn_mc4wp',
                        'type'        => 'color',
                        'title'       => esc_html__('Button Newsletter Color', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for button', 'makali'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
					array(
                        'id'          => 'color_h_btn_mc4wp',
                        'type'        => 'color',
                        'title'       => esc_html__('Button Newsletter Color on hover', 'makali'),
                        'subtitle'    => esc_html__('Pick a color for background on hover', 'makali'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                ),
            );
            $this->sections[] = array(
                'title'     => esc_html__('Social Icons', 'makali'),
                'icon'      => 'el-icon-website',
                'fields'     => array(
                    array(
                        'id'       => 'social_icons',
                        'type'     => 'sortable',
                        'title'    => esc_html__('Social Icons', 'makali'),
                        'subtitle' => esc_html__('Enter social links', 'makali'),
                        'desc'     => esc_html__('Drag/drop to re-arrange', 'makali'),
                        'mode'     => 'text',
                        'label'    => true,
                        'options'  => array(
                            'facebook'     => 'Facebook',
                            'twitter'      => 'Twitter',
                            'instagram'    => 'Instagram',
                            'tumblr'       => 'Tumblr',
                            'pinterest'    => 'Pinterest',
                            'google-plus'  => 'Google+',
                            'linkedin'     => 'LinkedIn',
                            'behance'      => 'Behance',
                            'dribbble'     => 'Dribbble',
                            'youtube'      => 'Youtube',
                            'vimeo'        => 'Vimeo',
                            'rss'          => 'Rss',
                        ),
                        'default' => array(
                            'facebook'    => 'https://www.facebook.com',
                            'twitter'     => 'https://twitter.com',
                            'instagram'   => 'https://www.instagram.com',
                            'tumblr'      => '',
                            'pinterest'   => '',
                            'google-plus' => '',
                            'linkedin'    => 'https://www.linkedin.com',
                            'behance'     => '',
                            'dribbble'    => '',
                            'youtube'     => '',
                            'vimeo'       => '',
                            'rss'         => 'https://www.rss.com',
                        ),
                    ),
                )
            ); 
            //Fonts
            $this->sections[] = array(
                'title'     => esc_html__('Fonts', 'makali'),
                'desc'      => esc_html__('Fonts options', 'makali'),
                'icon'      => 'el-icon-font',
                'fields'    => array(
                    array(
                        'id'              => 'bodyfont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Body font', 'makali'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => true,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'text-align'      => false,
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'      => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('body'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Main body font.', 'makali'),
                        'default'         => array(
                            'color'         => '#666666',
                            'font-weight'   => '400',
                            'font-family'   => 'Libre Franklin',
                            'google'        => true,
                            'font-size'     => '14px',
                            'line-height'   => '25px'
                        ),
                    ),
                    array(
                        'id'              => 'headingfont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Heading font', 'makali'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => false,
                        'line-height'     => false,
                        'text-align'      => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'      => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Heading font.', 'makali'),
                        'default'         => array(
                            'color'         => '#323232',
                            'font-weight'   => '600',
                            'font-family'   => 'Libre Franklin',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'menufont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Menu font', 'makali'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'      => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Menu font.', 'makali'),
                        'default'         => array(
                            'color'         => '#323232',
                            'font-weight'   => '500',
                            'font-family'   => 'Libre Franklin',
                            'font-size'     => '14px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'submenufont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Sub menu font', 'makali'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'      => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('sub menu font.', 'makali'),
                        'default'         => array(
                            'color'         => '#777777',
                            'font-weight'   => '300',
                            'font-family'   => 'Libre Franklin',
                            'font-size'     => '13px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'dropdownfont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Dropdown menu font', 'makali'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'      => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Dropdown menu font.', 'makali'),
                        'default'         => array(
                            'color'         => '#666666',
                            'font-weight'   => '400',
                            'font-family'   => 'Libre Franklin',
                            'font-size'     => '13px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'categoriesfont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Category menu font', 'makali'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'      => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Category menu font.', 'makali'),
                        'default'         => array(
                            'color'         => '#666666',
                            'font-weight'   => '400',
                            'font-family'   => 'Libre Franklin',
                            'font-size'     => '13px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'categoriessubmenufont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Category sub menu font', 'makali'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'      => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Category sub menu font.', 'makali'),
                        'default'         => array(
                            'color'         => '#333333',
                            'font-weight'   => '400',
                            'font-family'   => 'Libre Franklin',
                            'font-size'     => '13px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'pricefont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Price font', 'makali'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'      => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Price font.', 'makali'),
                        'default'         => array(
                            'color'         => '#323232',
                            'font-weight'   => '500',
                            'font-family'   => 'Libre Franklin', 
                            'font-size'     => '14px', 
                            'google'        => true,
                        ),
                    ),
                ),
            );
            //Image slider
            $this->sections[] = array(
                'title'     => esc_html__('Image slider', 'makali'),
                'desc'      => esc_html__('Upload images and links', 'makali'),
                'icon'      => 'el-icon-website',
                'fields'    => array(
                    array(
                        'id'          => 'image_slider',
                        'type'        => 'slides',
                        'title'       => esc_html__('Images', 'makali'),
                        'desc'        => esc_html__('Upload images and enter links.', 'makali'),
                        'placeholder' => array(
                            'title'           => esc_html__('Title', 'makali'),
                            'description'     => esc_html__('Description', 'makali'),
                            'url'             => esc_html__('Link', 'makali'),
                        ),
                    ),
                ),
            );
            //Brand logos
            $this->sections[] = array(
                'title'     => esc_html__('Brand Logos', 'makali'),
                'desc'      => esc_html__('Upload brand logos and links', 'makali'),
                'icon'      => 'el-icon-briefcase',
                'fields'    => array(
                    array(
                        'id'          => 'brand_logos',
                        'type'        => 'slides',
                        'title'       => esc_html__('Logos', 'makali'),
                        'desc'        => esc_html__('Upload logo image and enter logo link.', 'makali'),
                        'placeholder' => array(
                            'title'           => esc_html__('Title', 'makali'),
                            'description'     => esc_html__('Description', 'makali'),
                            'url'             => esc_html__('Link', 'makali'),
                        ),
                    ),
                ),
            );
            //Inner brand logos
            $this->sections[] = array(
                'title'     => esc_html__('Inner Brand Logos', 'makali'),
                'subsection'=> true,
                'icon'      => 'el-icon-website',
                'fields'    => array(
                    array(
                        'id'        => 'inner_brand',
                        'type'      => 'switch',
                        'title'     => esc_html__('Brand carousel in inner pages', 'makali'),
                        'subtitle'  => esc_html__('Show brand carousel in inner pages', 'makali'),
                        'default'   => false,
                    ),
                    array(
                        'id'       => 'brandscroll',
                        'type'     => 'switch',
                        'title'    => esc_html__('Auto scroll', 'makali'),
                        'default'  => true,
                    ),
                    array(
                        'id'            => 'brandscrollnumber',
                        'type'          => 'slider',
                        'title'         => esc_html__('Scroll amount', 'makali'),
                        'desc'          => esc_html__('Number of logos to scroll one time, default value: 1', 'makali'),
                        "default"       => 1,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 12,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'brandpause',
                        'type'          => 'slider',
                        'title'         => esc_html__('Pause in (seconds)', 'makali'),
                        'desc'          => esc_html__('Pause time, default value: 3000', 'makali'),
                        "default"       => 3000,
                        "min"           => 1000,
                        "step"          => 500,
                        "max"           => 10000,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'brandanimate',
                        'type'          => 'slider',
                        'title'         => esc_html__('Animate in (seconds)', 'makali'),
                        'desc'          => esc_html__('Animate time, default value: 2000', 'makali'),
                        "default"       => 2000,
                        "min"           => 300,
                        "step"          => 100,
                        "max"           => 5000,
                        'display_value' => 'text'
                    ),
                ),
            );
            // Sidebar
            $this->sections[] = array(
                'title'     => esc_html__('Sidebar', 'makali'),
                'desc'      => esc_html__('Sidebar options', 'makali'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
                    array(
                        'id'       => 'sidebarshop_pos',
                        'type'     => 'radio',
                        'title'    => esc_html__('Shop Sidebar Position', 'makali'),
                        'subtitle' => esc_html__('Sidebar on shop page', 'makali'),
                        'options'  => array(
                            'left' => 'Left',
                            'right'=> 'Right'),
                        'default'  => 'left'
                    ),
                    array(
                        'id'       => 'sidebarsingleproduct_pos',
                        'type'     => 'radio',
                        'title'    => esc_html__('Single Product Sidebar Position', 'makali'),
                        'subtitle' => esc_html__('Sidebar on single product page', 'makali'),
                        'options'  => array(
                            'left' => 'Left',
                            'right'=> 'Right'),
                        'default'  => 'left'
                    ),
                    array(
                        'id'       => 'sidebarblog_pos',
                        'type'     => 'radio',
                        'title'    => esc_html__('Blog Sidebar Position', 'makali'),
                        'subtitle' => esc_html__('Sidebar on Blog pages', 'makali'),
                        'options'  => array(
                            'left' => 'Left',
                            'right'=> 'Right'),
                        'default'  => 'right'
                    ),
                    array(
                        'id'       => 'sidebarse_pos',
                        'type'     => 'radio',
                        'title'    => esc_html__('Inner Pages Sidebar Position', 'makali'),
                        'subtitle' => esc_html__('Sidebar on pages (default pages)', 'makali'),
                        'options'  => array(
                            'left' => 'Left',
                            'right'=> 'Right'),
                        'default'  => 'left'
                    ),
                    array(
                        'id'       =>'custom-sidebars',
                        'type'     => 'multi_text',
                        'title'    => esc_html__('Custom Sidebars', 'makali'),
                        'subtitle' => esc_html__('Add more sidebars', 'makali'),
                        'desc'     => esc_html__('Enter sidebar name (Only allow digits and letters). click Add more to add more sidebar. Edit your page to select a sidebar ', 'makali')
                    ),
                ),
            );
            // Product
            $this->sections[] = array(
                'title'     => esc_html__('Product', 'makali'),
                'desc'      => esc_html__('Use this section to select options for product', 'makali'),
                'icon'      => 'el-icon-tags',
                'fields'    => array(
                    array(
                        'id'        => 'shop_banner',
                        'type'      => 'media',
                        'title'     => esc_html__('Banner image in shop pages', 'makali'),
                        'compiler'  => 'true',
                        'mode'      => false,
                        'desc'      => esc_html__('Upload image here.', 'makali'),
                    ),
                    array(
                        'id'        => 'show_category_image',
                        'type'      => 'switch',
                        'title'     => esc_html__('Show individual category thumnail', 'makali'),
                        'desc'      => esc_html__('Show individual category thumnail in product category pages', 'makali'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'shop_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Shop Layout', 'makali'),
                        'options'   => array(
                            'sidebar'   => 'Sidebar',
                            'fullwidth' => 'Full Width',
                        ),
                        'default'   => 'sidebar',
                    ),
                    array(
                        'id'        => 'default_view',
                        'type'      => 'select',
                        'title'     => esc_html__('Shop default view', 'makali'),
                        'default'   => 'grid-view',
                        'options'   => array(
                            'grid-view' => 'Grid View',
                            'list-view' => 'List View',
                        ),
                    ),
                    array(
                        'id'            => 'product_per_page',
                        'type'          => 'slider',
                        'title'         => esc_html__('Products per page', 'makali'),
                        'subtitle'      => esc_html__('Amount of products per page on category page', 'makali'),
                        "default"       => 12,
                        "min"           => 4,
                        "step"          => 1,
                        "max"           => 48,
                        'display_value' => 'text',
                    ),
                    array(
                        'id'            => 'product_per_row',
                        'type'          => 'slider',
                        'title'         => esc_html__('Product columns', 'makali'),
                        'subtitle'      => esc_html__('Amount of product columns on category page', 'makali'),
                        'desc'          => esc_html__('Only works with: 1, 2, 3, 4, 6', 'makali'),
                        "default"       => 3,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 6,
                        'display_value' => 'text',
                    ),
                    array(
                        'id'            => 'product_per_row_fw',
                        'type'          => 'slider',
                        'title'         => esc_html__('Product columns on full width shop', 'makali'),
                        'subtitle'      => esc_html__('Amount of product columns on full width category page', 'makali'),
                        'desc'          => esc_html__('Only works with: 1, 2, 3, 4, 6', 'makali'),
                        "default"       => 4,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 6,
                        'display_value' => 'text',
                    ),
                ),
            );
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Product page', 'makali' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'        => 'single_product_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Single Product Layout', 'makali'),
                        'default'   => 'fullwidth',
                        'options'   => array(
                            'sidebar'   => 'Sidebar',
                            'fullwidth' => 'Full Width',
                        ),
                    ),
                    array(
                        'id'        => 'product_banner',
                        'type'      => 'media',
                        'title'     => esc_html__('Banner image for single product pages', 'makali'),
                        'compiler'  => 'true',
                        'mode'      => false,
                        'desc'      => esc_html__('Upload image here.', 'makali'),
                    ),
                    array(
                        'id'        => 'related_product_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Related product title', 'makali'),
                        'default'   => 'Related Products',
                    ),
                    array(
                        'id'        => 'upsell_product_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Upsell product title', 'makali'),
                        'default'   => 'Upsell Products',
                    ),
                    array(
                        'id'            => 'related_amount',
                        'type'          => 'slider',
                        'title'         => esc_html__('Number of related products', 'makali'),
                        "default"       => 6,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 16,
                        'display_value' => 'text',
                    ),
                    array(
                        'id'        => 'product_share_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Product share title', 'makali'),
                        'default'   => 'Share this product',
                    ),
                )
            );
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Quick View', 'makali' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'        => 'detail_link_text',
                        'type'      => 'text',
                        'title'     => esc_html__('View details text', 'makali'),
                        'default'   => 'Quick View'
                    ),
                    array(
                        'id'        => 'quickview_link_text',
                        'type'      => 'text',
                        'title'     => esc_html__('View all features text', 'makali'),
                        'desc'      => esc_html__('This is the text on quick view box', 'makali'),
                        'default'   => 'See all features'
                    ),
                    array(
                        'id'        => 'quickview',
                        'type'      => 'switch',
                        'title'     => esc_html__('Quick View', 'makali'),
                        'desc'      => esc_html__('Show quick view button on all pages', 'makali'),
                        'default'   => true,
                    ),
                )
            );
            // Blog options
            $this->sections[] = array(
                'title'     => esc_html__('Blog', 'makali'),
                'desc'      => esc_html__('Use this section to select options for blog', 'makali'),
                'icon'      => 'el-icon-file',
                'fields'    => array( 
                    array(
                        'id'        => 'blog_header_text',
                        'type'      => 'text',
                        'title'     => esc_html__('Blog header text', 'makali'),
                        'default'   => 'Blog'
                    ), 
                    array(
                        'id'        => 'blog_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Blog Layout', 'makali'),
                        'options'   => array(
                            'sidebar'       => 'Sidebar',
                            'nosidebar'     => 'No Sidebar',
                            'largeimage'    => 'Large Image',
                            'grid'          => 'Grid',
                        ),
                        'default'   => 'sidebar'
                    ),
                    array(
                        'id'        => 'readmore_text',
                        'type'      => 'text',
                        'title'     => esc_html__('Read more text', 'makali'),
                        'default'   => '+ Read More'
                    ),
                    array(
                        'id'            => 'excerpt_length',
                        'type'          => 'slider',
                        'title'         => esc_html__('Excerpt length on blog page', 'makali'),
                        "default"       => 40,
                        "min"           => 10,
                        "step"          => 1,
                        "max"           => 120,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'        => 'blog_share_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Blog share title', 'makali'),
                        'default'   => 'Share this post',
                    ),
                ),
            );
            // Testimonials options
            $this->sections[] = array(
                'title'     => esc_html__('Testimonials', 'makali'),
                'desc'      => esc_html__('Use this section to select options for Testimonials', 'makali'),
                'icon'      => 'el-icon-comment',
                'fields'    => array(
                    array(
                        'id'       => 'testiscroll',
                        'type'     => 'switch',
                        'title'    => esc_html__('Auto scroll', 'makali'),
                        'default'  => false,
                    ),
                    array(
                        'id'            => 'testipause',
                        'type'          => 'slider',
                        'title'         => esc_html__('Pause in (seconds)', 'makali'),
                        'desc'          => esc_html__('Pause time, default value: 3000', 'makali'),
                        "default"       => 3000,
                        "min"           => 1000,
                        "step"          => 500,
                        "max"           => 10000,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'testianimate',
                        'type'          => 'slider',
                        'title'         => esc_html__('Animate in (seconds)', 'makali'),
                        'desc'          => esc_html__('Animate time, default value: 2000', 'makali'),
                        "default"       => 2000,
                        "min"           => 300,
                        "step"          => 100,
                        "max"           => 5000,
                        'display_value' => 'text'
                    ),
                ),
            );
            // Error 404 page
            $this->sections[] = array(
                'title'     => esc_html__('Error 404 Page', 'makali'),
                'desc'      => esc_html__('Error 404 page options', 'makali'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
                    array(
                        'id'        => 'background_error',
                        'type'      => 'background',
                        'output'    => array('body.error404'),
                        'title'     => esc_html__('Error 404 background', 'makali'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'makali'),
                        'default'   => array('background-color' => '#f2f2f2'),
                    ),
                ),
            );
            // Less Compiler
            $this->sections[] = array(
                'title'     => esc_html__('Less Compiler', 'makali'),
                'desc'      => esc_html__('Turn on this option to apply all theme options. Turn of when you have finished changing theme options and your site is ready.', 'makali'),
                'icon'      => 'el-icon-wrench',
                'fields'    => array(
                    array(
                        'id'        => 'enable_less',
                        'type'      => 'switch',
                        'title'     => esc_html__('Enable Less Compiler', 'makali'),
                        'default'   => true,
                    ),
                ),
            );
            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . esc_html__('<strong>Theme URL:</strong> ', 'makali') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . esc_html__('<strong>Author:</strong> ', 'makali') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . esc_html__('<strong>Version:</strong> ', 'makali') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . esc_html__('<strong>Tags:</strong> ', 'makali') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';
            $this->sections[] = array(
                'title'     => esc_html__('Import / Export', 'makali'),
                'desc'      => esc_html__('Import and Export your Redux Framework settings from file, text or URL.', 'makali'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );
            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => esc_html__('Theme Information', 'makali'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );
        }
        public function setHelpTabs() {
            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => esc_html__('Theme Information 1', 'makali'),
                'content'   => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'makali')
            );
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => esc_html__('Theme Information 2', 'makali'),
                'content'   => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'makali')
            );
            // Set the help sidebar
            $this->args['help_sidebar'] = esc_html__('<p>This is the sidebar content, HTML is allowed.</p>', 'makali');
        }
        /**
          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */
        public function setArguments() {
            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'makali_opt',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => esc_html__('Theme Options', 'makali'),
                'page_title'        => esc_html__('Theme Options', 'makali'),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key'    => '', // Must be defined to add google fonts to the typography module
                'async_typography'  => true,                    // Use a asynchronous font on the front end or font string
                //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                'admin_bar'         => false,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'           => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'        => false, // REMOVE
                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );
            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/reduxframework',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://www.linkedin.com/company/redux-framework',
                'title' => 'Find us on LinkedIn',
                'icon'  => 'el-icon-linkedin'
            );
            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
              } else {
            }
        }
    }
    global $reduxConfig;
    $reduxConfig = new makali_Theme_Config();
}
/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;
/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';
        /*
          do your validation
          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */
        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;