<?php /* Template Name: Get Availability */ 
isset($_GET['property_id'])? $id = $_GET['property_id'] : null;
$PropSum = '';

if ( $id == null) {
	$propArgs = array(
	'post_type'=>'estate_property',
	'status'=>'publish',
	);

	// The Query
	$prop_query = new WP_Query( $propArgs );
	
	if ( $prop_query->have_posts() ) {
	$PropSum .= "<Availability>";
	while ( $prop_query->have_posts() ) {
	$prop_query->the_post();
	$postID = get_the_ID();
	$PropSum .= '<BookedStays property_id="'.$postID.'">';
		$PropSum .= wdp_flipkey_get_booking_dates($postID);
	$PropSum .= '</BookedStays>';  
	}
	$PropSum .= '</Availability>'; 
		echo $PropSum;
	/* Restore original Post Data */
	wp_reset_postdata();
			
	} else {
		echo 'no posts found';
	}

}
else
{
	
	$propArgs = array(
	'post_type'=>'estate_property',
	'p'=>$id,
	);

	// The Query
	$prop_query = new WP_Query( $propArgs );
	
	if ( $prop_query->have_posts() ) {
	$PropSum .= "<Availability>";
	while ( $prop_query->have_posts() ) {
	$prop_query->the_post();
	$postID = get_the_ID();
	$PropSum .= '<BookedStays property_id="'.$postID.'">';
		$PropSum .= wdp_flipkey_get_booking_dates($postID);
	$PropSum .= '</BookedStays>'; 
	}
	$PropSum .= '</Availability>'; 
		echo $PropSum;
	/* Restore original Post Data */
	wp_reset_postdata();
			
	} else {
		echo 'no posts found';
	}
	
}

?>