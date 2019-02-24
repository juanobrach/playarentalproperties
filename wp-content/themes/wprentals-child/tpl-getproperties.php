<?php /* Template Name: Get Properties */ 
isset($_GET['property_id'])? $id = $_GET['property_id'] : null;
$PropSum = "<PropertySummary>";

if ( $id == null) {
	$propArgs = array(
	'post_type'=>'estate_property',
	'status'=>'publish',
	);

	// The Query
	$prop_query = new WP_Query( $propArgs );
	
	if ( $prop_query->have_posts() ) {
	while ( $prop_query->have_posts() ) {
	$prop_query->the_post();
	$postID = get_the_ID();
	$lu = get_the_modified_date( 'Y-m-d H:i:s', $postID ); 
	$PropSum .= '<Property property_id="'. $postID.'" last_update="'. $lu  .'"></Property >';	
	}
		$PropSum .= '</PropertySummary>';
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
		while ( $prop_query->have_posts() ) {
		$prop_query->the_post();
		
		$postID = get_the_ID();
		$lu = get_the_modified_date( 'Y-m-d H:i:s', $postID );
		$PropertyName = get_the_title ($postID);
		
		$add1 = "";
		$add2 = "";
		$city = "Playa del Carmen";
		$state = "Quintana Roo";
		$zipCode = '77710';
		$country = "Mexico";
		$latitude = get_post_meta($postID, 'property_latitude', 1);
		$longitude = get_post_meta($postID, 'property_longitude', 1);
		$MaximumOccupancy = get_post_meta($postID, 'guest_no', 1);
		$PropertyType = wp_get_post_terms($postID, 'property_category', array("fields" => "all"));
		$Beds = get_post_meta($postID, 'property_bedrooms', 1);
		$Baths = get_post_meta($postID, 'property_bathrooms', 1);
		$MinimumStayLength = 3;  
		$CheckIn = "03:00 PM"; 
		$CheckOut = "12:00 PM";
		$Currency = "USD";
		$UnitSize = "";
		$PropertyDescription = wp_strip_all_tags( get_the_content() );
		$RateRestrictions = "";	
		$LocationDescription = "";
		$Pets = "No";
		$Smoking = "No";
		$HandicapAccessible = "No";
		$ElderlyAccessible = "No";
		$rate1Label = get_post_meta($postID, 'rate1Title', 1);
		$rate1From = get_post_meta($postID, 'rate1From', 1);
		$rate1To = get_post_meta($postID, 'rate1To', 1);
		$rate1Value = get_post_meta($postID, 'rate1Value', 1);
		$rate2Label = get_post_meta($postID, 'rate2Title', 1);
		$rate2From = get_post_meta($postID, 'rate2From', 1);
		$rate2To = get_post_meta($postID, 'rate2To', 1);
		$rate2Value = get_post_meta($postID, 'rate2Value', 1);
		$rate3Label = get_post_meta($postID, 'rate3Title', 1);
		$rate3From = get_post_meta($postID, 'rate3From', 1);
		$rate3To = get_post_meta($postID, 'rate3To', 1);
		$rate3Value = get_post_meta($postID, 'rate3Value', 1);
		$rate4Label = get_post_meta($postID, 'rate4Title', 1);
		$rate4From = get_post_meta($postID, 'rate4From', 1);
		$rate4To = get_post_meta($postID, 'rate4To', 1);
		$rate4Value = get_post_meta($postID, 'rate4Value', 1);
		$rate5Label = get_post_meta($postID, 'rate5Title', 1);
		$rate5From = get_post_meta($postID, 'rate5From', 1);
		$rate5To = get_post_meta($postID, 'rate5To', 1);
		$rate5Value = get_post_meta($postID, 'rate5Value', 1);
		
		
		$PropSum .= '<Property property_id="'. $postID.'" last_update="'. $lu  .'">';	
		
		$PropSum .= '<PropertyName>'.$PropertyName.'</PropertyName>';
		
		$PropSum .= '<Address>';
			$PropSum .= '<Address1>'.$add1.'</Address1>';
			$PropSum .= '<Address2>'.$add2.'</Address2>';
			$PropSum .= '<City>'.$city.'</City>';
			$PropSum .= '<State>'.$state.'</State>';
			$PropSum .= '<ZipCode>'.$zipCode.'</ZipCode>';
			$PropSum .= '<Country>'.$country.'</Country>';
			$PropSum .= '<Latitude>'.$latitude.'</Latitude>';
			$PropSum .= '<Longitude>'.$longitude.'</Longitude>';
		$PropSum .='</Address>';
		
		$PropSum .= '<Details>';
			$PropSum .= '<MaximumOccupancy>'.$MaximumOccupancy.'</MaximumOccupancy>';
			$PropSum .= '<PropertyType>'. $PropertyType[0]->name .'</PropertyType>';
			$PropSum .= '<Bedrooms count="'. $Beds .'">';
				$PropSum .= '<Bedroom></Bedroom>';
			$PropSum .= '</Bedrooms>';
			$PropSum .= '<Bathrooms count="'. $Baths .'">';
				$PropSum .= '<Bathroom></Bathroom>';
			$PropSum .= '</Bathrooms>';
			$PropSum .= '<MinimumStayLength>'.$MinimumStayLength.'</MinimumStayLength>';
			$PropSum .= '<CheckIn>'.$CheckIn.'</CheckIn>';
			$PropSum .= '<CheckIn>'.$CheckOut.'</CheckIn>';
			$PropSum .= '<Currency>'.$Currency.'</Currency>';
			$PropSum .= '<UnitSize units="meters"></UnitSize>';
		$PropSum .= '</Details>';
		
		$PropSum .= '<Descriptions>';
			$PropSum .= '<PropertyDescription>'.$PropertyDescription.'</PropertyDescription>';
			$PropSum .= '<RateRestrictions>'.$RateRestrictions.'</RateRestrictions>';
			$PropSum .= '<LocationDescription>'.$LocationDescription.'</LocationDescription>';
		$PropSum .= '</Descriptions>';
		
		$PropSum .= '<Suitability>';
			$PropSum .= '<Pets value="'.$Pets.'"></Pets>';
			$PropSum .= '<Smoking value="'.$Smoking.'"></Smoking>';
			$PropSum .= '<HandicapAccessible value="'.$HandicapAccessible.'"></HandicapAccessible>';
			$PropSum .= '<ElderlyAccessible value="'.$ElderlyAccessible.'"></ElderlyAccessible>';
		$PropSum .= '</Suitability>';
		
		$PropSum .= '<Amenities>';
		$PropSum .= wdp_estate_listing_features($postID);
		$PropSum .= '</Amenities>';
		
		$PropSum .= '<Photos>';
		$PropSum .= wdp_get_ordered_images($postID);
		$PropSum .= '</Photos>';
		
		$PropSum .= '<Rates>';
			$PropSum .=  '<Rate>';
				$PropSum .=  '<Label>' . $rate1Label . '</Label>';
				$PropSum .=  '<StartDate>' . $rate1From . '</StartDate>';
				$PropSum .=  '<EndDate>' . $rate1To . '</EndDate>';
				$PropSum .=  '<DailyMinRate></DailyMinRate><DailyWeeknightRate></DailyWeeknightRate><DailyWeekendRate></DailyWeekendRate><WeeknightMinRate></WeeknightMinRate><WeekendMinRate></WeekendMinRate><WeekendMaxRate></WeekendMaxRate>';
				$PropSum .=  '<WeeklyMinRate>' . $rate1Value . '</WeeklyMinRate>';
				$PropSum .=  '<WeeklyMaxRate>' . $rate1Value . '</WeeklyMaxRate>';
				$PropSum .=  '<MonthlyMinRate></MonthlyMinRate><MonthlyMaxRate></MonthlyMaxRate><MinimumStayLength>7</MinimumStayLength><TurnDay></TurnDay>';
			$PropSum .=  '</Rate>';
			
			$PropSum .=  '<Rate>';
				$PropSum .=  '<Label>' . $rate2Label . '</Label>';
				$PropSum .=  '<StartDate>' . $rate2From . '</StartDate>';
				$PropSum .=  '<EndDate>' . $rate2To . '</EndDate>';
				$PropSum .=  '<DailyMinRate></DailyMinRate><DailyWeeknightRate></DailyWeeknightRate><DailyWeekendRate></DailyWeekendRate><WeeknightMinRate></WeeknightMinRate><WeekendMinRate></WeekendMinRate><WeekendMaxRate></WeekendMaxRate>';
				$PropSum .=  '<WeeklyMinRate>' . $rate2Value . '</WeeklyMinRate>';
				$PropSum .=  '<WeeklyMaxRate>' . $rate2Value . '</WeeklyMaxRate>';
				$PropSum .=  '<MonthlyMinRate></MonthlyMinRate><MonthlyMaxRate></MonthlyMaxRate><MinimumStayLength>7</MinimumStayLength><TurnDay></TurnDay>';
			$PropSum .=  '</Rate>';
			
			$PropSum .=  '<Rate>';
				$PropSum .=  '<Label>' . $rate3Label . '</Label>';
				$PropSum .=  '<StartDate>' . $rate3From . '</StartDate>';
				$PropSum .=  '<EndDate>' . $rate3To . '</EndDate>';
				$PropSum .=  '<DailyMinRate></DailyMinRate><DailyWeeknightRate></DailyWeeknightRate><DailyWeekendRate></DailyWeekendRate><WeeknightMinRate></WeeknightMinRate><WeekendMinRate></WeekendMinRate><WeekendMaxRate></WeekendMaxRate>';
				$PropSum .=  '<WeeklyMinRate>' . $rate3Value . '</WeeklyMinRate>';
				$PropSum .=  '<WeeklyMaxRate>' . $rate3Value . '</WeeklyMaxRate>';
				$PropSum .=  '<MonthlyMinRate></MonthlyMinRate><MonthlyMaxRate></MonthlyMaxRate><MinimumStayLength>7</MinimumStayLength><TurnDay></TurnDay>';
			$PropSum .=  '</Rate>';
			
			$PropSum .=  '<Rate>';
				$PropSum .=  '<Label>' . $rate4Label . '</Label>';
				$PropSum .=  '<StartDate>' . $rate4From . '</StartDate>';
				$PropSum .=  '<EndDate>' . $rate4To . '</EndDate>';
				$PropSum .=  '<DailyMinRate></DailyMinRate><DailyWeeknightRate></DailyWeeknightRate><DailyWeekendRate></DailyWeekendRate><WeeknightMinRate></WeeknightMinRate><WeekendMinRate></WeekendMinRate><WeekendMaxRate></WeekendMaxRate>';
				$PropSum .=  '<WeeklyMinRate>' . $rate4Value . '</WeeklyMinRate>';
				$PropSum .=  '<WeeklyMaxRate>' . $rate4Value . '</WeeklyMaxRate>';
				$PropSum .=  '<MonthlyMinRate></MonthlyMinRate><MonthlyMaxRate></MonthlyMaxRate><MinimumStayLength>7</MinimumStayLength><TurnDay></TurnDay>';
			$PropSum .=  '</Rate>';
			
			$PropSum .=  '<Rate>';
				$PropSum .=  '<Label>' . $rate5Label . '</Label>';
				$PropSum .=  '<StartDate>' . $rate5From . '</StartDate>';
				$PropSum .=  '<EndDate>' . $rate5To . '</EndDate>';
				$PropSum .=  '<DailyMinRate></DailyMinRate><DailyWeeknightRate></DailyWeeknightRate><DailyWeekendRate></DailyWeekendRate><WeeknightMinRate></WeeknightMinRate><WeekendMinRate></WeekendMinRate><WeekendMaxRate></WeekendMaxRate>';
				$PropSum .=  '<WeeklyMinRate>' . $rate5Value . '</WeeklyMinRate>';
				$PropSum .=  '<WeeklyMaxRate>' . $rate5Value . '</WeeklyMaxRate>';
				$PropSum .=  '<MonthlyMinRate></MonthlyMinRate><MonthlyMaxRate></MonthlyMaxRate><MinimumStayLength>7</MinimumStayLength><TurnDay></TurnDay>';
			$PropSum .=  '</Rate>';
			
		$PropSum .= '</Rates>';
		
		$FeeRequired = "No";
		$FeeName = "";
		$FeeDescription =  "";
		$FeeCost =  "";
		$FeeType =  "";
			
		$PropSum .= '<Fees>';
			$PropSum .= '<Fee required="'.$FeeRequired.'">';
				$PropSum .= '<Name>'.$FeeName.'</Name>';
				$PropSum .= '<Description>'.$FeeDescription.'</Description>';
				$PropSum .= '<Cost type="flat">'.$FeeCost.'</Cost>';
				$PropSum .= '<FeeType type="fee">'.$FeeType.'</FeeType>';
			$PropSum .= '</Fee>';
		$PropSum .='</Fees>';
	
		$PropSum .= '</Property >';	
		}
			$PropSum .= '</PropertySummary>';
			echo $PropSum;
		/* Restore original Post Data */
		wp_reset_postdata();
			
	} else {
		echo 'no posts found';
	}
	
}

?>