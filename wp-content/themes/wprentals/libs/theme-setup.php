<?php
///////////////////////////////////////////////////////////////////////////////////////////
/////// Theme Setup
///////////////////////////////////////////////////////////////////////////////////////////



//add_action( 'after_setup_theme', 'wp_estate_setup',99 );
if( !function_exists('wp_estate_setup') ):
function wp_estate_setup() {  
    global $pagenow;
   

    if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
     
    
         ////////////////////  insert sales and rental categories 
        $actions = array(   'Entire home',
                            'Private room',
                            'Shared room'
                        );

        foreach ($actions as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' =>sanitize_title($key)
            );
        

            if(!term_exists($key, 'property_action_category', $my_cat) ){
                $return =  wp_insert_term($key, 'property_action_category',$my_cat);
            }
        }

        ////////////////////  insert listings type categories 
        $actions = array(   'Apartment', 
                            'B & B', 
                            'Cabin', 
                            'Condos',
                            'Dorm',
                            'House',
                            'Condos',
                            'Villa',
                        );

        foreach ($actions as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' =>sanitize_title($key)
            );
        
            if(!term_exists($key, 'property_category') ){
                wp_insert_term($key, 'property_category');
            }
        }  
        
        
        $page_check = get_page_by_title('Advanced Search');
        if (!isset($page_check->ID)) {
            $my_post = array(
                'post_title' => 'Advanced Search',
                'post_type' => 'page',
                'post_status' => 'publish',
            );
            $new_id = wp_insert_post($my_post);
            update_post_meta($new_id, '_wp_page_template', 'advanced_search_results.php');
        }
        
      
    }// end if activated
      
      
        add_option('wp_estate_show_top_bar_user_login','yes');
        add_option('wp_estate_show_top_bar_user_menu','no');
        add_option('wp_estate_show_adv_search_general','yes');
       
        add_option('wp_estate_currency_symbol', '$');
        add_option('wp_estate_where_currency_symbol', 'before');
        add_option('wp_estate_measure_sys','ft');
        add_option('wp_estate_facebook_login', 'no');
        add_option('wp_estate_google_login', 'no');
        add_option('wp_estate_yahoo_login', 'no');
        add_option('wp_estate_social_register_on','no');
        add_option('wp_estate_wide_status', 1);
        add_option('wp_estate_header_type', 4); 
        add_option('wp_estate_user_header_type', 0); 
        add_option('wp_estate_prop_no', '12');

        add_option('wp_estate_show_empty_city', 'no');
        add_option('wp_estate_blog_sidebar', 'right');
        add_option('wp_estate_blog_sidebar_name', 'primary-widget-area');
   
        add_option('wp_estate_general_latitude', '40.781711');
        add_option('wp_estate_general_longitude', '-73.955927');
        add_option('wp_estate_default_map_zoom', '15');
        add_option('wp_estate_cache', 'no');
        add_option('wp_estate_ondemandmap', 'no');
        add_option('wp_estate_show_adv_search_map_close', 'yes');
        add_option('wp_estate_pin_cluster', 'yes');
        add_option('wp_estate_zoom_cluster', 10);
        add_option('wp_estate_hq_latitude', '40.781711');
        add_option('wp_estate_hq_longitude', '-73.955927');
        add_option('wp_estate_geolocation_radius', 1000);
        add_option('wp_estate_min_height', 550);
        add_option('wp_estate_max_height', 650);
        add_option('wp_estate_keep_min', 'no');
        add_option('wp_estate_paid_submission', 'no');
        add_option('wp_estate_admin_submission', 'yes');
        add_option('wp_estate_price_submission', 0);
        add_option('wp_estate_price_featured_submission', 0);
        add_option('wp_estate_submission_curency', 'USD');
        add_option('wp_estate_paypal_api', 'sandbox');     
        add_option('wp_estate_free_mem_list', 0);
        add_option('wp_estate_free_feat_list', 0);
        add_option('show_adv_search_slider','yes');
        add_option('wp_estate_delete_orphan','no');
        $custom_fields=array(
                    array('Check-in hour','Check-in hour','short text',1),
                    array('Check-Out hour','Check-Out hour','short text',2),
                    array('Late Check-in','Late Check-in','short text',3),
                    array('Optional services','Optional services','short text',4),
                    array('Outdoor facilities','Outdoor facilities','short text',5),
                    array('Extra People','Extra People','short text',6),
                    array('Cancellation','Cancellation','short text',7),
                    );
        add_option( 'wp_estate_custom_fields', $custom_fields); 
        
        add_option('wp_estate_custom_advanced_search', 'no');
        add_option('wp_estate_adv_search_type', 1);
        add_option('wp_estate_show_adv_search', 'yes');
        add_option('wp_estate_show_adv_search_map_close', 'yes');
        add_option('wp_estate_cron_run', time());
        $default_feature_list='Kitchen,Internet,Smoking Allowed,TV,Wheelchair Accessible,Elevator in Building,Indoor Fireplace,Heating,Essentials,Doorman,Pool,Washer,Hot Tub,Dryer,Gym,Free Parking on Premises,Wireless Internet,Pets Allowed,Family/Kid Friendly,Suitable for Events,Non Smoking,Phone (booth/lines),Projector(s),Bar / Restaurant,Air Conditioner,Scanner / Printer,Fax';
        add_option('wp_estate_feature_list', $default_feature_list);
        add_option('wp_estate_show_no_features', 'yes');
        add_option('wp_estate_property_features_text', 'Property Features');
        add_option('wp_estate_property_description_text', 'Property Description');
        add_option('wp_estate_property_details_text',  'Property Details ');
        $default_status_list='verified';
        add_option('wp_estate_status_list', $default_status_list);
        add_option('wp_estate_slider_cycle', 0); 
        add_option('wp_estate_show_save_search', 'no'); 
        add_option('wp_estate_search_alert',1);
        
        
        // colors option
        add_option('wp_estate_color_scheme', 'no');
   
        add_option('wp_estate_show_g_search', 'no');
        add_option('wp_estate_show_adv_search_extended', 'yes');
        add_option('wp_estate_readsys', 'no');
        add_option('wp_estate_enable_stripe','no');    
        add_option('wp_estate_enable_paypal','no');    
        add_option('wp_estate_enable_direct_pay','no'); 
        add_option('wp_estate_logo_margin',27);
        add_option('wp_estate_free_feat_list_expiration', 0);
        add_option('wp_estate_transparent_menu', 'no');
        add_option('wp_estate_transparent_menu_listing', 'no');
        add_option('wp_estate_date_lang','en-GB');
        
        add_option('wp_estate_show_slider_min_price',0);
        add_option('wp_estate_show_slider_max_price',2500);
        
        
        add_option('wp_estate_listing_unit_type',2);
        add_option('wp_estate_listing_page_type',1);
        add_option('wp_estate_adv_search_type','newtype');
        add_option('wp_estate_listing_unit_style_half',1);
        add_option('wp_estate_auto_curency','no');
        add_option('wp_estate_prop_list_slider','no');
        
        add_option('wp_estate_separate_users','no');
        add_option('wp_estate_publish_only','');
        add_option('wp_estate_show_adv_search_general','yes');
        
        add_option('wp_estate_show_submit','yes');
        add_option('wp_estate_setup_weekend',0);
         
        
        add_option('wp_estate_use_captcha_status','no');
        add_option('wp_estate_enable_user_pass','no');
        add_option('wp_estate_enable_direct_pay','no');   
        //  
        add_option('wp_estate_logo_header_type','type1');
        add_option('wp_estate_logo_header_align','left');
        add_option('wp_estate_wide_header','no');
        add_option('wp_estate_logo_header_select','type2'); 
        add_option('wp_estate_logo_margin',0);
        add_option('wp_estate_wide_footer','no');
        add_option('wp_estate_map_max_pins',30);
        
        add_option('wp_estate_guest_dropdown_no','15');
        add_option('wp_estate_month_no_show','12');
        add_option('wp_estate_theme_slider_type','type1');   
        add_option('wp_estate_date_format',0);
        
        add_option ('wp_estate_item_rental_type',0);
        add_option ('wp_estate_adv_search_fields_no',3);
        add_option ('wp_estate_search_fields_no_per_row',3);
        add_option ('wp_estate_use_custom_icon_area','no');
        
        add_option ('wp_estate_use_custom_icon_area','no');
        add_option ('wp_estate_use_price_pins_full_price','no');
        add_option ('wp_estate_show_adv_search_extended','yes');
        
        
        
        $adv_search_what_classic_half    =   array('Location','check_in','check_out','guest_no','property_rooms','property_category','property_action_category','property_bedrooms','property_bathrooms','property_price');
        $adv_search_how_classic_half     =   array('like','like','like','greater','greater','like','like','greater','greater','between');
            
        add_option('wp_estate_adv_search_what_half',$adv_search_what_classic_half);
        add_option('wp_estate_adv_search_how_half',$adv_search_how_classic_half);
        
        $adv_search_what    =   array('Location','check_in','check_out','guest_no');
        $adv_search_how     =   array('like','like','like','greater');
            
        add_option('wp_estate_adv_search_what_classic',$adv_search_what);
        add_option('wp_estate_adv_search_how_classic',$adv_search_how);
        add_option('wp_estate_show_menu_dashboard','yes');   
        add_option('wp_estate_show_city_drop_submit','no');  
        add_option('wp_estate_show_guest_number','yes');
            
        add_option('wp_estate_mandatory_page_fields','');
        $all_submission_fields_default=Array ( 
            'prop_category_submit',
            'prop_action_category_submit', 
            'property_city_front', 
            'property_area_front', 
            'property_description', 
            'property_price', 
            'property_taxes', 
            'property_price_per_week', 
            'property_price_per_month', 
            'price_per_weekeend', 
            'cleaning_fee', 
            'city_fee', 
            'min_days_booking', 
            'security_deposit', 
            'early_bird_percent', 
            'extra_price_per_guest', 
            'checkin_change_over', 
            'checkin_checkout_change_over', 
            'extra_options', 
            'custom_prices', 
            'attachid', 
            'embed_video_id', 
            'embed_video_type', 
            'property_size', 
            'property_rooms', 
            'property_bedrooms', 
            'property_bathrooms', 
            'property_address', 
            'property_zip', 
            'property_county', 
            'property_state', 
            'property_map', 
            'property_latitude', 
            'property_longitude', 
            'google_camera_angle', 
            'avalability_calendar', 
            'check-in-hour', 
            'check-out-hour', 
            'late-check-in', 
            'optional-services', 
            'outdoor-facilities', 
            'extra-people', 
            'cancellation', 
            'kitchen', 
            'internet', 
            'smoking_allowed', 
            'tv', 
            'wheelchair_accessible', 
            'elevator_in_building', 
            'indoor_fireplace', 
            'heating', 
            'essentials', 
            'doorman', 
            'pool', 
            'washer', 
            'hot_tub', 
            'dryer', 
            'gym', 
            'free_parking_on_premises', 
            'wireless_internet', 
            'pets_allowed', 
            'family-kid_friendly', 
            'suitable_for_events', 
            'non_smoking', 
            'phone_booth-lines', 
            'projectors', 
            'bar_-_restaurant', 
            'air_conditioner', 
            'scanner_-_printer', 
            'fax' );
        add_option('wp_estate_submission_page_fields',$all_submission_fields_default);
        
        add_option('wp_estate_use_float_search_form','yes');
        add_option('wp_estate_float_form_top','20%');
        add_option('wp_estate_float_form_top_tax','15%');
        
        add_option('wp_estate_search_on_start','no');
        // defaul emails 
        

        // agent_update_profile
            
        $to_save=__('Profile Update','wprentals');
        add_option ('wp_estate_subject_agent_update_profile',$to_save);
        
        $to_save=__('A user updated his profile on %website_url.
Username: %user_login','wprentals');
        add_option ('wp_estate_agent_update_profile',$to_save);

		// User ID Verification
		$to_save = __( 'New User ID verification', 'wprentals' );
		add_option( 'wp_estate_subject_new_user_id_verification', $to_save );

		$to_save = __( 'A user added his User ID verification image on %website_url.
Username: %user_login.
You can check the verification status here: %user_profile_url', 'wprentals' );
		add_option( 'wp_estate_new_user_id_verification', $to_save );
        
         // password_reset_request
        $to_save=__('Password Reset Request','wprentals');
        add_option ('wp_estate_subject_password_reset_request',$to_save);
        
        $to_save=__('Someone requested that the password be reset for the following account:
%website_url 
Username: %forgot_username .
If this was a mistake, just ignore this email and nothing will happen. To reset your password, visit the following address:%reset_link,
Thank You!','wprentals');
        add_option ('wp_estate_password_reset_request',$to_save);
        
        
         // password_reseted
        $to_save=__('Your Password was Reset','wprentals');
        add_option ('wp_estate_subject_password_reseted',$to_save);
        
        $to_save=__('Your new password for the account at: %website_url: 
Username:%user_login, 
Password:%user_pass
You can now login with your new password at: %website_url','wprentals');
        add_option ('wp_estate_password_reseted',$to_save);
  
        // purchase_activated
        $to_save=__('Your purchase was activated','wprentals');
        add_option ('wp_estate_subject_purchase_activated',$to_save);
        
        $to_save=__('Hi there,
Your purchase on  %website_url is activated! You should go check it out.','wprentals');
        add_option ('wp_estate_purchase_activated',$to_save);
          
         // approved_listing
        $to_save=__('Your listing was approved','wprentals');
        add_option ('wp_estate_subject_approved_listing',$to_save);
        
        $to_save=__('Hi there,
Your listing, %property_title was approved on  %website_url ! The listing is: %property_url.
You should go check it out.','wprentals');
        add_option ('wp_estate_approved_listing',$to_save);
        
        
        $to_save=__('New User Registration','wprentals');
        add_option ('wp_estate_subject_admin_new_user',$to_save);
        
        $to_save=__('New user registration on %website_url.
Username: %user_login_register, 
E-mail: %user_email_register','wprentals');
        add_option ('wp_estate_admin_new_user',$to_save);
        
        
        $to_save=__('Your username and password on %website_url','wprentals');
        add_option ('wp_estate_subject_new_user',$to_save);
        
        $to_save=__('Hi there,
Welcome to %website_url ! You can login now using the below credentials:
Username:%user_login_register
Password: %user_pass_register
If you have any problems, please contact me.
Thank you!','wprentals');
        add_option ('wp_estate_new_user',$to_save);
        
        $to_save=__('Expired Listing sent for approval on %website_url','wprentals');
        add_option ('wp_estate_subject_admin_expired_listing',$to_save);
        
        $to_save=__('Hi there,
A user has re-submited a new property on %website_url ! You should go check it out.
This is the property title: %submission_title.','wprentals');
        add_option ('wp_estate_admin_expired_listing',$to_save);
        
        //Paid Submissions  
        $to_save=__('New Paid Submission on %website_url','wprentals');
        add_option ('wp_estate_subject_paid_submissions',$to_save);
        
        $to_save=__('Hi there,
You have a new paid submission on  %website_url ! You should go check it out.','wprentals');
        add_option ('wp_estate_paid_submissions',$to_save);
        
        
        
         //Paid Submissions  
        $to_save=__('New Feature Upgrade on  %website_url','wprentals');
        add_option ('wp_estate_subject_featured_submission',$to_save);
        
        $to_save=__('Hi there,
You have a new featured submission on  %website_url ! You should go check it out.','wprentals');
        add_option ('wp_estate_featured_submission',$to_save);
        
        
        //account_downgraded  
        $to_save=__('Account Downgraded on %website_url','wprentals');
        add_option ('wp_estate_subject_account_downgraded',$to_save);
        
        $to_save=__('Hi there,
You downgraded your subscription on %website_url. Because your listings number was greater than what the actual package offers, we set the status of all your listings to expired. You will need to choose which listings you want live and send them again for approval.
Thank you!','wprentals');
        add_option ('wp_estate_account_downgraded',$to_save);
        
        
        //Membership Cancelled
        $to_save=__('Membership Cancelled on %website_url','wprentals');
        add_option ('wp_estate_subject_membership_cancelled',$to_save);
        
        $to_save=__('Hi there,
Your subscription on %website_url was cancelled because it expired or the recurring payment from the merchant was not processed. All your listings are no longer visible for our visitors but remain in your account.
Thank you.','wprentals');
        add_option ('wp_estate_membership_cancelled',$to_save);
        
         // Membership Activated
        $to_save=__('Membership Activated on %website_url','wprentals');
        add_option ('wp_estate_subject_membership_activated',$to_save);
        
        $to_save=__('Hi there,
Your new membership on %website_url is activated! You should go check it out.','wprentals');
        add_option ('wp_estate_membership_activated',$to_save);


        
        //Free Listing expired
        $to_save=__('Free Listing expired on %website_url','wprentals');
        add_option ('wp_estate_subject_free_listing_expired',$to_save);
        
        $to_save=__('Hi there,
One of your free listings on  %website_url has expired. The listing is %expired_listing_url.
Thank you!','wprentals');
        add_option ('wp_estate_free_listing_expired',$to_save);

        //New Listing Submission
        $to_save=__('New Listing Submission on %website_url','wprentals');
        add_option ('wp_estate_subject_new_listing_submission',$to_save);
        
        $to_save=__('Hi there,
A user has submited a new property on %website_url ! You should go check it out.This is the property title %new_listing_title!','wprentals');
        add_option ('wp_estate_new_listing_submission',$to_save);

        //listing edit
        $to_save=__('Listing Edited on %website_url','wprentals');
        add_option ('wp_estate_subject_listing_edit',$to_save);
        
        $to_save=__('Hi there,
A user has edited one of his listings  on %website_url ! You should go check it out. The property name is : %editing_listing_title!','wprentals');
        add_option ('wp_estate_listing_edit',$to_save);
         

        //recurring_payment
        $to_save=__('Recurring Payment on %website_url','wprentals');
        add_option ('wp_estate_subject_recurring_payment',$to_save);
        
        $to_save=__('Hi there,
We charged your account on %merchant for a subscription on %website_url ! You should go check it out.','wprentals');
        add_option ('wp_estate_recurring_payment',$to_save);
        
        
        
        
        //bookingconfirmeduser
        $to_save=__('Booking Confirmed on %website_url','wprentals');
        add_option ('wp_estate_subject_bookingconfirmeduser',$to_save);
        
        $to_save=__('Hi there,
Your booking made on %website_url was confirmed! You can see all your reservations by logging in your account and visiting My Reservations page.','wprentals');
        add_option ('wp_estate_bookingconfirmeduser',$to_save);
        
        //bookingconfirmed
        $to_save=__('Booking Confirmed on %website_url','wprentals');
        add_option ('wp_estate_subject_bookingconfirmed',$to_save);
        
        $to_save=__('Hi there,
Somebody confirmed a booking on %website_url! You should go and check it out!Please remember that the confirmation is made based on the payment confirmation of a non-refundable fee of the total invoice cost, processed through %website_url and sent to website administrator. ','wprentals');
        add_option ('wp_estate_bookingconfirmed',$to_save);
        
         //bookingconfirmed_nodeposit
        $to_save=__('Booking Confirmed on %website_url','wprentals');
        add_option ('wp_estate_subject_bookingconfirmed_nodeposit',$to_save);
        
        $to_save=__('Hi there,
You confirmed a booking on %website_url! The booking was confirmed with no deposit!','wprentals');
        add_option ('wp_estate_bookingconfirmed_nodeposit',$to_save);
        
        
        
        //inbox
        $to_save=__('New Message on %website_url.','wprentals');
        add_option ('wp_estate_subject_inbox',$to_save);
        
        $to_save=__('Hi there,
You have a new message on %website_url! You should go and check it out!
The message is:
%content','wprentals');
        add_option ('wp_estate_inbox',$to_save);
        
        
        //newbook
        $to_save=__('New Booking Request on %website_url.','wprentals');
        add_option ('wp_estate_subject_newbook',$to_save);
        
        $to_save=__('Hi there,
You have received a new booking request on %website_url !  Go to your account in Bookings page to see the request, issue the invoice or reject it!
The property is: %booking_property_link','wprentals');
        add_option ('wp_estate_newbook',$to_save);
        
        //mynewbook
        $to_save=__('You booked a period on %website_url.','wprentals');
        add_option ('wp_estate_subject_mynewbook',$to_save);
        
        $to_save=__('Hi there,
You have booked a period for your own listing on %website_url !  The reservation will appear in your account, under My Bookings. 
The property is: %booking_property_link','wprentals');
        add_option ('wp_estate_mynewbook',$to_save);
        
        //newinvoice
        $to_save=__('New Invoice on %website_url.','wprentals');
        add_option ('wp_estate_subject_newinvoice',$to_save);
        
        $to_save=__('Hi there,
An invoice was generated for your booking request on %website_url !  A deposit will be required for booking to be confirmed. For more details check out your account, My Reservations page.','wprentals');
        add_option ('wp_estate_newinvoice',$to_save);
        
        
         //deletebooking
        $to_save=__('Booking Request Rejected on %website_url','wprentals');
        add_option ('wp_estate_subject_deletebooking',$to_save);
        
        $to_save=__('Hi there,
One of your booking requests sent on %website_url was rejected by the owner. The rejected reservation is automatically removed from your account. ','wprentals');
        add_option ('wp_estate_deletebooking',$to_save);
        
         //deletebookinguser
        $to_save=__('Booking Request Cancelled on %website_url','wprentals');
        add_option ('wp_estate_subject_deletebookinguser',$to_save);
        
        $to_save=__('Hi there,
One of the unconfirmed booking requests you received on %website_url  was cancelled! The request is automatically deleted from your account!','wprentals');
        add_option ('wp_estate_deletebookinguser',$to_save);
        
         //deletebookingconfirmed
        $to_save=__('Booking Period Cancelled on %website_url.','wprentals');
        add_option ('wp_estate_subject_deletebookingconfirmed',$to_save);
        
        $to_save=__('Hi there,
One of your confirmed bookings on %website_url  was cancelled by property owner. ','wprentals');
        add_option ('wp_estate_deletebookingconfirmed',$to_save);
        
        
           // new_wire_transfer
        $to_save=__('You ordered a new Wire Transfer','wprentals');
        add_option ('wp_estate_subject_new_wire_transfer',$to_save);
        
        $to_save=__('We received your Wire Transfer payment request on  %website_url !
Please follow the instructions below in order to start submitting properties as soon as possible.
The invoice number is: %invoice_no, Amount: %total_price. 
Instructions:  %payment_details.','wprentals');
        add_option ('wp_estate_new_wire_transfer',$to_save);
        
        $to_save=__('Somebody ordered a new Wire Transfer','wprentals');
        add_option ('wp_estate_subject_admin_new_wire_transfer',$to_save);
        
        $to_save=__('Hi there,
You received a new Wire Transfer payment request on %website_url.
The invoice number is:  %invoice_no,  Amount: %total_price.
Please wait until the payment is made to activate the user purchase.','wprentals');
        add_option ('wp_estate_admin_new_wire_transfer',$to_save);
        
        $to_save=__('Invoice payment reminder','wprentals');
        add_option ('wp_estate_subject_full_invoice_reminder',$to_save);
        
        $to_save=__('Hi there,
We remind you that you need to fully pay the invoice no %invoice_id until  %until_date. This invoice is for booking no %booking_id on property %property_title with the url %property_url.
Thank you.','wprentals');
        add_option ('wp_estate_full_invoice_reminder',$to_save);
}
endif; // end   wp_estate_setup  
?>