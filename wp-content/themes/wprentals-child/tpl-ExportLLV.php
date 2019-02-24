<?php
/* Template Name: Export to LLV */ 
$properties=$_GET["properties"];
$PropSum = '';
$args=array(
        'post_type'        => 'wpestate_booking',
        'post_status'      => 'publish',
        'posts_per_page'   => -1,
        'meta_query' => array(
							'relation' => 'AND',
                            array(
                                'key'       => 'booking_id',
                                'value'     => $properties,
                                'type'      => 'NUMERIC',
                                'compare'   => 'IN'
                            )
                        )
        );
    

$booking_selection  =   new WP_Query($args);
	
	
	if ($booking_selection->have_posts()){    
	$prop_bookings = array(); 
        while ($booking_selection->have_posts()): $booking_selection->the_post();
            $pid            =   get_the_ID();
            $fromd        =   esc_html(get_post_meta($pid, 'booking_from_date', true));
            $tod            =   esc_html(get_post_meta($pid, 'booking_to_date', true));
            $listing        =   esc_html(get_post_meta($pid, 'booking_id', true));
            $email = "jrahill@playarentalproperties.com";
			$paid="reserved";
			
        $PropSum .= 'BEGIN:VEVENT
		';
		$PropSum .= "DTSTART;VALUE=DATE:".$fromd.'
		';
		$PropSum .= "DTEND;VALUE=DATE:".$tod.'
		';
		$PropSum .= 'UID:'.$pid.'
		';
		$PropSum .= 'SUMMARY:'.'Booking'.'
		'; 
		$PropSum .= "END:VEVENT
		";
		$prop_bookings[$pid][$pid]['ID'] = trim($pid);
		$prop_bookings[$pid][$pid]['checkin'] = trim($fromd);
		$prop_bookings[$pid][$pid]['checkout'] = trim($tod);
            
        endwhile;
         
        wp_reset_query();
		
    }       
	else {
		echo 'No Results. Please make sure to include the dates in the URL with the correct format.';
		
	}
		header('Content-Type: application/json');
		echo json_encode($prop_bookings);	
	
?>
