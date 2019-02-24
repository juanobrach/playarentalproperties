<?php
require_once get_template_directory().'/libs/plugins/multiple_sidebars_plugin.php';
require_once get_template_directory().'/libs/plugins/class-tgm-plugin-activation.php';

///////////////////////////////////////////////////////////////////////////////////////////
/////// Required Plugins 
///////////////////////////////////////////////////////////////////////////////////////////




if( !function_exists('wpestate_required_plugins') ):
function wpestate_required_plugins() {
	$plugins = array(
		array(
                    'name'     			=> 'Revolution Slider', 
                    'slug'     			=> 'revslider', 
                    'source'   			=> get_template_directory_uri()  . '/libs/plugins/revslider.zip',
                    'required' 			=> false,
                    'version' 			=>  '5.4.8',
                    'force_activation' 		=> false, 
                    'force_deactivation' 	=> false,
                    'external_url' 		=> '',
		),
                array(
                    'name'     			=> 'WPBakery Visual Composer', 
                    'slug'     			=> 'js_composer', 
                    'source'   			=> get_template_directory_uri()  . '/libs/plugins/js_composer.zip',
                    'required' 			=> true,
                    'version' 			=> '5.5.1',
                    'force_activation' 		=> false, 
                    'force_deactivation' 	=> false,
                    'external_url' 		=> '',
		),
                array(
                    'name'     			=> 'Ultimate Addons for Visual Composer', 
                    'slug'     			=> 'Ultimate_VC_Addons', 
                    'source'   			=> get_template_directory_uri()  . '/libs/plugins/Ultimate_VC_Addons.zip',
                    'required' 			=> false,
                    'version' 			=> '3.16.24',
                    'force_activation' 		=> false, 
                    'force_deactivation' 	=> false,
                    'external_url' 		=> '',
		),
                  array(
                    'name'     			=> 'EWWW Image Optimizer', 
                    'slug'     			=> 'ewww-image-optimizer', 
                    'source'   			=> get_template_directory_uri()  . '/libs/plugins/ewww-image-optimizer.4.0.0.zip',
                    'required' 			=> false,
                    'version' 			=> '4.0.0',
                    'force_activation' 		=> false, 
                    'force_deactivation' 	=> false,
                    'external_url' 		=> '',
		),array(
                    'name'     			=> 'One Click Demo Import', 
                    'slug'     			=> 'one-click-demo-import', 
                    'source'   			=> get_template_directory_uri()  . '/libs/plugins/one-click-demo-import.zip',
                    'required' 			=> false,
                    'version' 			=> '2.5.0',
                    'force_activation' 		=> false, 
                    'force_deactivation' 	=> false,
                    'external_url' 		=> '',
		)
	);

	
	
		$config = array(
		'domain'       		=> 'wpestate',         	
		'default_path' 		=> '',                         	
		'parent_slug'           => 'themes.php', 				
						
		'menu'         		=> 'install-required-plugins', 	
		'has_notices'      	=> true,                       
		'is_automatic'    	=> true,					   
		'message' 			=> '',							
		'strings'      		=> array(
			'page_title'                       			=> esc_html__(  'Install Required Plugins', 'wprentals' ),
			'menu_title'                       			=> esc_html__(  'Install Plugins', 'wprentals' ),
			'installing'                       			=> esc_html__(  'Installing Plugin: %s', 'wprentals' ), 
			'oops'                             			=> esc_html__(  'Something went wrong with the plugin API.', 'wprentals' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ,'wprentals'), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ,'wprentals'), // %1$s = plugin name(s)
			'notice_cannot_install'  				=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.','wprentals' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.','wprentals' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.','wprentals' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 				=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.','wprentals' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 					=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.','wprentals' ), // %1$s = plugin name(s)
			'notice_cannot_update' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'wprentals'), // %1$s = plugin name(s)
			'install_link' 					 	=> _n_noop( 'Begin installing plugin', 'Begin installing plugins','wprentals' ),
			'activate_link' 				  	=> _n_noop( 'Activate installed plugin', 'Activate installed plugins','wprentals' ),
			'return'                           			=> esc_html__(  'Return to Required Plugins Installer', 'wprentals' ),
			'plugin_activated'                 			=> esc_html__(  'Plugin activated successfully.', 'wprentals' ),
			'complete' 						=> esc_html__(  'All plugins installed and activated successfully. %s', 'wprentals' ), 
			'nag_type'						=> 'updated'
		)
	);
tgmpa($plugins, $config);
}
endif; // end   wpestate_required_plugins  
?>
