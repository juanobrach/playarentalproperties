<?php

add_action( 'admin_enqueue_scripts', 'load_admin_print_style' );
      function load_admin_print_style() {
        wp_enqueue_style( 'admin_print_css', get_stylesheet_directory_uri() . '/admin-print.css', false, '1.0.0' );
}

//Get XML for amenities for a listing
function wdp_estate_listing_features($post_id){
        $return_string='';    
        $counter            =   1;                          
        $feature_list_array =   array();
        $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
        $feature_list_array =   explode( ',',$feature_list);
		
	 foreach($feature_list_array as $checker => $value){
                        
                        $post_var_name  =   str_replace(' ','_', trim($value) );
                        $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
                        $input_name     =   sanitize_key($input_name);
						$value= stripslashes($value);
						
						if (esc_html( get_post_meta($post_id, $input_name, true) ) == 1) {
						$return_string  .= '<Amenity order="'. $counter .'">';
						$return_string  .= trim($value);
						$return_string  .= '</Amenity>';
						$counter++;
						}
	 }
return $return_string;		
}

function wdp_get_ordered_images ($post_id) {
	$returnIMG = '';
	$arguments      = array(
                    'numberposts' => -1,
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image',
                    'post_parent' => $post_id,
                    'post_status' => null,
                    'orderby'         => 'menu_order',
                    'order'           => 'ASC'
                );

$post_attachments   = get_posts($arguments);

foreach ($post_attachments as $pic) {
	$permalink = get_permalink($pic->ID);
	$returnIMG .= '<Photo order="'. $pic->menu_order .'">' . $permalink . '</Photo>';
}
return $returnIMG;	
}


function wdp_flipkey_get_booking_dates($listing_id){
    $flipkey_feed='';
    $args=array(
        'post_type'        => 'wpestate_booking',
        'post_status'      => 'any',
        'posts_per_page'   => -1,
        'meta_query' => array(
                            array(
                                'key'       => 'booking_id',
                                'value'     => $listing_id,
                                'type'      => 'NUMERIC',
                                'compare'   => '='
                            ),
                            array(
                                'key'       =>  'booking_status',
                                'value'     =>  'confirmed',
                                'compare'   =>  '='
                            )
                        )
        );
    

    $booking_selection  =   new WP_Query($args);

    if ($booking_selection->have_posts()){    
        $flipkey_feed='';
        while ($booking_selection->have_posts()): $booking_selection->the_post();
            $pid            =   get_the_ID();
            $fromd          =   esc_html(get_post_meta($pid, 'booking_from_date', true));
            $tod            =   esc_html(get_post_meta($pid, 'booking_to_date', true));

        $flipkey_feed .= '<BookedStay>'; 
			$flipkey_feed .= '<ArrivalDate>'. $fromd .'</ArrivalDate>';
			$flipkey_feed .= '<DepartureDate>'. $tod .'</DepartureDate>';
		$flipkey_feed .= '</BookedStay>';  
            
        endwhile;
         
        wp_reset_query();
    }        
  
    return $flipkey_feed;
    
}

