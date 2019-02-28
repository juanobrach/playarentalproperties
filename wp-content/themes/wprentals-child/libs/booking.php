<?php

////////////////////////////////////////////////////////////////////////////////
// custom action on save
////////////////////////////////////////////////////////////////////////////////


add_action('save_post', 'WDP_estate_save_booking_postdata', 99);
if( !function_exists('WDP_estate_save_booking_postdata') ):
    function WDP_estate_save_booking_postdata($post_id) {
        global $post;   
        if(!is_object($post) || !isset($post->post_type)) {
            return;
        }

        if($post->post_type!='wpestate_booking'){
            return;    
        }

        $curent_listng_id= get_post_meta($post_id, 'booking_id',true);
  
        if($curent_listng_id ==''){
            $selected= $curent_listng_id= get_post_meta($post->ID,'booking_listing_name',true);
            update_post_meta($post_id, 'booking_id', $selected  ); 
        } 
        
        // save booking dates;
        $reservation_array = WDP_wpestate_get_booking_dates($curent_listng_id);
        update_post_meta($curent_listng_id, 'booking_dates', $reservation_array);      
    }
endif;


function WDP_get_bookings_from_listings($listing_id=null, $booking_id ){
    $args=array(
        'post_type'        => 'wpestate_booking',
        'post_status'      => 'any',
        'posts_per_page'   => -1,
        'post__not_in'    => array( $booking_id ),
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
    
    $reservation_array  =   array();
    
    $booking_selection  =   new WP_Query($args);

    if ($booking_selection->have_posts()){    

        while ($booking_selection->have_posts()): $booking_selection->the_post();
            $pid            =   get_the_ID();
            
            $fromd          =   esc_html(get_post_meta($pid, 'booking_from_date', true));
            $tod            =   esc_html(get_post_meta($pid, 'booking_to_date', true));

            $reservation_array[]= array( $fromd , $tod );          
        endwhile;
          // print_r($reservation_array);
        wp_reset_query();
    }
    return $reservation_array;
}

////////////////////////////////////////////////////////////////////////////////
// save array with bookng dates
////////////////////////////////////////////////////////////////////////////////
if (!function_exists("WDP_wpestate_get_booking_dates")):
function WDP_wpestate_get_booking_dates($listing_id){
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
    
    $reservation_array  =   array();
    
    $booking_selection  =   new WP_Query($args);
    $now=time();
    $daysago = $now-365*24*60*60;
  

    if ($booking_selection->have_posts()){    

        while ($booking_selection->have_posts()): $booking_selection->the_post();
            $pid            =   get_the_ID();
            
            $fromd          =   esc_html(get_post_meta($pid, 'booking_from_date', true));
            $tod            =   esc_html(get_post_meta($pid, 'booking_to_date', true));
            $unix_time_start = strtotime ($fromd);
            if ($unix_time_start > $daysago){ // add booking from 3 days ago 
                $from_date      =   new DateTime($fromd);
                $from_date_unix =   $from_date->getTimestamp();
                $to_date        =   new DateTime($tod);
                $to_date_unix   =   $to_date->getTimestamp();

                //$reservation_array[]=$from_date_unix;
                $reservation_array[$from_date_unix]=$pid;

                // $from_date->modify('tomorrow');
                $from_date_unix =   $from_date->getTimestamp();

               //print ' from date'.$from_date_unix.'  ---  to date.'.$to_date_unix.' - '.date("Y-m-d", $from_date_unix).' --- '.date("Y-m-d", $to_date_unix).'</br>';

                while ($from_date_unix < $to_date_unix){
                //  print '</br> iteration from date'.$from_date_unix. ' / ' .date("Y-m-d", $from_date_unix);
                //  $reservation_array[]=$from_date_unix;
                    $reservation_array[$from_date_unix]=$pid;

                    $from_date->modify('tomorrow');
                    $from_date_unix =   $from_date->getTimestamp();
                }          
            }
        endwhile;
          // print_r($reservation_array);
        wp_reset_query();
    }        
  
    return $reservation_array;
    
}

endif;


////////////////////////////////////////////////////////////////////////////////////////////////
// Add booking metaboxes
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_bookings_metaboxes') ):
function wpestate_add_bookings_metaboxes() {    
  add_meta_box(  'estate_booking-sectionid', esc_html__(  'Booking Details', 'wprentals' ), 'wpestate_booking_meta_function', 'wpestate_booking' ,'normal','default');
}
endif; // end   



////////////////////////////////////////////////////////////////////////////////////////////////
// booking details
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_booking_meta_function') ):
function wpestate_booking_meta_function( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'estate_booking_noncename' );
    global $post;
    $option_status='';
    $status_values = array(
                        'confirmed',
                        'pending'
                        );
    
     $status_type = get_post_meta($post->ID, 'booking_status', true);

     foreach ($status_values as $value) {
         $option_status.='<option value="' . $value . '"';
         if ($value == $status_type) {
             $option_status.='selected="selected"';
         }
         $option_status.='>' . $value . '</option>';
     }
    // print   ' owner id '.get_post_meta($post->ID, 'owner_id', true);
    $property_id = esc_html(get_post_meta($post->ID, 'booking_id', true)); 
    $property_id = apply_filters( 'wpml_object_id', $property_id, get_post_type($property_id), true );
    
    print'   
    <p class="meta-options">
        <label for="booking_listing_name">'.esc_html__( 'Booking Status:','wprentals').' </label>
        '.get_post_meta($post->ID, 'booking_status', true).'
    </p>
    
    <p class="meta-options">
        <label for="booking_listing_name">'.esc_html__( 'Booking Invoice:','wprentals').' </label>
        '.get_post_meta($post->ID, 'booking_invoice_no', true).'
    </p>
    
    <p class="meta-options">
        <label for="booking_from_date">'.esc_html__( 'Check In:','wprentals').' </label><br />
        <input type="text" id="booking_from_date" size="58" name="booking_from_date" value="'.  esc_html(get_post_meta($post->ID, 'booking_from_date', true)).'">
    </p>
    
    <p class="meta-options">
        <label for="booking_to_date">'.esc_html__( 'Check Out:','wprentals').' </label><br />
        <input type="text" id="booking_to_date" size="58" name="booking_to_date" value="'.  esc_html(get_post_meta($post->ID, 'booking_to_date', true)).'">
    </p>

    <p class="meta-options">
        <label for="booking_id">'.esc_html__( 'Property ID:','wprentals').' </label><br />
        <input type="text" id="booking_id" size="58" name="booking_id" value="'.  $property_id.'">
    </p>
   
    <p class="meta-options">
        <label for="booking_guests">'.esc_html__( 'Guests No:','wprentals').' </label><br />
        <input type="text" id="booking_guests" size="58" name="booking_guests" value="'.  esc_html(get_post_meta($post->ID, 'booking_guests', true)).'">
    </p>
    
    <p class="meta-options">
        <label for="booking_status">'.esc_html__( 'Property Name:','wprentals').' </label><br />         
        '.get_the_title($property_id).'
    </p>
    

    <p class="meta-options">
        <label for="booking_listing_name">'.esc_html__( 'Booking Status:','wprentals').' </label><br />
        <select id="booking_status" name="booking_status">
            '.$option_status.' 
        </select>   
    </p>
    ';

     // Get all bookings for this listing
    $bookings = WDP_get_bookings_from_listings($property_id, $post->ID );
    // Print range of date for each bookings founded
    if( !empty( $bookings ) && is_array( $bookings )  ){    
        print' <div id="listing-bookings" data-listing-bookings='.json_encode($bookings).'></div>';
    }   
    

    $date_lang_status= esc_html ( get_option('wp_estate_date_lang','') );
    $dates_types=array(
            '0' =>'yy-mm-dd',
            '1' =>'yy-dd-mm',
            '2' =>'dd-mm-yy',
            '3' =>'mm-dd-yy',
            '4' =>'dd-yy-mm',
            '5' =>'mm-yy-dd',

    );

    // We have differents scripts becouse original inputs for datepicker user ID'S and jqueryDatepicker can't have the same instance for differents inputs

    // checkin Datepicker  
    print '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>';                
    print '<script type="text/javascript">
                  //<![CDATA[
                  jQuery(document).ready(function(){
                     jQuery("#booking_from_date").datepicker({
                        dateFormat : "'.$dates_types[esc_html ( get_option('wp_estate_date_format','') )].'",
                        beforeShowDay: function(date){
                            // Bookings arrays for the actual listing **DONT ADDED ACTUAL BOOKINGS TO THIS RANGE [ startDate, endDate];
                            // the data-listings-bookins is populated with the function WDP_get_bookings_from_listings
                            var bookings = jQuery("#listing-bookings").attr("data-listing-bookings");     
                            console.log(bookings)                   
                            dateRange = [];           // array to hold the range
                            var arr =  JSON.parse(bookings)
                            arr.forEach( function( date){
                                // populate the array
                                var checkIn = moment(date[0] );
                                var checkOut = moment(date[1]  );
                                var incrementDate  = moment(date[0] );
                                for ( incrementDate; incrementDate <= checkOut ; incrementDate.add("days", 1) ) {
                                    if( incrementDate.format() != checkOut.format() ){
                                        dateRange.push( incrementDate.format("YYYY-MM-DD") );
                                    }
                                }
                            })
                            var day = date.getDay();
                            var string = jQuery.datepicker.formatDate("yy-mm-dd", date);
                            var isDisabled = ($.inArray(string, dateRange) != -1);
                            return [!isDisabled];
                        }
                    },jQuery.datepicker.regional["'.$date_lang_status.'"]).datepicker("widget").wrap(\'<div class="ll-skin-melon"/>\');
                  });
                  //]]>
                  </script>';

    // checkout Datepicker                             
    print '<script type="text/javascript">
                  //<![CDATA[
                  jQuery(document).ready(function(){
                     jQuery("#booking_to_date").datepicker({
                        dateFormat : "'.$dates_types[esc_html ( get_option('wp_estate_date_format','') )].'",
                        beforeShowDay: function(date){
                            // Bookings arrays for the actual listing [ startDate, endDate];
                            // the data-listings-bookins is populated with the function WDP_get_bookings_from_listings
                            var bookings = jQuery("#listing-bookings").attr("data-listing-bookings");     
                            dateRange = [];           // array to hold the range
                            var arr =  JSON.parse(bookings)
                            arr.forEach( function( date){
                                // populate the array
                                var checkIn = moment(date[0]);
                                var checkOut = moment(date[1]); 
                                var incrementDate  = moment(date[0]);

                                
                                for ( incrementDate; incrementDate.format() <= checkOut.format(); incrementDate.add("days", 1) ) {
                                    if(  incrementDate.format() != checkIn.format() ){
                                        dateRange.push( incrementDate.format("YYYY-MM-DD") );
                                    }
                                }
                            })
                            var day = date.getDay();
                            var string = jQuery.datepicker.formatDate("yy-mm-dd", date);
                            var isDisabled = ($.inArray(string, dateRange) != -1);
                            return [!isDisabled];
                        }
                    },jQuery.datepicker.regional["'.$date_lang_status.'"]).datepicker("widget").wrap(\'<div class="ll-skin-melon"/>\');
                  });
                  //]]>
                  </script>';                  





}
endif; // end   estate_booking  

