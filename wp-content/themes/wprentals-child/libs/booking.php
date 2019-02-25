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
    $daysago = $now-3*24*60*60;
  
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