function ratesmetabox_admin_init(){
  add_meta_box('rate1Title', 'Rate 1 Title', 'rate1Title', 'estate_property', 'normal', 'high');
  add_meta_box('rate1From', 'Rate 1 Start Date', 'rate1From', 'estate_property', 'normal', 'high');
  add_meta_box('rate1To', 'Rate 1 End Date', 'rate1To', 'estate_property', 'normal', 'high');
  add_meta_box('rate1Value', 'Rate 1 Weekly Value', 'rate1Value', 'estate_property', 'normal', 'high');
  add_meta_box('rate2Title', 'Rate 2 Title', 'rate2Title', 'estate_property', 'normal', 'high');
  add_meta_box('rate2From', 'Rate 2 Start Date', 'rate2From', 'estate_property', 'normal', 'high');
  add_meta_box('rate2To', 'Rate 2 End Date', 'rate2To', 'estate_property', 'normal', 'high');
  add_meta_box('rate2Value', 'Rate 2 Weekly Value', 'rate2Value', 'estate_property', 'normal', 'high');
  add_meta_box('rate3Title', 'Rate 3 Title', 'rate3Title', 'estate_property', 'normal', 'high');
  add_meta_box('rate3From', 'Rate 3 Start Date', 'rate3From', 'estate_property', 'normal', 'high');
  add_meta_box('rate3To', 'Rate 3 End Date', 'rate3To', 'estate_property', 'normal', 'high');
  add_meta_box('rate3Value', 'Rate 3 Weekly Value', 'rate3Value', 'estate_property', 'normal', 'high');
  add_meta_box('rate4Title', 'Rate 4 Title', 'rate4Title', 'estate_property', 'normal', 'high');
  add_meta_box('rate4From', 'Rate 4 Start Date', 'rate4From', 'estate_property', 'normal', 'high');
  add_meta_box('rate4To', 'Rate 4 End Date', 'rate4To', 'estate_property', 'normal', 'high');
  add_meta_box('rate4Value', 'Rate 4 Weekly Value', 'rate4Value', 'estate_property', 'normal', 'high');
  add_meta_box('rate5Title', 'Rate 5 Title', 'rate5Title', 'estate_property', 'normal', 'high');
  add_meta_box('rate5From', 'Rate 5 Start Date', 'rate5From', 'estate_property', 'normal', 'high');
  add_meta_box('rate5To', 'Rate 5 End Date', 'rate5To', 'estate_property', 'normal', 'high');
  add_meta_box('rate5Value', 'Rate 5 Weekly Value', 'rate5Value', 'estate_property', 'normal', 'high');
  
}
//Add Meta Boxes to the Property Edit Page
function rate1Title(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate1Title = $custom['rate1Title'][0];
  ?>
  <label>Rate 1 title:</label>
  <input name='rate1Title' value='<?php echo $rate1Title; ?>' />
  <?php
}
function rate1From(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate1From = $custom['rate1From'][0];
  ?>
  <label>Rate 1 Starts (YY-MM-DD):</label>
  <input name='rate1From' value='<?php echo $rate1From; ?>' />
  <?php
}
function rate1To(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate1To = $custom['rate1To'][0];
  ?>
  <label>Rate 1 Ends (YY-MM-DD):</label>
  <input name='rate1To' value='<?php echo $rate1To; ?>' />
  <?php
}
function rate1Value(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate1Value = $custom['rate1Value'][0];
  ?>
  <label>Rate 1 Weekly USD Value:</label>
  <input name='rate1Value' value='<?php echo $rate1Value; ?>' />
  <?php
}
function rate2Title(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate2Title = $custom['rate2Title'][0];
  ?>
  <label>Rate 2 title:</label>
  <input name='rate2Title' value='<?php echo $rate2Title; ?>' />
  <?php
}
function rate2From(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate2From = $custom['rate2From'][0];
  ?>
  <label>Rate 2 Starts (YY-MM-DD):</label>
  <input name='rate2From' value='<?php echo $rate2From; ?>' />
  <?php
}
function rate2To(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate2To = $custom['rate2To'][0];
  ?>
  <label>Rate 2 Ends (YY-MM-DD):</label>
  <input name='rate2To' value='<?php echo $rate2To; ?>' />
  <?php
}
function rate2Value(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate2Value = $custom['rate2Value'][0];
  ?>
  <label>Rate 2 Weekly USD Value:</label>
  <input name='rate2Value' value='<?php echo $rate2Value; ?>' />
  <?php
}
function rate3Title(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate3Title = $custom['rate3Title'][0];
  ?>
  <label>Rate 3 title:</label>
  <input name='rate3Title' value='<?php echo $rate3Title; ?>' />
  <?php
}
function rate3From(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate3From = $custom['rate3From'][0];
  ?>
  <label>Rate 3 Starts (YY-MM-DD):</label>
  <input name='rate3From' value='<?php echo $rate3From; ?>' />
  <?php
}
function rate3To(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate3To = $custom['rate3To'][0];
  ?>
  <label>Rate 3 Ends (YY-MM-DD):</label>
  <input name='rate3To' value='<?php echo $rate3To; ?>' />
  <?php
}
function rate3Value(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate3Value = $custom['rate3Value'][0];
  ?>
  <label>Rate 3 Weekly USD Value:</label>
  <input name='rate3Value' value='<?php echo $rate3Value; ?>' />
  <?php
}
function rate4Title(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate4Title = $custom['rate4Title'][0];
  ?>
  <label>Rate 4 title:</label>
  <input name='rate4Title' value='<?php echo $rate4Title; ?>' />
  <?php
}
function rate4From(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate4From = $custom['rate4From'][0];
  ?>
  <label>Rate 4 Starts (YY-MM-DD):</label>
  <input name='rate4From' value='<?php echo $rate4From; ?>' />
  <?php
}
function rate4To(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate4To = $custom['rate4To'][0];
  ?>
  <label>Rate 4 Ends (YY-MM-DD):</label>
  <input name='rate4To' value='<?php echo $rate4To; ?>' />
  <?php
}
function rate4Value(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate4Value = $custom['rate4Value'][0];
  ?>
  <label>Rate 4 Weekly USD Value:</label>
  <input name='rate4Value' value='<?php echo $rate4Value; ?>' />
  <?php
}
function rate5Title(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate5Title = $custom['rate5Title'][0];
  ?>
  <label>Rate 5 title:</label>
  <input name='rate5Title' value='<?php echo $rate5Title; ?>' />
  <?php
}
function rate5From(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate5From = $custom['rate5From'][0];
  ?>
  <label>Rate 5 Starts (YY-MM-DD):</label>
  <input name='rate5From' value='<?php echo $rate5From; ?>' />
  <?php
}
function rate5To(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate5To = $custom['rate5To'][0];
  ?>
  <label>Rate 5 Ends (YY-MM-DD):</label>
  <input name='rate5To' value='<?php echo $rate5To; ?>' />
  <?php
}
function rate5Value(){
  global $post;
  $custom = get_post_custom($post->ID);
  $rate5Value = $custom['rate5Value'][0];
  ?>
  <label>Rate 5 Weekly USD Value:</label>
  <input name='rate5Value' value='<?php echo $rate5Value; ?>' />
  <?php
}
add_action('admin_init', 'ratesmetabox_admin_init');

