<?php /* Template Name: Get iCal List */ 
get_header();

 the_title( '<h1>', '</h1>' );
 
 echo '<div class="col-md-12">';
	$propArgs = array(
	'post_type'=>'estate_property',
	'status'=>'publish',
	'posts_per_page'=>-1
	);

	// The Query
	$prop_query = new WP_Query( $propArgs );
	
	if ( $prop_query->have_posts() ) {
	while ( $prop_query->have_posts() ) {
	$prop_query->the_post();
	$postID = get_the_ID();
	$ical = get_post_meta($postID, 'unique_code_ica',true);
	//Do something
	echo '<div class=" row listing-row" style="padding:15px; border-bottom: 1px solid #eee;">';
	echo '<div class="col-md-4">' . the_title( '<h3>', '</h3>', false ) . '</div> <div class="col-md-8">https://playarentalproperties.com/ical-page/?ical=' . $ical;
	echo '</div></div><div style="clear:both;"></div>';
	}
		
	/* Restore original Post Data */
	wp_reset_postdata();
			
	} else {
		echo 'no posts found';
	}
echo '</div>';
get_footer();
?>