<?php
/* Template Name: Import Bookings */ 
function httpGet($url){
  $ch = curl_init();  
  $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch,CURLOPT_HEADER, false); 
  //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
 // line 2
  curl_setopt($ch, CURLOPT_USERAGENT, $agent);

  $output=curl_exec($ch);

  curl_close($ch);
  return $output;
}

// Key: Room ID on playaren , Value: property ID on this site
$ll_properties = array(
94 => 83,
93 => 79,
91 => 119,
90 => 80,
89 => 128,
85 => 94,
83 => 124,
82 => 84,
80 => 75,
79 => 108,
78 => 78,
77 => 114,
76 => 105,
75 => 127,
69 => 125,
67 => 96,
68 => 122,
66 => 116,
65 => 123,
64 => 121,
63 => 85,
61 => 81,
53 => 97,
52 => 117,
50 => 109,
49 => 92,
48 => 113,
47 => 126,
44 => 106,
42 => 112,
41 => 111,
40 => 110,
37 => 103,
36 => 115,
29 => 88,
28 => 93,
27 => 91,
26 => 90,
24 => 89,
23 => 87,
22 => 86,
21 => 77,
20 => 74,
19 => 73,
18 => 39,
17 => 72,
15 => 118,
14 => 107,
13 => 104,
10 => 102,
8 => 101,
7 => 100,
6 => 99,
3 => 98,
5 => 82
);
$properties_bookings      = array();


// Save bookings for all properties
foreach( $ll_properties as $prop => $asc ){
	echo "searching for: $prop" . "<br>";
  $properties_bookings[$asc] = httpGet("oldwebsite.playarentalproperties.com/flipkey/bookings_export_wp.php?properties=$prop" );
}

$today = date("Y-m-d");

foreach ($properties_bookings as $property => $bookings  ) {
  if( !empty( $property) ){
      foreach( json_decode( $bookings) as $key => $booking ){
        foreach( $booking as $b ){
		  //Create WP post
		  //echo 'Check-in: ' . $b->checkin . '<br />';
		  //echo 'Check-out: ' . $b->checkout . '<br />';
		  //echo 'Listing: ' . $property . '<br /><br />';
		  $post_args = array (
		  'post_author' => '2',
		  'post_content' => 'Imported from the old PRP website.',
		  'post_title' => 'Imported Booking',
		  'post_status' => 'publish',
		  'post_type' => 'wpestate_booking',
		  'post_content' => $b->summary,
		  );
		  $newPost_ID = wp_insert_post($post_args);
		  $listingName = get_the_title($property);
		  update_post_meta($newPost_ID, 'booking_id', $property);
		  update_post_meta($newPost_ID, 'booking_status', 'confirmed');
		  update_post_meta($newPost_ID, 'owner_id', '2');
		  update_post_meta($newPost_ID, 'booking_from_date', $b->checkin);
		  update_post_meta($newPost_ID, 'booking_to_date', $b->checkout);
		  update_post_meta($newPost_ID, 'listing_name', $listingName);
		  
		  //Update to create calendar dates
		  wp_update_post($newPost_ID);
        };
       }
   }
}


function pr($e){
  echo "<pre>";
  print_r($e);
  echo "<pre>";
}



