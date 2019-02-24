<?php
/**
Plugin Name: Playa Rental Properties
Plugin URI: https://abovemedia.ca
Author: Above Media
Version:      0.0.1
Author URI:   https://abovemedia.ca
Description:  Custom Functionality for PRP. Requires the WP Rentals Theme
License:      GPL2
*/
if ( ! defined('ABSPATH') ) {
	exit;
}
remove_filter( 'manage_edit-wpestate_booking_columns', 'wpestate_my_booking_columns' );
remove_action( 'manage_posts_custom_column', 'wpestate_populate_booking_columns' );

add_filter( 'manage_edit-wpestate_booking_columns', 'wpestate_my_booking_columns' );

if( !function_exists('wpestate_my_booking_columns') ):
    function wpestate_my_booking_columns( $columns ) {
        $slice=array_slice($columns,2,2);
        unset( $columns['comments'] );
        unset( $slice['comments'] );
        $splice=array_splice($columns, 2);  
        $columns['booking_estate_listing']  = esc_html__( 'Listing','wpestate');
        $columns['booking_estate_checkin']   = esc_html__( 'Check-In','wpestate');
        $columns['booking_estate_checkout']   = esc_html__( 'Check-Out','wpestate');
        //$columns['booking_estate_owner']    = esc_html__( 'Owner','wpestate');
        $columns['booking_estate_renter']   = esc_html__( 'Renter','wpestate');
        $columns['booking_estate_renter_email']   = esc_html__( 'Renter Email','wpestate');
        $columns['booking_estate_value']   = esc_html__( 'Value','wpestate');
        $columns['booking_estate_value_to_be_paid']   = esc_html__( 'Initial Deposit','wpestate');
        return  array_merge($columns,array_reverse($slice));
    }
endif; // end   wpestate_my_columns  


add_action( 'manage_posts_custom_column', 'wpestate_populate_booking_columns' );
if( !function_exists('wpestate_populate_booking_columns') ):
    function wpestate_populate_booking_columns( $column ) {
        $the_id=get_the_ID();
        $invoice_no         =   get_post_meta($the_id, 'booking_invoice_no', true);
        
		if(  'booking_estate_listing' == $column){
            $curent_listng_id= get_post_meta($the_id, 'booking_id',true);
            echo get_the_title($curent_listng_id);
        }
		
        if(  'booking_estate_checkin' == $column){
            echo esc_html(get_post_meta($the_id, 'booking_from_date', true));
        }
		
		if(  'booking_estate_checkout' == $column){
            echo esc_html(get_post_meta($the_id, 'booking_to_date', true));
        }
        
    //    if(  'booking_estate_owner' == $column){
    //        $owner_id = get_post_meta($the_id, 'owner_id', true);
    //        $user = get_user_by( 'id', $owner_id );
    //        echo $user->user_login;
    //    }
        
        if(  'booking_estate_renter' == $column){
           $renter_name        =   get_post_meta($the_id, 'am_renter_name', true);
		   echo $renter_name;
        }
		if(  'booking_estate_renter_email' == $column){
           $renter_email        =   get_post_meta($the_id, 'am_renter_email', true);
		   echo $renter_email;
        }
        
        if(  'booking_estate_value' == $column){
             echo '<a href="https://playarentalproperties.com/print-booking?booking='. $the_id .'" target="_blank">Print</a>';
        }
        if(  'booking_estate_value_to_be_paid' == $column){
            $to_be_paid         =   get_post_meta($the_id, 'am_already_paid', true);
            echo $to_be_paid;
        }
        
       
        
    }
endif;






//Add Meta Boxes to the Booking Edit Page in Admin
function PRP_admin_init(){
add_meta_box('am_renter_name', 'Renter Name', 'am_renter_name', 'wpestate_booking', 'normal', 'high');
add_meta_box('am_renter_email', 'Renter Email', 'am_renter_email', 'wpestate_booking', 'normal', 'high');
add_meta_box('am_total_value', 'Total Value', 'am_total_value', 'wpestate_booking', 'normal', 'high');
add_meta_box('am_already_paid', 'Amount Paid', 'am_already_paid', 'wpestate_booking', 'normal', 'high');
}
add_action('admin_init', 'PRP_admin_init');

function am_renter_name(){
  global $post;
  $custom = get_post_custom($post->ID);
  $am_renter_name = $custom['am_renter_name'][0];
  ?>
  <label>Renter Name:</label>
  <input name='am_renter_name' value='<?php echo $am_renter_name; ?>' />
  <?php
}
function am_renter_email(){
  global $post;
  $custom = get_post_custom($post->ID);
  $am_renter_email = $custom['am_renter_email'][0];
  ?>
  <label>Renter Email:</label>
  <input name='am_renter_email' value='<?php echo $am_renter_email; ?>' />
  <?php
}
function am_total_value(){
  global $post;
  $custom = get_post_custom($post->ID);
  $am_total_value = $custom['am_total_value'][0];
  ?>
  <label>Total Value:</label>
  <input name='am_total_value' value='<?php echo $am_total_value; ?>' />
  <?php
}
function am_already_paid(){
  global $post;
  $custom = get_post_custom($post->ID);
  $am_already_paid = $custom['am_already_paid'][0];
  ?>
  <label>Amount Paid:</label>
  <input name='am_already_paid' value='<?php echo $am_already_paid; ?>' />
  <?php
}

//Save PRP Meta
function save_prp_booking_meta(){
  global $post;
  update_post_meta($post->ID, "am_renter_name", $_POST["am_renter_name"]);
  update_post_meta($post->ID, "am_renter_email", $_POST["am_renter_email"]);
  update_post_meta($post->ID, "am_total_value", $_POST["am_total_value"]);
  update_post_meta($post->ID, "am_already_paid", $_POST["am_already_paid"]);

  }
add_action('save_post_wpestate_booking', 'save_prp_booking_meta');


//Register Post Types
function prp_register_post_types()
{

	//Request
    register_post_type('prp_request',
                       array(
                           'labels'      => array(
                               'name'          => __('Requests', 'prp'),
                               'singular_name' => __('Request', 'prp'),
							   'add_new' => __('New Request', 'prp'),
								'add_new_item' => __('Add New Request', 'prp'),
								'edit_item' => __('Edit Request', 'prp'),
								'new_item' => __('New Request', 'prp'),
								'view_item' => __('View Request', 'prp'),
								'search_items' => __('Search Request', 'prp'),
								'not_found' =>  __('No Requests Found', 'prp'),
                           ),
                           'description' => __('Email Logs', 'prp'),
							'exclude_from_search' => true,
							'publicly_queryable' => false,
							'public' => false,
							'show_ui' => true,
							'show_in_admin_bar' => false,
							'hierarchical' => false,
							'has_archive' => false,
							'supports' => array('title','custom_fields','editor'),
							'rewrite' => false,
							'can_export' => false,
							'capabilities' => array (
								'create_posts' => false,
								'edit_post' => 'edit_posts',
								'read_post' => 'edit_posts',
								'delete_post' => 'edit_posts',
								'edit_posts' => 'edit_posts',
								'edit_others_posts' => 'edit_posts',
								'publish_posts' => 'edit_posts',
								'read_private_posts' => 'edit_posts',
							),
                       )
    );
}
add_action( 'init', 'prp_register_post_types' );

?>
