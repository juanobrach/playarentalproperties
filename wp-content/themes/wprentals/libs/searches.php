<?php
// register the custom post type
add_action( 'after_setup_theme', 'wpestate_create_saved_search' );

if( !function_exists('wpestate_create_saved_search') ):
function wpestate_create_saved_search() {
register_post_type( 'wpestate_search',
		array(
			'labels' => array(
				'name'          => esc_html__(  'Searches','wprentals'),
				'singular_name' => esc_html__(  'Searches','wprentals'),
				'add_new'       => esc_html__( 'Add New Searches','wprentals'),
                'add_new_item'          =>  esc_html__( 'Add Searches','wprentals'),
                'edit'                  =>  esc_html__( 'Edit Searches' ,'wprentals'),
                'edit_item'             =>  esc_html__( 'Edit Searches','wprentals'),
                'new_item'              =>  esc_html__( 'New Searches','wprentals'),
                'view'                  =>  esc_html__( 'View Searches','wprentals'),
                'view_item'             =>  esc_html__( 'View Searches','wprentals'),
                'search_items'          =>  esc_html__( 'Search Searches','wprentals'),
                'not_found'             =>  esc_html__( 'No Searches found','wprentals'),
                'not_found_in_trash'    =>  esc_html__( 'No Searches found','wprentals'),
                'parent'                =>  esc_html__( 'Parent Searches','wprentals')
			),
		'public' => false,
		'has_archive' => false,
		'rewrite' => array('slug' => 'searches'),
		'supports' => array('title'),
		'can_export' => true,
		'register_meta_box_cb' => 'wpestate_add_searches',
                 'menu_icon'=>get_template_directory_uri().'/img/searches.png'
		)
	);
}
endif; // end   wpestate_create_invoice_type  

////////////////////////////////////////////////////////////////////////////////////////////////
// Add Invoice metaboxes
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_searches') ):
function wpestate_add_searches() {	
        add_meta_box(  'estate_invoice-sectionid',  esc_html__(  'Search Details', 'wprentals' ),'wpestate_search_details','wpestate_search' ,'normal','default');
}
endif; // end   wpestate_add_pack_invoices  



////////////////////////////////////////////////////////////////////////////////////////////////
// Invoice Details
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_search_details') ):

function wpestate_search_details( $post ) {
    global $post;
    wp_nonce_field( plugin_basename( __FILE__ ), 'estate_invoice_noncename' );

    
}

endif; // end   wpestate_invoice_details  


?>