function save_ratesMeta(){
	if ( is_admin() ) {
  global $post;
  update_post_meta($post->ID, "rate1Title", $_POST["rate1Title"]);
  update_post_meta($post->ID, "rate1From", $_POST["rate1From"]);
  update_post_meta($post->ID, "rate1To", $_POST["rate1To"]);
  update_post_meta($post->ID, "rate1Value", $_POST["rate1Value"]);
  update_post_meta($post->ID, "rate2Title", $_POST["rate2Title"]);
  update_post_meta($post->ID, "rate2From", $_POST["rate2From"]);
  update_post_meta($post->ID, "rate2To", $_POST["rate2To"]);
  update_post_meta($post->ID, "rate2Value", $_POST["rate2Value"]);
  update_post_meta($post->ID, "rate3Title", $_POST["rate3Title"]);
  update_post_meta($post->ID, "rate3From", $_POST["rate3From"]);
  update_post_meta($post->ID, "rate3To", $_POST["rate3To"]);
  update_post_meta($post->ID, "rate3Value", $_POST["rate3Value"]);
  update_post_meta($post->ID, "rate4Title", $_POST["rate4Title"]);
  update_post_meta($post->ID, "rate4From", $_POST["rate4From"]);
  update_post_meta($post->ID, "rate4To", $_POST["rate4To"]);
  update_post_meta($post->ID, "rate4Value", $_POST["rate4Value"]);
  update_post_meta($post->ID, "rate5Title", $_POST["rate5Title"]);
  update_post_meta($post->ID, "rate5From", $_POST["rate5From"]);
  update_post_meta($post->ID, "rate5To", $_POST["rate5To"]);
  update_post_meta($post->ID, "rate5Value", $_POST["rate5Value"]);
  update_post_meta($post->ID, "listing_name", $_POST["post_title"]);
  }
  }
add_action('save_post', 'save_ratesMeta');


// Edit booking from admin functions
include dirname( __FILE__ ) . '/libs/booking.php';

// Custom search functions for advance search results ( fixed orderd by title ASC )
include dirname( __FILE__ ) . '/libs/search_functions3.php';