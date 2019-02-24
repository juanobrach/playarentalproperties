<?php

///////////////////////////////////////////////////////////////////////////////////////////
/////// Js & Css include on front site 
///////////////////////////////////////////////////////////////////////////////////////////



if( !function_exists('wpestate_scripts') ):
function wpestate_scripts() {   
    global $post;
    $custom_image               =   '';
    $use_idx_plugins            =   0;
    $header_type                =   '';
  
    $adv_search_type_status     =   intval   ( get_option('wp_estate_adv_search_type',''));
    $home_small_map_status      =   esc_html ( get_option('wp_estate_home_small_map','') );
        
  
   
    if( isset($post->ID) ) {
        $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
    }
   
    $global_header_type         =   get_option('wp_estate_header_type','');
    if(is_singular('estate_agent')){
        $global_header_type         =   get_option('wp_estate_user_header_type','');
    }

    $listing_map                =   'internal';
    
    if( $header_type==5 || $global_header_type==4 ){
        $listing_map            =   'top';        
    }
    
    
    $slugs=array();
    $hows=array();
    $show_price_slider          =   'no';
    $slider_price_position      =   0;
            
 
    $custom_advanced_search='no';
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // load the css files
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    wp_enqueue_style('wpestate_bootstrap',get_template_directory_uri().'/css/bootstrap.css', array(), '1.0', 'all');
    wp_enqueue_style('wpestate_bootstrap-theme',get_template_directory_uri().'/css/bootstrap-theme.css', array(), '1.0', 'all');
    wp_enqueue_style('wpestate_style',get_stylesheet_uri(), array(), '1.0', 'all');  
    wp_enqueue_style('wpestate_media',get_template_directory_uri().'/css/my_media.css', array(), '1.0', 'all'); 
   
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    // load the general js files
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    wp_enqueue_script("jquery");
    wp_enqueue_script("jquery-ui-slider");
    wp_enqueue_script("jquery-ui-datepicker");
    wp_enqueue_script("jquery-ui-autocomplete");
     wp_enqueue_script('slick-slider', get_template_directory_uri().'/js/slick.min.js',array(), '1.0', false); 
    if( is_page_template('user-dashboard-profile.php')  ){
        //   wp_enqueue_script('plupload-handlers');
    }
    wp_enqueue_script('wpestate_bootstrap', trailingslashit( get_template_directory_uri() ).'js/bootstrap.min.js',array(), '1.0', false);
    wp_enqueue_script('wpestate_viewport', trailingslashit( get_template_directory_uri() ).'js/jquery.viewport.mini.js',array(), '1.0', false);
    wp_enqueue_script('wpestate_modernizr', trailingslashit( get_template_directory_uri() ).'js/modernizr.custom.62456.js',array(), '1.0', false);     
    wp_enqueue_script('wpestate_jquery.fancybox.pack', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox.pack.js',array('jquery'), '1.0', true); 
    wp_enqueue_script('wpestate_jquery.fancybox-thumbs', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox-thumbs.js',array('jquery'), '1.0', true); 
    wp_enqueue_script('wpestate_jquery.placeholders', trailingslashit( get_template_directory_uri() ).'js/placeholders.min.js',array('jquery'), '1.0', true);
    wp_enqueue_script('wpestate_dense', trailingslashit( get_template_directory_uri() ).'js/dense.js',array('jquery'), '1.0', true);
    wp_enqueue_script('wpestate_touch-punch', trailingslashit( get_template_directory_uri() ).'js/jquery.ui.touch-punch.min.js',array('jquery'), '1.0', true); 
    wp_enqueue_script('wpestate_jquery.lazyloadxt.min', trailingslashit( get_template_directory_uri() ).'js/jquery.lazyload.min.js',array('jquery'), '1.0', true);
    wp_enqueue_style('wpestate_jquery.ui.theme', trailingslashit( get_template_directory_uri() ) . 'css/jquery-ui.min.css');
    wp_enqueue_script('latinise.min', get_template_directory_uri().'/js/latinise.min_.js',array('jquery'), '1.0', true);
   
   
    if( !is_tax() && get_post_type() === 'estate_property' ) {
        wp_enqueue_script('wpestate_jquery.fancybox.pack', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox.pack.js',array('jquery'), '1.0', true); 
        wp_enqueue_script('wpestate_jquery.fancybox-thumbs', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox-thumbs.js',array('jquery'), '1.0', true); 
        wp_enqueue_style('wpestate_fancybox', trailingslashit( get_template_directory_uri() ).'css/jquery.fancybox.css', array(), '1.0', 'all'); 
    }
    
    $date_lang_status= esc_html ( get_option('wp_estate_date_lang','') );
    
    if($date_lang_status!='xx'){
        $handle="datepicker-".$date_lang_status;
        $name="datepicker-".$date_lang_status.".js";
        wp_enqueue_script($handle, trailingslashit( get_template_directory_uri() ).'js/i18n/'.$name,array('jquery'), '1.0', true);
    }
   
    
    if ( is_page_template('user_dashboard_edit_listing.php') ||  is_page_template('user_dashboard_profile.php') ){
        wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_script("jquery-ui-sortable");              
    }
    
    $use_generated_pins =   0;
    $load_extra         =   0;
    $post_type          =   get_post_type();

    if( is_page_template('advanced_search_results.php') || is_page_template('property_list_half.php') || is_page_template('property_list.php') || is_tax() || $post_type=='estate_agent' ){    // search results -> pins are added  from template   
        $use_generated_pins=1;
        $json_string=array();
        $json_string=json_encode($json_string);
    }else{
        // google maps pins
        if ( get_option('wp_estate_readsys','') =='yes' ){
           
            $path= wpestate_get_pin_file_path_read();
            $request = wp_remote_get($path);
            $json_string = wp_remote_retrieve_body( $request );
        }else{
      
            $json_string= wpestate_listing_pins();
        }
    }

    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // load the Google Maps js files
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $show_g_search_status= esc_html ( get_option('wp_estate_show_g_search','') );
        
    if ( is_ssl() ) {
        wp_enqueue_script('wpestate_googlemap', 'https://maps-api-ssl.google.com/maps/api/js?v=3&libraries=places,geometry&amp;language=en&amp;key='.esc_html(get_option('wp_estate_api_key', '') ),array('jquery'), '1.0', false);        
    }else{
        wp_enqueue_script('wpestate_googlemap', 'http://maps.googleapis.com/maps/api/js?v=3&libraries=places,geometry&amp;language=en&amp;key='.esc_html(get_option('wp_estate_api_key', '') ),array('jquery'), '1.0', false);        
    }
    wp_enqueue_script('wpestate_infobox',  trailingslashit( get_template_directory_uri() ) .'js/infobox.js',array('jquery'), '1.0', true); 
    
   
    $pin_images=wpestate_pin_images();
    $geolocation_radius =   esc_html ( get_option('wp_estate_geolocation_radius','') );
    if ($geolocation_radius==''){
          $geolocation_radius =1000;
    }
    $pin_cluster_status =   esc_html ( get_option('wp_estate_pin_cluster','') );
    $zoom_cluster       =   esc_html ( get_option('wp_estate_zoom_cluster ','') );
    $show_adv_search    =   esc_html ( get_option('wp_estate_show_adv_search_map_close','') );
    
    if( isset($post->ID) ){
        $page_lat           =   wpestate_get_page_lat($post->ID);
        $page_long          =   wpestate_get_page_long($post->ID);  
        $page_custom_zoom   =   wpestate_get_page_zoom($post->ID); 
        $page_custom_zoom_prop   =   get_post_meta($post->ID,'page_custom_zoom',true);
        $closed_height      =   wpestate_get_current_map_height($post->ID);
        $open_height        =   wpestate_get_map_open_height($post->ID);
        $open_close_status  =   wpestate_get_map_open_close_status($post->ID);  
    }else{
        $page_lat           =   esc_html( get_option('wp_estate_general_latitude','') );
        $page_long          =   esc_html( get_option('wp_estate_general_longitude','') );
        $page_custom_zoom   =   esc_html( get_option('wp_estate_default_map_zoom','') ); 
        $page_custom_zoom_prop  =   15;
        $closed_height      =   intval (get_option('wp_estate_min_height',''));
        $open_height        =   get_option('wp_estate_max_height','');
        $open_close_status  =   esc_html( get_option('wp_estate_keep_min','' ) ); 
    }
   
    
    if( get_post_type() === 'estate_property' && !is_tax() && !is_search() && !is_tag() ){
        $load_extra =   1;
        $google_camera_angle    =   intval( esc_html(get_post_meta($post->ID, 'google_camera_angle', true)) );
        $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
        $global_header_type         =   get_option('wp_estate_header_type','');
        $small_map=0;
        if ( $header_type == 0 ){ // global
            if ($global_header_type != 4){
                $small_map=1;
            }
        }else{
            if($header_type!=5){
                $small_map=1;
            }
        }
        
        $single_json_string= wpestate_single_listing_pins($post->ID);
        
        wp_enqueue_script('wpestate_googlecode_property',trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_code_listing.js',array('jquery'), '1.0', true); 
        wp_localize_script('wpestate_googlecode_property', 'googlecode_property_vars', 
              array(  'general_latitude'  =>  esc_html( get_option('wp_estate_general_latitude','') ),
                      'general_longitude' =>  esc_html( get_option('wp_estate_general_longitude','') ),
                      'path'              =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                      'markers'           =>  $json_string,
                      'single_marker'     =>  $single_json_string,
                      'single_marker_id'  =>  $post->ID,
                      'camera_angle'      =>  $google_camera_angle,
                      'idx_status'        =>  $use_idx_plugins,
                      'page_custom_zoom'  =>  $page_custom_zoom_prop,
                      'current_id'        =>  $post->ID,
                      'generated_pins'    =>  0,
                      'small_map'          => $small_map
                   )
          );
        
      
   
  
    }else if( is_page_template('contact_page.php')  ){
        $load_extra =   1;
        if($custom_image    ==  ''){  
            wp_enqueue_script('wpestate_googlecode_contact', trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_code_contact.js',array('jquery'), '1.0', true);        
            $hq_latitude =  esc_html( get_option('wp_estate_hq_latitude','') );
            $hq_longitude=  esc_html( get_option('wp_estate_hq_longitude','') );

            if($hq_latitude==''){
                $hq_latitude='40.781711';
            }

            if($hq_longitude==''){
                $hq_longitude='-73.955927';
            }
            $json_string=wpestate_contact_pin(); 

        wp_localize_script('wpestate_googlecode_contact', 'googlecode_contact_vars', 
            array(  'hq_latitude'       =>  $hq_latitude,
                    'hq_longitude'      =>  $hq_longitude,
                    'path'              =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                    'markers'           =>  $json_string,
                    'page_custom_zoom'  =>  $page_custom_zoom,
                    'address'           =>  esc_html( get_option('wp_estate_co_address', '') )                     
                   )
          );
        }
       
    }else {
            if($header_type==5 || $global_header_type==4){           
                $load_extra =   1;
                 
                wp_enqueue_script('wpestate_googlecode_regular', trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_code.js',array('jquery'), '1.0', true);        
                wp_localize_script('wpestate_googlecode_regular', 'googlecode_regular_vars', 
                    array(  'general_latitude'  =>  $page_lat,
                            'general_longitude' =>  $page_long,
                            'path'              =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                            'markers'           =>  $json_string,
                            'idx_status'        =>  $use_idx_plugins,
                            'page_custom_zoom'  =>  $page_custom_zoom,
                            'generated_pins'    =>  $use_generated_pins,
                            'page_custom_zoom'  =>  $page_custom_zoom,
                            'on_demand_pins'    =>   esc_html ( get_option('wp_estate_ondemandmap','') )
                         )
                );

            }
    }         
    
   
    $measure_sys             = get_option('wp_estate_measure_sys','');
    
    $is_tax=0;  
    if( is_tax() ){
        $is_tax=1;  
    }
    
    
    $is_property_list=0;
    if ( is_page_template('property_list.php') || is_page_template('property_list_half.php') || is_page_template('advanced_search_results.php') ){
        $is_property_list=1;  
    }
    
    
    
    if ( is_page_template('user_dashboard_edit_listing.php') ){
        $load_extra =   1; 
    }
    
    if($load_extra ==   1){            
            $slugs                  =   array();
            $hows                   =   array();
            $adv_search_what        =   get_option('wp_estate_adv_search_what','');
            $adv_search_label       =   get_option('wp_estate_adv_search_label','');
            $adv_search_how         =   get_option('wp_estate_adv_search_how','');
           
          
            $slider_price_position  =   0;
            $counter                =   0;
            if(is_array($adv_search_what)){
                foreach($adv_search_what as $key=>$search_field){
                    $counter++;
                    if($search_field=='types'){  
                        $slugs[]='adv_actions';
                    }
                    else if($search_field=='categories'){
                        $slugs[]='adv_categ';
                    }  
                    else if($search_field=='cities'){
                        $slugs[]='advanced_city';
                    } 
                    else if($search_field=='areas'){
                        $slugs[]='advanced_area';
                    }
                    else if($search_field=='county / state'){
                        $slugs[]='county-state';
                    } 
                    else if($search_field=='property country'){
                        $slugs[]='property-country';
                    }else if (  $search_field=='property price'  ){
                        $slugs[]='property_price';
                        // $slugs[]='property_price';
                        $slider_price_position=$counter ;
                    }
                    else { 
                        $string       =    wpestate_limit45( sanitize_title ($adv_search_label[$key]) );              
                        $slug         =   sanitize_key($string);
                        $slugs[]=$slug;
                    }
                }
            }
            
            if(is_array($adv_search_how)){
                foreach($adv_search_how as $key=>$search_field){
                    $hows[]= $adv_search_how[$key];
                }
            }   
            
            
            
        $bypass_fit_bounds=0;
        if( isset($post->ID)    ){
            $bypass_fit_bounds=intval(get_post_meta($post->ID,'bypass_fit_bounds',true));   
        }
         
        if(is_tax()) {
            $bypass_fit_bounds=1;
        }
        wp_enqueue_script('wprentals_pin',trailingslashit( get_template_directory_uri() ).'js/google_js/rentals_pin.js',array('jquery'), '1.0', true);  
        wp_enqueue_script('wpestate_oms.min',trailingslashit( get_template_directory_uri() ).'js/google_js/oms.min.js',array('jquery'), '1.0', true);   
        wp_enqueue_script('wpestate_mapfunctions', trailingslashit( get_template_directory_uri() ).'js/google_js/mapfunctions.js',array('jquery'), '1.0', true);   
        wp_localize_script('wpestate_mapfunctions', 'mapfunctions_vars', 
            array(  'path'                 =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                    'pin_images'           =>  $pin_images ,
                    'geolocation_radius'   =>  $geolocation_radius,
                    'adv_search'           =>  $adv_search_type_status,
                    'in_text'              =>  esc_html__( ' in ','wprentals'),
                    'zoom_cluster'         =>  intval($zoom_cluster),
                    'user_cluster'         =>  $pin_cluster_status,
                    'open_close_status'    =>  $open_close_status,
                    'open_height'          =>  $open_height,
                    'closed_height'        =>  $closed_height,     
                    'generated_pins'       =>  $use_generated_pins,
                    'geo_no_pos'           =>  esc_html__( 'The browser couldn\'t detect your position!','wprentals'),
                    'geo_no_brow'          =>  esc_html__( 'Geolocation is not supported by this browser.','wprentals'),
                    'geo_message'          =>  esc_html__( 'm radius','wprentals'),
                    'show_adv_search'      =>  $show_adv_search,
                    'custom_search'        =>  $custom_advanced_search,
                    'listing_map'          =>  $listing_map,
                    'slugs'                =>  $slugs,
                    'hows'                 =>  $hows,
                    'measure_sys'          =>  $measure_sys,
                    'close_map'            =>  esc_html__( 'close map','wprentals'),
                    'show_g_search_status' =>  $show_g_search_status,
                    'slider_price'         =>  $show_price_slider,
                    'slider_price_position'=>  $slider_price_position,
                    'map_style'            =>  stripslashes (  get_option('wp_estate_map_style','') ),
                    'is_tax'               =>  $is_tax, 
                    'is_property_list'     =>  $is_property_list,
                    'bypass_fit_bounds'    =>  $bypass_fit_bounds,
                    'useprice'              =>  esc_html ( get_option('wp_estate_use_price_pins','')),
                    'use_price_pins_full_price' =>  esc_html ( get_option('wp_estate_use_price_pins_full_price','')),
                    'adv_search_type'           =>  get_option('wp_estate_adv_search_type',''),
                    'fields_no'                 =>  intval( get_option('wp_estate_adv_search_fields_no')),
                    'slugs'                     =>  $slugs,
                    'hows'                      =>  $hows,
                    )
            );   
        wp_enqueue_script('wpestate_markerclusterer', trailingslashit( get_template_directory_uri() ).'js/google_js/markerclusterer.js',array('jquery'), '1.0', true);  
    } // end load extra
    
  
    
         

    $login_redirect =   wpestate_get_template_link('user_dashboard_profile.php');
    $show_adv_search_map_close          =   esc_html ( get_option('wp_estate_show_adv_search_map_close','') ); 
    $max_file_size  = 100 * 1000 * 1000;
    $current_user = wp_get_current_user();
    $userID                     =   $current_user->ID; 
      
    
    $booking_array                  =   array();
    $custom_price                   =   '';
    $default_price                  =   '';
    $cleaning_fee_per_day           =   '';
    $city_fee_per_day               =   '';
    $price_per_guest_from_one       =   '';
    $checkin_change_over            =   '';
    $checkin_checkout_change_over   =   '';
    $min_days_booking               =   '';
    $extra_price_per_guest          =   '';
    $price_per_weekeend             =   '';
    $mega_details                   =   '';
    
    if(isset($post->ID)){
        $custom_price    =  json_encode(  wpml_custom_price_adjust($post->ID));
        
        
        $booking_array   =   json_encode(get_post_meta($post->ID, 'booking_dates',true  ));
        $default_price   =   get_post_meta($post->ID,'property_price',true);
        
        $cleaning_fee_per_day           =   intval  ( get_post_meta($post->ID,  'cleaning_fee_per_day', true) );
        $city_fee_per_day               =   intval   ( get_post_meta($post->ID, 'city_fee_per_day', true) );
        $price_per_guest_from_one       =   intval   ( get_post_meta($post->ID, 'price_per_guest_from_one', true) );
        $checkin_change_over            =   intval   ( get_post_meta($post->ID, 'checkin_change_over', true) );  
        $checkin_checkout_change_over   =   intval   ( get_post_meta($post->ID, 'checkin_checkout_change_over', true) );  
        $min_days_booking               =   intval   ( get_post_meta($post->ID, 'min_days_booking', true) );  
        $extra_price_per_guest          =   intval   ( get_post_meta($post->ID, 'extra_price_per_guest', true) );  
        $price_per_weekeend             =   intval   ( get_post_meta($post->ID, 'price_per_weekeend', true) );
        $mega_details                   =   json_encode( wpml_mega_details_adjust($post->ID));
    }
    
    $week_days_control=array(
        '0'=>esc_html__('None','wprentals'),
        '1'=>esc_html__('Monday','wprentals'), 
        '2'=>esc_html__('Tuesday','wprentals'),
        '3'=>esc_html__('Wednesday','wprentals'),
        '4'=>esc_html__('Thursday','wprentals'),
        '5'=>esc_html__('Friday','wprentals'),
        '6'=>esc_html__('Saturday','wprentals'),
        '7'=>esc_html__('Sunday','wprentals')
    );
       
    $submission_curency = wpestate_curency_submission_pick();
  
    
    //$direct_payment_details         =   wp_kses( get_option('wp_estate_direct_payment_details','') ,$argsx);
    if (function_exists('icl_translate') ){
        $mes =  stripslashes ( esc_html( get_option('wp_estate_direct_payment_details','') ) );
        $direct_payment_details      =   icl_translate('wpestate','wp_estate_property_direct_payment_text', $mes );
    }else{
        $direct_payment_details = stripslashes ( esc_html( get_option('wp_estate_direct_payment_details','') ) );
    }
    
    
      
    $wp_estate_book_down                =   floatval ( get_option('wp_estate_book_down', '') );
    $wp_estate_book_down_fixed_fee      =   floatval ( get_option('wp_estate_book_down_fixed_fee', '') );
    $include_expeses                    =   esc_html ( get_option('wp_estate_include_expenses','') );

    $dates_types=array(
                '0' =>'yy-mm-dd',
                '1' =>'yy-dd-mm',
                '2' =>'dd-mm-yy',
                '3' =>'mm-dd-yy',
                '4' =>'dd-yy-mm',
                '5' =>'mm-yy-dd',
        
    );
    $sticky_search = get_option('wp_estate_sticky_search');
    if( wpestate_is_user_dashboard() ){
        $sticky_search = 'no';
    }
    
    wp_enqueue_script('wpestate_control', trailingslashit( get_template_directory_uri() ).'js/control.js',array('jquery'), '1.0', true);   
    wp_localize_script('wpestate_control', 'control_vars', 
            array(  'searchtext'            =>   esc_html__( 'SEARCH','wprentals'),
                    'searchtext2'           =>   esc_html__( 'Search here...','wprentals'),
                    'path'                  =>   get_template_directory_uri(),
                    'search_room'           =>  esc_html__( 'Type Bedrooms No.','wprentals'),
                    'search_bath'           =>  esc_html__( 'Type Bathrooms No.','wprentals'),
                    'search_min_price'      =>  esc_html__( 'Type Min. Price','wprentals'),
                    'search_max_price'      =>  esc_html__( 'Type Max. Price','wprentals'),
                    'contact_name'          =>  esc_html__( 'Your Name','wprentals'),
                    'contact_email'         =>  esc_html__( 'Your Email','wprentals'),
                    'contact_phone'         =>  esc_html__( 'Your Phone','wprentals'),
                    'contact_comment'       =>  esc_html__( 'Your Message','wprentals'),
                    'zillow_addres'         =>  esc_html__( 'Your Address','wprentals'),
                    'zillow_city'           =>  esc_html__( 'Your City','wprentals'),
                    'zillow_state'          =>  esc_html__( 'Your State Code (ex CA)','wprentals'),
                    'adv_contact_name'      =>  esc_html__( 'Your Name','wprentals'),
                    'adv_email'             =>  esc_html__( 'Your Email','wprentals'),
                    'adv_phone'             =>  esc_html__( 'Your Phone','wprentals'),
                    'adv_comment'           =>  esc_html__( 'Your Message','wprentals'),
                    'adv_search'            =>  esc_html__( 'Send Message','wprentals'),
                    'admin_url'             =>  get_admin_url(),
                    'login_redirect'        =>  $login_redirect,
                    'login_loading'         =>  esc_html__( 'Sending user info, please wait...','wprentals'), 
                    'street_view_on'        =>  esc_html__( 'Street View','wprentals'),
                    'street_view_off'       =>  esc_html__( 'Close Street View','wprentals'),
                    'userid'                =>  $userID,
                    'show_adv_search_map_close'=>$show_adv_search_map_close,
                    'close_map'             =>  esc_html__( 'close map','wprentals'),
                    'open_map'              =>  esc_html__( 'open map','wprentals'),
                    'fullscreen'            =>  esc_html__( 'Fullscreen','wprentals'),
                    'default'               =>  esc_html__( 'Default','wprentals'),
                    'addprop'               =>  esc_html__( 'Please wait while we are processing your submission!','wprentals'),
                    'deleteconfirm'         =>  esc_html__( 'Are you sure you wish to delete?','wprentals'),
                    'terms_cond'            =>  esc_html__( 'You must to agree with terms and conditions!','wprentals'),
                    'slider_min'            =>  floatval(get_option('wp_estate_show_slider_min_price','')),
                    'slider_max'            =>  floatval(get_option('wp_estate_show_slider_max_price','')),
                    'bookconfirmed'         =>  esc_html__( 'Booking request sent. Please wait for owner\'s confirmation!','wprentals'),
                    'bookdenied'            =>  esc_html__( 'The selected period is already booked. Please choose a new one!','wprentals'),
                    'to'                    =>  esc_html__( 'to','wprentals'),
                    'curency'               =>  esc_html( get_option('wp_estate_currency_label_main', '') ),
                    'where_curency'         =>  esc_html( get_option('wp_estate_where_currency_symbol', '') ),
                    'price_separator'       =>  esc_html( get_option('wp_estate_prices_th_separator', '') ),
                    'datepick_lang'         =>  esc_html ( get_option('wp_estate_date_lang','') ),
                    'custom_price'          =>  $custom_price,
                    'booking_array'         =>  $booking_array,
                    'default_price'         =>  $default_price ,
                    'transparent_logo'      =>  get_option('wp_estate_transparent_logo_image', ''),
                    'normal_logo'                       =>  get_option('wp_estate_logo_image', ''),
                    'cleaning_fee_per_day'              =>   $cleaning_fee_per_day,         
                    'city_fee_per_day'                  =>   $city_fee_per_day,
                    'price_per_guest_from_one'          =>   $price_per_guest_from_one,
                    'checkin_change_over'               =>   $checkin_change_over,
                    'checkin_checkout_change_over'      =>   $checkin_checkout_change_over,
                    'min_days_booking'                  =>   $min_days_booking,
                    'extra_price_per_guest'             =>   $extra_price_per_guest,
                    'price_per_weekeend'                =>   $price_per_weekeend,
                    'setup_weekend_status'              =>   esc_html ( get_option('wp_estate_setup_weekend','') ),
                    'mega_details'                      =>   $mega_details,
                    'mindays'                           =>   esc_html__( 'The selected period is shorter than the minimum required period!','wprentals'),
                    'weekdays'                          =>   json_encode($week_days_control),
                    'stopcheckin'                       =>   esc_html__( 'Check in date is not correct','wprentals'),
                    'stopcheckinout'                    =>   esc_html__( 'Check in/Check out dates are not correct','wprentals'),  
                    'from'                              =>   esc_html__('from','wprentals'),
                    'separate_users'                    =>   esc_html ( get_option('wp_estate_separate_users','') )  ,
                    'captchakey'                        =>   get_option('wp_estate_recaptha_sitekey',''),
                    'usecaptcha'                        =>   get_option('wp_estate_use_captcha',''),
                    'unavailable_check'                 =>   esc_html__('Unavailable/Only Check Out','wprentals'),
                    'unavailable'                       =>   esc_html__('Unavailable','wprentals'),
                    'submission_curency'                =>   $submission_curency,
                    'direct_price'                      =>   esc_html__('To be paid','wprentals'),
                    'send_invoice'                      =>   esc_html__('Send me the invoice','wprentals'),
                    'direct_pay'                        =>   $direct_payment_details,
                    'direct_title'                      =>   esc_html__('Direct payment instructions','wprentals'),
                    'direct_thx'                        =>   esc_html__('Thank you. Please check your email for payment instructions.','wprentals'),
                    'guest_any'                         =>   esc_html__('any','wprentals'),
                    'my_reservations_url'               =>   wpestate_get_template_link('user_dashboard_my_reservations.php'),
                    'pls_wait'                          =>   esc_html__('processing, please wait...','wprentals'),
                    'wp_estate_book_down'               =>   $wp_estate_book_down,
                    'wp_estate_book_down_fixed_fee'     =>   $wp_estate_book_down_fixed_fee,
                    'include_expeses'                   =>   $include_expeses,
                    'date_format'                       =>   $dates_types[ intval( get_option('wp_estate_date_format','')  )],
                    'stiky_search'                      =>   $sticky_search,
                    'geo_radius_measure'                =>  get_option('wp_estate_geo_radius_measure',''),
                    'initial_radius'                    =>  get_option('wp_estate_initial_radius',''),
                    'min_geo_radius'                    =>  get_option('wp_estate_min_geo_radius',''),
                    'max_geo_radius'                    =>  get_option('wp_estate_max_geo_radius',''),
                
               
                )
     );
    
   
    
    $adv_search_type            =   get_option('wp_estate_adv_search_type','');
    $adv_search_what_half            =   get_option('wp_estate_adv_search_what','');
    $adv_search_how_half             =   get_option('wp_estate_adv_search_how','');
    
    if($adv_search_type=='newtype' || $adv_search_type=='oldtype' ){
        $adv_search_what_half   =   get_option('wp_estate_adv_search_what_half',true);
        $adv_search_how_half    =   get_option('wp_estate_adv_search_how_half',true);
            
    }  else if($adv_search_type=='type4' ){
        $adv_search_what_half[]='property_category';
        $adv_search_how_half[]='like';
    
        $adv_search_what_half[]='property_action_category';
        $adv_search_how_half[]='like';
    } 
            
    
    wp_enqueue_script('wpestate_ajaxcalls', trailingslashit( get_template_directory_uri() ).'js/ajaxcalls.js',array('jquery'), '1.0', true);   
    wp_localize_script('wpestate_ajaxcalls', 'ajaxcalls_vars', 
            array(  'contact_name'          =>  esc_html__( 'Your Name','wprentals'),
                    'contact_email'         =>  esc_html__( 'Your Email','wprentals'),
                    'contact_phone'         =>  esc_html__( 'Your Phone','wprentals'),
                    'contact_comment'       =>  esc_html__( 'Your Message','wprentals'),
                    'adv_contact_name'      =>  esc_html__( 'Your Name','wprentals'),
                    'adv_email'             =>  esc_html__( 'Your Email','wprentals'),
                    'adv_phone'             =>  esc_html__( 'Your Phone','wprentals'),
                    'adv_comment'           =>  esc_html__( 'Your Message','wprentals'),
                    'adv_search'            =>  esc_html__( 'Send Message','wprentals'),
                    'admin_url'             =>  get_admin_url(),
                    'login_redirect'        =>  $login_redirect,
                    'login_loading'         =>  esc_html__( 'Sending user info, please wait...','wprentals'), 
                    'userid'                =>  $userID,
                    'prop_featured'         =>  esc_html__( 'Property is featured','wprentals'),
                    'no_prop_featured'      =>  esc_html__( 'You have used all the "Featured" listings in your package.','wprentals'),
                    'favorite'              =>  esc_html__( 'Favorite','wprentals').'<i class="fas fa-heart"></i>',
                    'add_favorite'          =>  esc_html__( 'Add to Favorites','wprentals'),
                    'remove_favorite'       =>  esc_html__( 'remove from favorites','wprentals'),
                    'add_favorite_unit'     =>  esc_html__( 'add to favorites','wprentals'),
                    'saving'                =>  esc_html__( 'saving..','wprentals'),
                    'sending'               =>  esc_html__( 'sending message..','wprentals'),
                    'reserve'               =>  esc_html__( 'Reserve Period','wprentals'),
                    'paypal'                =>  esc_html__( 'Connecting to Paypal! Please wait...','wprentals'),
                    'stripecancel'          =>  esc_html__( 'subscription will be cancelled at the end of the current period','wprentals'),
                    'max_month_no'          =>  intval   ( get_option('wp_estate_month_no_show','') ),
                    'processing'            =>  esc_html__( 'processing..','wprentals'),
                    'home'                  =>  get_site_url(),
                    'delete_account'        =>  esc_html__('Confirm your ACCOUNT DELETION request! Clicking the button below will result your account data will be deleted. This means you will no longer be able to login to your account and access your account information: My Profile, My Reservations, My bookings, Invoices. This operation CAN NOT BE REVERSED!','wprentals'),
                    'adv_search_what_half'  =>  $adv_search_what_half,
                    'adv_search_how_half'   =>  $adv_search_how_half,
                    'adv_search_type'       =>  $adv_search_type
                )
     );
    
  
      
    if(is_page_template('user_dashboard_edit_listing.php') || is_page_template('user_dashboard_add_step1.php')   ){

        $page_lat   = esc_html( get_option('wp_estate_general_latitude','') );
        $page_long  = esc_html( get_option('wp_estate_general_longitude','') );
        wp_enqueue_script('wpestate_google_map_submit', trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_submit.js',array('jquery'), '1.0', true);  
        wp_localize_script('wpestate_google_map_submit', 'google_map_submit_vars', 
            array(  'general_latitude'  =>  $page_lat,
                    'general_longitude' =>  $page_long,    
                    'geo_fails'        =>  esc_html__( 'Geolocation was not successful for the following reason:','wprentals') 
                 )
        ); 
    }
      
      
    if(is_page_template('user_dashboard_allinone.php') || is_page_template('user_dashboard_edit_listing.php') || is_page_template('user_dashboard_add_step1.php') ||  ( 'estate_property' == get_post_type() )  ){
   
        $custom_fields          =   get_option( 'wp_estate_custom_fields', true);  
        $tranport_custom_array  =   array();
        $i=0;
        if( !empty($custom_fields)){  
            while($i< count($custom_fields) ){
                $name  =   $custom_fields[$i][0];
                $label =   $custom_fields[$i][1];
                $type  =   $custom_fields[$i][2];
                $slug  =   str_replace(' ','_',$name);

                $slug         =   wpestate_limit45(sanitize_title( $name ));
                $slug         =   sanitize_key($slug);
                $i++;
                $tranport_custom_array[]=$slug;
           }
        }
        
        $feature_list_array             =   array();
        $feature_list                   =   esc_html( get_option('wp_estate_feature_list') );
        $feature_list_array             =   explode( ',',$feature_list);
        $moving_array_amm               =   array();
        foreach($feature_list_array as $key => $value){
            $post_var_name      =   str_replace(' ','_', trim($value) );
            $post_var_name      =   wpestate_limit45(sanitize_title( $post_var_name ));
            $post_var_name      =   sanitize_key($post_var_name);
            $moving_array_amm[] =   $post_var_name;           
        }
            
       
        wp_enqueue_script('wpestate_ajaxcalls_add', trailingslashit( get_template_directory_uri() ).'js/ajaxcalls_add.js',array('jquery'), '1.0', true);   
        wp_localize_script('wpestate_ajaxcalls_add', 'ajaxcalls_add_vars', 
            array(  'admin_url'                 =>  get_admin_url(),
                    'tranport_custom_array'     =>  json_encode($tranport_custom_array),  
                    'transport_custom_array_amm'=>  json_encode($moving_array_amm),
                    'wpestate_autocomplete'     =>  get_option('wp_estate_wpestate_autocomplete',''),
                    'mandatory_fields'          =>  get_option('wp_estate_mandatory_page_fields',''),
                    'mandatory_fields_label'    =>  wpestate_return_all_fields(1),
                    'pls_fill'                  =>  esc_html__('Please complete these fields','wprentals'),
            )
        );
    
    }

    if ( is_user_logged_in() ) {
        $logged_in="yes";
    } else {
         $logged_in="no";
    }
    if( 'estate_property' == get_post_type() ||  'estate_agent' == get_post_type() ){
        
        $early_discount =  floatval(get_post_meta($post->ID, 'early_bird_percent', true));
        wp_enqueue_script('wpestate_property', trailingslashit( get_template_directory_uri() ).'js/property.js',array('jquery'), '1.0', true);   
        wp_localize_script('wpestate_property', 'property_vars', 
            array(  'plsfill'                 =>    esc_html__( 'Please fill all the forms:','wprentals'),
                    'sending'                 =>    esc_html__( 'Sending Request...','wprentals'),
                    'logged_in'               =>    $logged_in,
                    'notlog'                  =>    esc_html__( 'You need to log in order to book a listing!','wprentals'),
                    'viewless'                =>    esc_html__( 'View less','wprentals'),
                    'viewmore'                =>    esc_html__( 'View more','wprentals'),
                    'nostart'                 =>    esc_html__( 'Check in date cannot be bigger than Check out date','wprentals'),
                    'noguest'                 =>    esc_html__('Please select the number of guests','wprentals'),
                    'guestoverload'           =>    esc_html__('The number of guests is greater than the property capacity - ','wprentals'),
                    'guests'                  =>    esc_html__('guests','wprentals'),
                    'early_discount'          =>    $early_discount,
                    'rental_type'               =>  get_option('wp_estate_item_rental_type',true),
   
               )
        );
                
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////file upload ajax - profile and user dashboard
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    if( is_page_template('user_dashboard_profile.php') || is_page_template('user_dashboard_edit_listing.php')   ){

        $prop_id=0;
        if( isset($_GET['listing_edit']) && is_numeric($_GET['listing_edit'] ) ){
            $prop_id=intval($_GET['listing_edit']);
            
        }
        
    	$plup_url = add_query_arg( array(
            'action'    => 'wpestate_me_upload',
            'nonce'     =>  wp_create_nonce('aaiu_allow'),
            'propid'    =>  $prop_id,
        ), admin_url('admin-ajax.php') );
                
        $max_images = intval   ( get_option('wp_estate_prop_image_number','') );

	    $uploader_js = 'ajax-profile-upload';

	    $plupload_values = array(
		    'runtimes'          => 'html5,flash,html4',
		    'max_file_size'     => $max_file_size . 'b',
		    'url'               => $plup_url,
		    'file_data_name'    => 'aaiu_upload_file',
		    'flash_swf_url'     => includes_url('js/plupload/plupload.flash.swf'),
		    'filters'           => array(array('title' => esc_html__( 'Allowed Files','wprentals'), 'extensions' => "jpeg,jpg,gif,png,pdf")),
		    'multipart'         => true,
		    'urlstream_upload'  => true,
	    );

        if (is_page_template('user_dashboard_edit_listing.php')) {
        	$tmp_plupload_values = array(
		        'browse_button'     => 'aaiu-uploader',
		        'container'         => 'aaiu-upload-container',
	        );

	        $plupload_values = wp_parse_args($plupload_values,$tmp_plupload_values);
	        $uploader_js = 'ajax-upload';
        }
           
        wp_enqueue_script('ajax-upload', trailingslashit( get_template_directory_uri() ).'js/'.$uploader_js.'.js',array('jquery','plupload-handlers'), '1.0', true);
        wp_localize_script('ajax-upload', 'ajax_vars', 
            array(  'ajaxurl'           => admin_url('admin-ajax.php'),
                    'nonce'             => wp_create_nonce('aaiu_upload'),
                    'remove'            => wp_create_nonce('aaiu_remove'),
                    'number'            => 1,
                    'upload_enabled'    => true,
                    'warning'           =>  __('Image needs to be at least 500px height  x 500px wide!','wprentals'),
                    'max_images'        =>  $max_images,
                    'warning_max'      =>  __('You cannot upload more than','wprentals').' '.$max_images.' '.__('images','wprentals'),
                    'path'              =>  trailingslashit( get_template_directory_uri() ),
                    'confirmMsg'        => esc_html__( 'Are you sure you want to delete this?','wprentals'),
                    'plupload'         => $plupload_values
                
                )
                );
    }
     

     
     
    if ( is_singular() && get_option( 'thread_comments' ) ){
        wp_enqueue_script( 'comment-reply' );
    }
    
    
    if( get_post_type() === 'estate_property' && !is_tax() ){
        wp_enqueue_script('wpestate_property',trailingslashit( get_template_directory_uri() ).'js/property.js',array('jquery'), '1.0', true); 
    }
   
    $protocol = is_ssl() ? 'https' : 'http';
    $general_font = esc_html( get_option('wp_estate_general_font', '') );
    
    $headings_font_subset   =   esc_html ( get_option('wp_estate_headings_font_subset','') );
    if($headings_font_subset!=''){
        $headings_font_subset='&amp;subset='.$headings_font_subset;
    }
   
    // embed custom fonts from admin
    if($general_font && $general_font!='x'){
        $general_font =  str_replace(' ', '+', $general_font);
        wp_enqueue_style( 'wpestate-custom-font',"https://fonts.googleapis.com/css?family=$general_font:300,400,700,900$headings_font_subset");  
    }else{      
        wp_enqueue_style( 'wpestate-railway', "https://fonts.googleapis.com/css?family=Raleway:500,600,400,700,800&amp;subset=latin,latin-ext" );
        wp_enqueue_style( 'wpestate-opensans', "https://fonts.googleapis.com/css?family=Open+Sans:400,600,300&amp;subset=latin,latin-ext" );
   
        
    }

   
    $headings_font = esc_html( get_option('wp_estate_headings_font', '') );
    if($headings_font && $headings_font!='x'){
       $headings_font =  str_replace(' ', '+', $headings_font);
       wp_enqueue_style( 'wpestate-custom-secondary-font', "https://fonts.googleapis.com/css?family=$headings_font:400,500,300" );  
    }
    
    
    
    wp_enqueue_style( 'wpestate_font-awesome.min',  trailingslashit( get_template_directory_uri() ) . 'css/fontawesome/css/font-awesome.min.css' );  
    wp_enqueue_style( 'font-awesome5.min',  trailingslashit( get_template_directory_uri() ) . '/css/fontawesome/all.css' );  
     
    if(!is_search() && !is_404() && !is_tax() && !is_category() && !is_tag()){
        if( wpestate_check_if_admin_page($post->ID) || is_singular('estate_property') ){

                $wp_estate_book_down=get_option('wp_estate_book_down', '');
                if($wp_estate_book_down==''){
                    $wp_estate_book_down=10;
                }
                $book_down_fixed_fee            =   floatval( get_option('wp_estate_book_down_fixed_fee','') );
                
                $wp_estate_service_fee_fixed_fee            =   floatval( get_option('wp_estate_service_fee_fixed_fee','') );
                $wp_estate_service_fee            =   floatval( get_option('wp_estate_service_fee','') );
             
           
                wp_enqueue_script('wpestate_dashboard-control', trailingslashit( get_template_directory_uri() ).'js/dashboard-control.js',array('jquery'), '1.0', true);   
                wp_localize_script('wpestate_dashboard-control', 'dashboard_vars', 
                    array(  'deleting'                  =>  esc_html__( 'deleting...','wprentals'),
                            'searchtext2'               =>  esc_html__( 'Search here...','wprentals'),
                            'currency_symbol'           =>  wpestate_curency_submission_pick(),
                            'where_currency_symbol'     =>  esc_html( get_option('wp_estate_where_currency_symbol', '') ),
                            'book_down'                 =>  $wp_estate_book_down,
                            'book_down_fixed_fee'       =>  $book_down_fixed_fee,
                            'discount'                  =>  esc_html__( 'Discount','wprentals'),
                            'delete_inv'                =>  esc_html__( 'Delete Invoice','wprentals'),
                            'issue_inv'                 =>  esc_html__( 'Invoice Issued','wprentals'),
                            'confirmed'                 =>  esc_html__( 'Confirmed','wprentals'),
                            'issue_inv1'                =>  esc_html__( 'Issue invoice','wprentals'),
                            'sending'                   =>  esc_html__( 'sending message...','wprentals'),
                            'send_reply'                =>  esc_html__( 'Send Reply','wprentals'),
                            'plsfill'                   =>  esc_html__( 'Please fill in all the fields','wprentals'),
                            'datesb'                    =>  esc_html__( 'Dates are already booked. Please check the calendar for free days!','wprentals'),
                            'datepast'                  =>  esc_html__( 'You cannot select a date in the past! ','wprentals'),
                            'bookingstart'              =>  esc_html__( 'Start date cannot be greater than end date !','wprentals'),
                            'selectprop'                =>  esc_html__( 'Please select a property !','wprentals'),
                            'err_title'                 =>  esc_html__( 'Please submit a title !','wprentals'),
                            'err_category'              =>  esc_html__( 'Please pick a category !','wprentals'),
                            'err_type'                  =>  esc_html__( 'Please pick a typr !','wprentals'),
                            'err_guest'                 =>  esc_html__( 'Please select the guest no !','wprentals'),
                            'err_city'                  =>  esc_html__( 'Please pick a city !','wprentals'),
                            'sending'                   =>  esc_html__( 'sending...','wprentals'),
                            'doublebook'                =>  esc_html__( 'This period is already booked','wprentals'),
                            'deleted_feed'              =>  esc_html__( 'Delete imported dates','wprentals'),
                            'sent'                      =>  esc_html__( 'done','wprentals'),
                            'service_fee_fixed_fee'     =>  $wp_estate_service_fee_fixed_fee,
                            'service_fee'               =>  $wp_estate_service_fee
                          
                    )       
                );

        }
    }   
    if(get_option('wp_estate_use_captcha','')=='yes'){
        wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js?onload=wpestate_onloadCallback&render=explicit&hl=iw" async defer',array('jquery'), '1.0', true);        
    }

}
endif; // end   wpestate_scripts  







///////////////////////////////////////////////////////////////////////////////////////////
/////// Js & Css include on admin site 
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_admin') ):

function wpestate_admin($hook_suffix) {	
    global $post;            
    global $pagenow;
    global $typenow;
    
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_script('my-upload'); 
    wp_enqueue_style('thickbox');
    wp_enqueue_script("jquery-ui-autocomplete");
    wp_enqueue_style('wpestate_adminstyle', trailingslashit( get_template_directory_uri() ) . '/css/admin.css');
    wp_enqueue_script('wpestate_admin-control', trailingslashit( get_template_directory_uri() ).'/js/admin-control.js',array('jquery'), '1.0', true);     
    wp_localize_script('wpestate_admin-control', 'admin_control_vars', 
        array( 'ajaxurl'            => admin_url('admin-ajax.php'))
    );
      
    wp_enqueue_style( 'font-awesome.min',  trailingslashit( get_template_directory_uri() ) . '/css/fontawesome/css/font-awesome.min.css' );
    wp_enqueue_style( 'font-awesome5.min',  trailingslashit( get_template_directory_uri() ) . '/css/fontawesome/all.css' );  
     
    
    if($hook_suffix=='post-new.php' || $hook_suffix=='post.php'){
        wp_enqueue_script("jquery-ui-datepicker");
        wp_enqueue_style( 'font-awesome.min',  trailingslashit( get_template_directory_uri() ) . '/css/fontawesome/css/font-awesome.min.css' );  
        wp_enqueue_style('jquery.ui.theme', trailingslashit( get_template_directory_uri() ). '/css/jquery-ui.min.css');
    }

    if (empty($typenow) && !empty($_GET['post'])) {
        $allowed_html   =   array();
        $post = get_post(wp_kses($_GET['post'],$allowed_html));
        $typenow = $post->post_type;
    }

    
    if (is_admin() &&  ( $pagenow=='post-new.php' || $pagenow=='post.php') && $typenow=='estate_property') {
        if ( is_ssl() ) {
            wp_enqueue_script('wpestate_googlemap',      'https://maps-api-ssl.google.com/maps/api/js?v=3&key='.esc_html(get_option('wp_estate_api_key', '') ).'&amp;sensor=true',array('jquery'), '1.0', false);
        }else{
            wp_enqueue_script('wpestate_googlemap',      'http://maps.googleapis.com/maps/api/js?v=3&key='.esc_html(get_option('wp_estate_api_key', '') ).'&amp;sensor=true',array('jquery'), '1.0', false);
        }  
        wp_enqueue_script('wpestate_admin_google',   trailingslashit( get_template_directory_uri() ).'js/google_js/admin_google.js',array('jquery'), '1.0', true); 
           
                     
        $wp_estate_general_latitude  = floatval(get_post_meta($post->ID, 'property_latitude', true));
        $wp_estate_general_longitude = floatval(get_post_meta($post->ID, 'property_longitude', true));

        if ($wp_estate_general_latitude=='' || $wp_estate_general_longitude=='' ){
            $wp_estate_general_latitude    = esc_html( get_option('wp_estate_general_latitude','') ) ;
            $wp_estate_general_longitude   = esc_html( get_option('wp_estate_general_longitude','') );

            if($wp_estate_general_latitude==''){
               $wp_estate_general_latitude ='40.781711';
            }

            if($wp_estate_general_longitude==''){ 
               $wp_estate_general_longitude='-73.955927';  
            }
        }
        
        wp_localize_script('wpestate_admin_google', 'admin_google_vars', 
        array(  'general_latitude'  =>  $wp_estate_general_latitude,
                'general_longitude' =>  $wp_estate_general_longitude,
                'postId'=>$post->ID,
                'geo_fails'        =>  esc_html__( 'Geolocation was not successful for the following reason:','wprentals') 
              )
        );
     }

    wp_enqueue_script('wpestate_admin', trailingslashit( get_template_directory_uri() ).'/js/admin.js',array('jquery'), '1.0', true); 
    wp_localize_script('wpestate_admin', 'admin_vars', 
        array( 'ajaxurl'            => admin_url('admin-ajax.php'))
    );
    
    wp_enqueue_style ('wpestate_colorpicker_css', trailingslashit( get_template_directory_uri() ).'/css/colorpicker.css', false, '1.0', 'all');
    wp_enqueue_script('wpestate_admin_colorpicker', trailingslashit( get_template_directory_uri() ).'/js/admin_colorpicker.js',array('jquery'), '1.0', true);
       
     
    $admin_pages = array('appearance_page_libs/theme-admin','appearance_page_libs/theme-import','toplevel_page_libs/theme-admin');
   
    if(in_array($hook_suffix, $admin_pages)) {
         wp_enqueue_script('wpestate_config-property', trailingslashit( get_template_directory_uri() ).'/js/config-property.js',array('jquery'), '1.0', true);          
    }
    
    $plup_url = add_query_arg( array(
        'action' => 'me_upload_demo',
        'nonce' => wp_create_nonce('aaiu_allow'),
    ), admin_url('admin-ajax.php') );
    $max_file_size  = 100 * 1000 * 1000;

    $upload_dir = wp_upload_dir();
    $destination = $upload_dir['baseurl'];
    $destination_path = $destination . '/estate_templates/';
    
    wp_enqueue_script('ajax_upload_demo', get_template_directory_uri().'/js/ajax-upload-demo.js',array('jquery','plupload-handlers'), '1.0', true);  
    wp_localize_script('ajax_upload_demo', 'ajax_upload_demo_vars', 
            array(  'ajaxurl'           => admin_url('admin-ajax.php'),
                    'importing'         =>  __('Importing... Please wait!','wprentals'),
                    'complete'          =>  __('Import Completed!','wprentals'),
                    'admin_url'         =>  get_admin_url(),
                    'nonce'             => wp_create_nonce('aaiu_upload'),
                    'remove'            => wp_create_nonce('aaiu_remove'),
                    'number'            => 1,
                    'warning'           =>  __('Warning !','wprentals'),
                    'upload_enabled'    => true,
                    'path'              =>  get_template_directory_uri(),
                    'confirmMsg'        => __('Are you sure you want to delete this?','wprentals'),
                    'destination_path'  =>  $destination_path,
                    'plupload'         => array(
                                            'runtimes'          => 'html5,flash,html4',
                                            'browse_button'     => 'aaiu-uploader-demo',
                                            'container'         => 'aaiu-upload-container-demo',
                                            'file_data_name'    => 'aaiu_upload_file',
                                            'max_file_size'     => $max_file_size . 'b',
                                            'url'               => $plup_url,
                                            'flash_swf_url'     => includes_url('js/plupload/plupload.flash.swf'),
                                            'filters'           => array(array('title' => __('Allowed Files','wprentals'), 'extensions' => "zip")),
                                            'multipart'         => true,
                                            'urlstream_upload'  => true,
                                            )
                
            )
    );
   
}

endif; // end   wpestate_admin  
?>