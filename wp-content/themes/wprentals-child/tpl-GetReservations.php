<?php
/* Template Name: Get Reservations */ 
isset($_GET['start_date'])? $start_date = $_GET['start_date'] : null;
isset($_GET['end_date'])? $end_date = $_GET['end_date'] : null;
$PropSum = '';
$args=array(
        'post_type'        => 'wpestate_booking',
        'post_status'      => 'any',
        'posts_per_page'   => -1,
        'meta_query' => array(
							'relation' => 'AND',
                            array(
                                'key'       => 'booking_from_date',
                                'value'     => $start_date,
                                'type'      => 'DATE',
                                'compare'   => '>='
                            ),
                            array(
                                'key'       =>  'booking_to_date',
                                'value'     =>  $end_date,
								'type'      => 'DATE',
                                'compare'   =>  '<='
                            )
                        )
        );
    

$booking_selection  =   new WP_Query($args);
	
	
	if ($booking_selection->have_posts()){    
	$PropSum .= "<Reservations>";
        while ($booking_selection->have_posts()): $booking_selection->the_post();
            $pid            =   get_the_ID();
            $fromd          =   esc_html(get_post_meta($pid, 'booking_from_date', true));
            $tod            =   esc_html(get_post_meta($pid, 'booking_to_date', true));
            $listing        =   esc_html(get_post_meta($pid, 'booking_id', true));
            $email = "jrahill@playarentalproperties.com";
			$paid="reserved";
			
        $PropSum .= '<Reservation property_id="'.$listing.'" reservation_id="'.$pid.'">'; 
			$PropSum .= '<ArrivalDate>'. $fromd .'</ArrivalDate>';
			$PropSum .= '<DepartureDate>'. $tod .'</DepartureDate>';
			$PropSum .= '<Status value="'.$paid.'" ></Status>'; 
		$PropSum .= "</Reservation>";
            
        endwhile;
         
        wp_reset_query();
		$PropSum .= '</Reservations>'; 
    }       
	else {
		echo 'No Results. Please make sure to include the dates in the URL with the correct format.';
		
	}
		
		echo $PropSum;
	
?>
