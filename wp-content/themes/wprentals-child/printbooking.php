<?php
/* Template Name: Print Booking */ 

isset($_GET['booking'])? $booking = $_GET['booking'] : null;
if ( $booking == null) {
	echo 'Booking is null';
} else {
	$bookingMeta = get_post_meta($booking);
	$propID = get_post_meta($booking, 'booking_id', true);
	$propTitle = get_post_field('post_title',$propID);
	$from = get_post_meta($booking, 'booking_from_date', true);
	$to = get_post_meta($booking, 'booking_to_date', true);
	$renterNAme = get_post_meta($booking, 'am_renter_name', true);
	$renterEmail = get_post_meta($booking, 'am_renter_email', true);
	$totalValue = get_post_meta($booking, 'am_total_value', true);
	$bookingContent = get_post_field('post_content', $booking);
	
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Print Booking</title>
	<style>
	td {padding:10px;}
	</style>
	</head>

	<body style="width:600px; margin:20px auto; border: 1px solid #000;">
	<table width="100%" border="1">
	<tr>
	<th><h3>Booking #<?php echo $booking; ?></h3></th>
	</tr>
	<tr>
	<td width="600">
	<?php echo 'Property: ' . $propTitle; ?>
	</td>
	</tr>
	<tr>
	<td>
	<?php echo 'Check-in: ' . $from; ?>
	</td>
	</tr>
	<tr>
	<td>
	<?php echo 'Check-out: ' . $to; ?>
	</td>
	</tr>
	<tr>
	<td width="600">
	<?php echo 'Name: ' . $renterNAme; ?>
	</td>
	</tr>
	<tr>
	<td width="600">
	<?php echo 'Email: ' . $renterEmail; ?>
	</td>
	</tr>
	<tr>
	<td width="600">
	<?php echo 'Booking Total:' . $totalValue; ?>
	</td>
	</tr>
	<tr>
	<td width="600">
	<?php
	echo 'Details: <br /><br />' . $bookingContent; ?>
	</td>
	</tr>
	
	</table>
	</body>

	</html>
	<?php
	}

?>
