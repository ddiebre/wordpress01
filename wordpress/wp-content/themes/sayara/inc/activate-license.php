<?php
// Create a Menu for Activate License
function sayara_admin_menu() {
    if (is_admin()) {
        add_menu_page( esc_html__( 'Activate License', 'sayara' ),  esc_html__( 'Activate License', 'sayara' ), 'manage_options', 'activate-license','sayara_activate_license_page_content', 'dashicons-admin-network', 10 );
    }
}
add_action('admin_menu','sayara_admin_menu' );

// Settings Page for Activate License ( callback function )
function sayara_activate_license_page_content() { ?>
    <div class="wrap">               
    	<h1><?php echo esc_html__( 'Activate License', 'sayara' ) ?></h1>

		<div class="update-nag">
			<?php echo esc_html__('Please visit the', 'sayara' ); ?>
			<a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"><?php echo esc_html__('Where Is My Purchase Code?', 'sayara' ); ?></a>
			<?php echo esc_html__('documentation page for full setup instructions.', 'sayara' ); ?>
		</div>

        <form action='options.php' method='post'>
	        <?php
	        settings_fields('sayara_activate_license_group');
	        do_settings_sections('sayara_activate_license_section');
	        submit_button();
	        ?>
        </form>		
    </div>
<?php }

// License input fields init
function sayara_activate_license_init() {

	register_setting( 'sayara_activate_license_group', 'sayara_activate_license_option', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => NULL,
    ) );

	add_settings_section( 'sayara_activate_license_section', esc_html__( 'License Settings', 'sayara' ), 'sayara_activate_license_section_callback' , 'sayara_activate_license_section' );

	function sayara_activate_license_section_callback() {
	 	echo '<p>'.esc_html__('Please enter your purchase code' , 'sayara').'</p>';
	}

	add_settings_field( 'sayara_activate_license_input_field', esc_html__('Purchase Code', 'sayara'), 'sayara_activate_license_setting_callback' , 'sayara_activate_license_section', 'sayara_activate_license_section' );

	function sayara_activate_license_setting_callback() { ?>

	    <input type='text' class="regular-text" name="sayara_activate_license_option" value="<?php echo get_option('sayara_activate_license_option') ?>">

	    <p class="description">
	    	<?php

	    	$sayara_activate_license = !empty( get_option('sayara_activate_license_option') ) ? get_option('sayara_activate_license_option') : '';

	    	wp_remote_post( 'https://themebing.com/wp-json/purchase/code='.$sayara_activate_license.'/site='.home_url().'/email='.get_option( 'admin_email', false ),array( 
				'timeout' => 10
			));

	    	$validation = wp_remote_retrieve_body( wp_remote_get( 'https://themebing.com/wp-json/license-validation/code='.$sayara_activate_license.'/site='.home_url(), array( 
				'timeout' => 10,
				'httpversion' => '1.1'
			)));

		    	if ( !is_wp_error($validation) && isset( $validation ) ) {

		    		if (!empty(json_decode( $validation, true )['status'])) { ?>

		    			<h3><?php echo esc_html( json_decode( $validation, true )['message'] ) ?></h3>
		    			<?php if (is_admin()) {
		    				update_user_meta( get_current_user_id(), 'licence_activated', $sayara_activate_license );
		    				wp_redirect(get_dashboard_url(get_current_user_id()));
		    			} ?>

		    		<?php } else { ?>

		    			<?php if ( !isset(json_decode( $validation, true )['data']['status']) ){ ?>

							<h3><?php echo json_decode( $validation, true )['message']; ?></h3>

		    			<?php } else { ?>

			    			<strong><?php echo esc_html__( '( Not activated! )','sayara' ) ?></strong>
				    		<?php printf(__('Enter your purchase code (e.g %s).', 'sayara'), 'd54e2c8d-e075-4e4d-a10a-e9bf64r64rdbc');?>
				    		
				    		<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"><?php echo esc_html__( 'See how to get your purchase code', 'sayara' ) ?></a>
		    		<?php }
		    	}
	    	} ?>
	    	
	    </p>

	<?php
	}
}
add_action( 'admin_init', 'sayara_activate_license_init' );

// Admin notice for license key
function sayara_admin_license_notice(){
	if (empty(get_user_meta( get_current_user_id(), 'licence_activated' , true ))) { ?>
		<div class="notice notice-error is-dismissible">
			<p>
				<strong>
			        <p><?php echo esc_html__( 'This theme requires license key to install core plugin, auto update and one click demo import.', 'sayara' ) ?></p>
			        <span><a href="<?php echo admin_url( 'admin.php?page=activate-license' ); ?>"><?php echo esc_html__( 'Activate License','sayara' ) ?></a></span> |
			        <span><a href="<?php echo esc_url( 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-' ); ?>" target="_blank"><?php echo esc_html__( 'Get Your Purchase Code','sayara' ) ?></a></span>
		        </strong>
	        </p>
	    </div>
	<?php } ?>
<?php
}
add_action('admin_notices', 'sayara_admin_license_notice');