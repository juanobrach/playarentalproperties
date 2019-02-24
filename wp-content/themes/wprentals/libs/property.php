<?php
// register the custom post type
add_action('after_setup_theme', 'wpestate_create_property_type',0);

if( !function_exists('wpestate_create_property_type') ):
function wpestate_create_property_type() {
    register_post_type('estate_property', array(
        'labels' => array(
            'name'                  => esc_html__( 'Listings','wprentals'),
            'singular_name'         => esc_html__( 'Listing','wprentals'),
            'add_new'               => esc_html__( 'Add New Listing','wprentals'),
            'add_new_item'          => esc_html__( 'Add Listing','wprentals'),
            'edit'                  => esc_html__( 'Edit','wprentals'),
            'edit_item'             => esc_html__( 'Edit Listings','wprentals'),
            'new_item'              => esc_html__( 'New Listing','wprentals'),
            'view'                  => esc_html__( 'View','wprentals'),
            'view_item'             => esc_html__( 'View Listings','wprentals'),
            'search_items'          => esc_html__( 'Search Listings','wprentals'),
            'not_found'             => esc_html__( 'No Listings found','wprentals'),
            'not_found_in_trash'    => esc_html__( 'No Listings found in Trash','wprentals'),
            'parent'                => esc_html__( 'Parent Listings','wprentals')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'properties'),
        'supports' => array('title', 'editor', 'thumbnail', 'comments','excerpt'),
        'can_export' => true,
        'register_meta_box_cb' => 'wpestate_add_property_metaboxes',
        'menu_icon'=>get_template_directory_uri().'/img/properties.png'
         )
    );

    
    
////////////////////////////////////////////////////////////////////////////////////////////////
// Add custom taxomies
////////////////////////////////////////////////////////////////////////////////////////////////
    $category_main_label        =   stripslashes( esc_html(get_option('wp_estate_category_main', '')));
    $category_second_label      =   stripslashes( esc_html(get_option('wp_estate_category_second', '')));
        
    $name_label             =   esc_html__( 'Categories','wprentals');
    $add_new_item_label     =   esc_html__( 'Add New Listing Category','wprentals');
    $new_item_name_label    =   esc_html__( 'New Listing Category','wprentals');
        
    if($category_main_label!=''){
        $name_label             =   $category_main_label;
        $add_new_item_label     =   esc_html__( 'Add New','wprentals').' '.$category_main_label;
        $new_item_name_label    =   esc_html__( 'New','wprentals').' '.$category_main_label;
    }
            
    register_taxonomy('property_category', 'estate_property', array(
        'labels' => array(
            'name'              => $name_label,
            'add_new_item'      => $add_new_item_label,
            'new_item_name'     => $new_item_name_label
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => 'listings' )
        )
    );

    $action_name              = esc_html__( 'What do you rent ?','wprentals');
    $action_add_new_item      = esc_html__( 'Add new option for "What do you rent" ','wprentals');
    $action_new_item_name     = esc_html__( 'Add new option for "What do you rent"','wprentals');
    
    if($category_second_label!=''){
        $action_name              =     $category_second_label;
        $action_add_new_item      =     esc_html__( 'Add New','wprentals').' '.$category_second_label;
        $action_new_item_name     =     esc_html__( 'New','wprentals').' '.$category_second_label;
    
    }
    
    
    // add custom taxonomy
    register_taxonomy('property_action_category', 'estate_property', array(
        'labels' => array(
            'name'              =>  $action_name,
            'add_new_item'      =>  $action_add_new_item,
            'new_item_name'     =>  $action_new_item_name
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => 'action' )
       )      
    );



    // add custom taxonomy
    register_taxonomy('property_city', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'City','wprentals'),
            'add_new_item'      => esc_html__( 'Add New City','wprentals'),
            'new_item_name'     => esc_html__( 'New City','wprentals')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => 'city' )
        )
    );




    // add custom taxonomy
    register_taxonomy('property_area', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'Neighborhood / Area','wprentals'),
            'add_new_item'      => esc_html__( 'Add New Neighborhood / Area','wprentals'),
            'new_item_name'     => esc_html__( 'New Neighborhood / Area','wprentals')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => 'area' )

        )
    );
    
wpestate_ping_me();


}// end create property type
endif; // end   wpestate_create_property_type      



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Add metaboxes for Property
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_property_metaboxes') ):
function wpestate_add_property_metaboxes() {
    add_meta_box('estate_property-sectionid',       esc_html__( 'Listing Settings', 'wprentals'),      'estate_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-propdetails',     esc_html__( 'Listing Details', 'wprentals'),       'details_estate_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-custom',          esc_html__( 'Listing Custom', 'wprentals'),        'wpestate_custom_details_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-googlemap',       esc_html__( 'Place It On The Map', 'wprentals'),    'map_estate_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-features',        esc_html__( 'Amenities and Features', 'wprentals'), 'amenities_estate_box', 'estate_property', 'normal', 'default' );
    add_meta_box('estate_property-agent',           esc_html__( 'Owner', 'wprentals'),      'agentestate_box', 'estate_property', 'normal', 'default' );
    add_meta_box('wpestate-paid-submission',        esc_html__( 'Paid Submission',   'wprentals'),      'estate_paid_submission', 'estate_property', 'side', 'high' );  
    //add_meta_box('estate_property-user',            esc_html__( 'Assign property to user', 'wprentals'), 'userestate_box', 'estate_property', 'normal', 'default' );
   
}
endif; // end   wpestate_add_property_metaboxes  





///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property Custom details  function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_custom_details_box') ):
function wpestate_custom_details_box(){
    global $post;
    $i=0;
    $custom_fields = get_option( 'wp_estate_custom_fields', true);    
    if( !empty($custom_fields)){ 
     
        while($i< count($custom_fields) ){     
            $name               =   $custom_fields[$i][0]; 
            $label              =   $custom_fields[$i][1];
            $type               =   $custom_fields[$i][2];
            if(isset( $custom_fields[$i][4])){
                $dropdown_values    =   $custom_fields[$i][4];
            }
            $slug               =   wpestate_limit45(sanitize_title( $name )); 
            $slug               =   sanitize_key($slug); 

            print '<div class="metacustom">';
            if ( $type =='long text' ){
                print '<label for="'.$slug.'">'.stripslashes($label).' (*text) </label>';
                print '<textarea type="text" id="'.$slug.'"  size="0" name="'.$slug.'" rows="3" cols="42">' . esc_html(get_post_meta($post->ID, $slug, true)) . '</textarea>'; 
            }else if( $type =='short text' ){
                print '<label for="'.$slug.'">'.stripslashes($label).' (*text) </label>';
                print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . esc_html(get_post_meta($post->ID,$slug, true)) . '">';
            }else if( $type =='numeric'  ){
                print '<label for="'.$slug.'">'.stripslashes($label).' (*numeric) </label>';
                $numeric_value=get_post_meta($post->ID,$slug, true);
                if($numeric_value!=''){
                    $numeric_value=  floatval($numeric_value);
                }
                print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . $numeric_value . '">';
            }else if( $type =='date' ){
                print '<label for="'.$slug.'">'.stripslashes($label).' (*date) </label>';
                print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . esc_html(get_post_meta($post->ID,$slug, true)) . '">';
                print '<script type="text/javascript">
                      //<![CDATA[
                      jQuery(document).ready(function(){
                           '.wpestate_date_picker_translation($slug).'
                      });
                      //]]>
                      </script>';

            }else if( $type =='dropdown' ){
                $dropdown_values_array=explode(',',$dropdown_values);

                print '<label for="'.$slug.'">'.stripslashes($label).' </label>';
                print '<select id="'.$slug.'"  name="'.$slug.'" >';
                print '<option value="">'.esc_html__('Not Available','wprentals').'</option>';
                $value = esc_html(get_post_meta($post->ID,$slug, true)); 
                foreach($dropdown_values_array as $key=>$value_drop){
                    print '<option value="'.trim($value_drop).'"';
                    if( trim( htmlspecialchars_decode($value) ) === trim( htmlspecialchars_decode ($value_drop) ) ){
                        print ' selected ';
                    }
                    if (function_exists('icl_translate') ){
                        $value_drop = apply_filters('wpml_translate_single_string', $value_drop,'custom field value','custom_field_value'.$value_drop );
                    }

                    print '>'.stripslashes( trim( $value_drop ) ).'</option>';
                }
                print '</select>';
            }
            print '</div>';  
            $i++;        
        }
    }
    

    $details =   get_post_meta($post->ID, 'property_custom_details', true);

    if(is_array($details)){
        print '   <div class="extra_detail_option_wrapper_admin"> <h3>'.esc_html__('Custom Details','wprentals').'</h3>';
        foreach ($details as $label=>$value){
            print ' 
         
                <div class="extra_detail_option ">
                    <label class="extra_detail_option_label">'.esc_html__('Label','wprentals').'</label>
                    <input type="text" name="property_custom_details_admin_label[]" class=" extra_option_name form-control" value="'.$label.'">
                </div>
        
                <div class="extra_detail_option ">
                    <label class="extra_detail_option_label">'.esc_html__('Value','wprentals').'</label>
                    <input type="text" name="property_custom_details_admin_value[]" class=" extra_option_value form-control" value="'.$value.'">
                </div>';
         
        }  
        print' </div>';

    }

   
    print '<div style="clear:both"></div>';
     
}
endif; // end     



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Agent box function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('userestate_box') ):
function userestate_box($post) {
    global  $post;
    $mypost         =   $post->ID;
    $originalpost   =   $post;
    $blog_list      =   '';
    $original_user  =   wpsestate_get_author();


    
    $blogusers = get_users( 'blog_id=1&orderby=nicename&role=subscriber' );

    foreach ( $blogusers as $user ) {
 
        $the_id=$user->ID;
        $blog_list  .=  '<option value="' . $the_id . '"  ';
            if ($the_id == $original_user) {
                $blog_list.=' selected="selected" ';
            }
        $blog_list.= '>' .$user->user_login . '</option>';
    }


    

    print '
    <label for="property_user">'.esc_html__( 'Users: ','wprentals').'</label><br />
    <select id="property_user" style="width: 237px;" name="property_user">
          <option value="1">admin</option>
          <option value=""></option>
          '. $blog_list .'
    </select>';  

}
endif;


///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property Pay Submission  function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_paid_submission') ):

function estate_paid_submission($post){
  global $post;
  $paid_submission_status= esc_html ( get_option('wp_estate_paid_submission','') );
  if($paid_submission_status=='no'){
     esc_html_e('Paid Submission is disabled','wprentals');  
  }
  
  if($paid_submission_status=='per listing'){
     esc_html_e('Pay Status: ','wprentals');
     $pay_status           = get_post_meta($post->ID, 'pay_status', true);
     if($pay_status=='paid'){
        esc_html_e('PAID','wprentals');
     }
     else{
        esc_html_e('Not Paid','wprentals');
     }
  }
    
}
endif; // end   estate_paid_submission  




///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property details  function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('details_estate_box') ):

function details_estate_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    $week_days=array(
    '0'=>esc_html__('All','wprentals'),
    '1'=>esc_html__('Monday','wprentals'), 
    '2'=>esc_html__('Tuesday','wprentals'),
    '3'=>esc_html__('Wednesday','wprentals'),
    '4'=>esc_html__('Thursday','wprentals'),
    '5'=>esc_html__('Friday','wprentals'),
    '6'=>esc_html__('Saturday','wprentals'),
    '7'=>esc_html__('Sunday','wprentals')
 
    );
    
   
    $options_array=array(
            0   =>  esc_html__('Single Fee','wprentals'),
            1   =>  esc_html__('Per Night','wprentals'),
            2   =>  esc_html__('Per Guest','wprentals'),
            3   =>  esc_html__('Per Night per Guest','wprentals')
        );
    
    $mypost             =   $post->ID;
    
    $checkin_change_over            =   floatval   ( get_post_meta($mypost, 'checkin_change_over', true) );  
    $checkin_checkout_change_over   =   floatval   ( get_post_meta($mypost, 'checkin_checkout_change_over', true) );  
    $city_fee_per_day               =   floatval   ( get_post_meta($mypost, 'city_fee_per_day', true) );  
    $cleaning_fee_per_day           =   floatval   ( get_post_meta($mypost, 'cleaning_fee_per_day', true) );  
    $city_fee_percent               =   floatval   ( get_post_meta($mypost, 'city_fee_percent', true) ); 
    
    print'            
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price">'.esc_html__( 'Price: ','wprentals').'</label><br />
            <input type="text" id="property_price" size="40" name="property_price" value="' . intval(get_post_meta($mypost, 'property_price', true)) . '">
            </p>
        </td>';

        print'
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price_before_label">'.esc_html__( 'Before Price Label: ','wprentals').'</label><br />
            <input type="text" id="property_price_before_label" size="40" name="property_price_before_label" value="' . esc_html(get_post_meta($mypost, 'property_price_before_label', true)) . '">
            </p>
        </td>';
   
        print'
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price_after_label">'.esc_html__( 'After Price Label: ','wprentals').'</label><br />
            <input type="text" id="property_price_after_label" size="40" name="property_price_after_label" value="' . esc_html(get_post_meta($mypost, 'property_price_after_label', true)) . '">
            </p>
        </td>';
    
    print'
    </tr>  
    
    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_taxes">'.esc_html__( 'Taxes in % (taxes are considered included in the daily price): ','wprentals').'</label><br />
            <input type="text" id="property_taxes" size="40" name="property_taxes" value="' . esc_html(get_post_meta($mypost, 'property_taxes', true)) . '">
            </p>
        </td>';

        print'
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="security_deposit">'.esc_html__( 'Security Deposit ','wprentals').'</label><br />
            <input type="text" id="security_deposit" size="40" name="security_deposit" value="' . esc_html(get_post_meta($mypost, 'security_deposit', true)) . '">
            </p>
        </td>';
    
    print'
    </tr>  
   
    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="early_bird_percent">'.esc_html__( 'Early Bird Discount value- in % from the price per night','wprentals').'</label><br />
            <input type="text" id="early_bird_percent" size="40" name="early_bird_percent" value="' . esc_html(get_post_meta($mypost, 'early_bird_percent', true)) . '">
            </p>
        </td>';
        
        print'
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="early_bird_days">'.esc_html__( 'Early Bird Discount no of days ','wprentals').'</label><br />
            <input type="text" id="early_bird_days" size="40" name="early_bird_days" value="' . esc_html(get_post_meta($mypost, 'early_bird_days', true)) . '">
            </p>
        </td>';

   
    print'
    </tr>  


    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="cleaning_fee">'.esc_html__( 'Cleaning Fee:','wprentals').'</label><br />
                <input type="text" id="cleaning_fee" size="40" name="cleaning_fee" value="' . floatval(get_post_meta($mypost, 'cleaning_fee', true)) . '">
            </p>
        </td>

        <td width="33%" valign="top" align="left">
                <p class="meta-options">
                    <label for="cleaning_fee_per_day">'.esc_html__( 'Cleaning Fee for:','wprentals').'</label><br />
                        <select id="cleaning_fee_per_day" name="cleaning_fee_per_day" class="select-select_submit_price">';
                            foreach($options_array as $key=>$option){
                                print '   <option value="'.$key.'"';
                                if( $key==$cleaning_fee_per_day){
                                    print ' selected="selected" ';
                                }
                                print '>'.$option.'</option>';
                            }
                        print'    
                        </select>
                </p>
         </td>   
    </tr>
    
    <tr>
        
    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="city_fee">'.esc_html__( 'City Fee:','wprentals').'</label><br />
                <input type="text" id="city_fee" size="40" name="city_fee" value="' . floatval(get_post_meta($mypost, 'city_fee', true)) . '">
            </p>
        </td>
 
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="city_fee_per_day">'.esc_html__( 'City Fee for:','wprentals').'</label><br />
                    <select id="city_fee_per_day" name="city_fee_per_day" class="select_submit_price">';
                        foreach($options_array as $key=>$option){
                            print '   <option value="'.$key.'"';
                            if( $key==$city_fee_per_day){
                                print ' selected="selected" ';
                            }
                            print '>'.$option.'</option>';
                        }
                    print'    
                    </select>
            </p>
        </td>

        <td width="33%" valign="top" align="left">
            <p class="meta-options"> 
                <input type="hidden" name="city_fee_percent" value="0">
                <input type="checkbox"  id="city_fee_percent" name="city_fee_percent" value="1" ';
                if (intval(get_post_meta($mypost, 'city_fee_percent', true)) == 1) {
                    print'checked="checked"';
                }
                print' />
                <label for="city_fee_percent">'.esc_html__( 'City Fee is a % of the daily fee','wprentals').'</label>
            </p>
        </td>
    </tr>


    <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="price_per_weekeend">'. esc_html__('Price per weekend (Saturday and Sundays)','wprentals').'</label><br />
                <input type="text" id="price_per_weekeend" size="40" name="price_per_weekeend" value="' . floatval(get_post_meta($mypost, 'price_per_weekeend', true)) . '">
            </p>
        </td>

        <td>
            <p class="meta-options">
            <label for="min_days_booking">'.esc_html__('Minimum days of booking (only numbers) ','wprentals').'</label></br>
            <input type="text" id="min_days_booking" class="form-control" size="40" name="min_days_booking" value="' . floatval(get_post_meta($mypost, 'min_days_booking', true)) . '">
            </p>
        </td>

    </tr>

    
    <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price">'.esc_html__( 'Price per night (7d+): ','wprentals').'</label><br />
            <input type="text" id="property_price_per_week" size="40" name="property_price_per_week" value="' . esc_html(get_post_meta($mypost, 'property_price_per_week', true)) . '">
            </p>
        </td>

        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price">'.esc_html__( 'Price per night (30d+): ','wprentals').'</label><br />
            <input type="text" id="property_price_per_month" size="40" name="property_price_per_month" value="' . esc_html(get_post_meta($mypost, 'property_price_per_month', true)) . '">
            </p>
        </td>
    </tr>
    
    <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="extra_price_per_guest">'.esc_html__( 'Extra Price per guest per night','wprentals').'</label><br />
            <input type="text" id="extra_price_per_guest" size="40" name="extra_price_per_guest" value="' . esc_html(get_post_meta($mypost, 'extra_price_per_guest', true)) . '">
            </p>
        </td>

        <td width="33%" valign="top" align="left">
           <p class="meta-options"> 
                <input type="hidden" name="overload_guest" value="0">
                <input type="checkbox"  id="overload_guest" name="overload_guest" value="1" ';
                if (intval(get_post_meta($mypost, 'overload_guest', true)) == 1) {
                    print'checked="checked"';
                }
                print' />
                <label for="overload_guest">'.esc_html__( 'Allow guests above capacity?','wprentals').'</label>
            </p>
        </td>
    </tr>
 
    <tr>
        <td valign="top" align="left">
        '.esc_html__('These options do not work together - choose only one and leave the other one on "All"','wprentals').'
        </td>
    </tr>
      

      <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="checkin_change_over">'. esc_html__('Allow only bookings starting with the check in on:','wprentals').'</label></br>
            <select id="checkin_change_over" name="checkin_change_over" class="select-submit2">';
              
                foreach($week_days as $key=>$value){
                    print '   <option value="'.$key.'"';
                    if( $key==$checkin_change_over){
                        print ' selected="selected" ';
                    }
                    print '>'.$value.'</option>';
                }
            print'    
            </select>
            </p>
        </td>

        <td width="33%" valign="top" align="left">
            <p class="meta-options"> 
            <label for="checkin_checkout_change_over">'. esc_html__('Allow only bookings with the check in/check out on: ','wprentals').'</label></br>
            <select id="checkin_checkout_change_over" name="checkin_checkout_change_over" class="select-submit2">';
               
                foreach($week_days as $key=>$value){
                   print '   <option value="'.$key.'"';
                    if( $key==$checkin_checkout_change_over){
                        print ' selected="selected" ';
                    }
                    print '>'.$value.'</option>';
                }
              print'
            </p>
        </td>
    </tr>
    



    <tr>  
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_size">'.esc_html__( 'Size: ','wprentals').'</label><br />
            <input type="text" id="property_size" size="40" name="property_size" value="' . esc_html(get_post_meta($mypost, 'property_size', true)) . '">
            </p>
        </td>



        <td valign="top" align="left">
            <p class="meta-options">
            <label for="property_rooms">'.esc_html__( 'Rooms: ','wprentals').'</label><br />
            <input type="text" id="property_rooms" size="40" name="property_rooms" value="' . esc_html(get_post_meta($mypost, 'property_rooms', true)) . '">
            </p>
        </td>
    </tr>

    <tr>
        <td valign="top" align="left">
            <p class="meta-options">
            <label for="property_bedrooms">'.esc_html__( 'Bedrooms: ','wprentals').'</label><br />
            <input type="text" id="property_bedrooms" size="40" name="property_bedrooms" value="' . esc_html(get_post_meta($mypost, 'property_bedrooms', true)) . '">
            </p>
        </td>

        <td valign="top" align="left">  
            <p class="meta-options">
            <label for="property_bedrooms">'.esc_html__( 'Bathrooms: ','wprentals').'</label><br />
            <input type="text" id="property_bathrooms" size="40" name="property_bathrooms" value="' . esc_html(get_post_meta($mypost, 'property_bathrooms', true)) . '">
            </p>
        </td>
    </tr>
    
    <tr>
    <td valign="top" align="left">  
        <p class="meta-options">
        <label for="guest_no">'.esc_html__( 'Guests: ','wprentals').'</label><br />
        <input type="text" id="guest_no" size="40" name="guest_no" value="' . esc_html(get_post_meta($mypost, 'guest_no', true)) . '">
        </p>
    </td>
    
    </tr>
    <tr>';
     
     $option_video='';
     $video_values = array('vimeo', 'youtube');
     $video_type = get_post_meta($mypost, 'embed_video_type', true);

     foreach ($video_values as $value) {
         $option_video.='<option value="' . $value . '"';
         if ($value == $video_type) {
             $option_video.='selected="selected"';
         }
         $option_video.='>' . $value . '</option>';
     }
     
     
    print'
    <td valign="top" align="left">
        <p class="meta-options">
        <label for="embed_video_type">'.esc_html__( 'Video from ','wprentals').'</label><br />
        <select id="embed_video_type" name="embed_video_type" style="width: 237px;">
                ' . $option_video . '
        </select>       
        </p>
    </td>';

  
    print'
    <td valign="top" align="left">
      <p class="meta-options">     
      <label for="embed_video_id">'.esc_html__( 'Video id: ','wprentals').'</label> <br />
        <input type="text" id="embed_video_id" name="embed_video_id" size="40" value="'.esc_html( get_post_meta($mypost, 'embed_video_id', true) ).'">
      </p>
    </td>
    </tr>
    </table>';
}
endif; // end   details_estate_box  



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Google map function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('map_estate_box') ):
 
function map_estate_box($post) {
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    global $post;
    
    $mypost                 =   $post->ID;
    $gmap_lat               =   floatval(get_post_meta($mypost, 'property_latitude', true));
    $gmap_long              =   floatval(get_post_meta($mypost, 'property_longitude', true));
    $google_camera_angle    =   intval( esc_html(get_post_meta($mypost, 'google_camera_angle', true)) );
    $cache_array            =   array('yes','no');
    $keep_min_symbol        =   '';
    $keep_min_status        =   esc_html ( get_post_meta($post->ID, 'keep_min', true) );

    foreach($cache_array as $value){
            $keep_min_symbol.='<option value="'.$value.'"';
            if ($keep_min_status==$value){
                    $keep_min_symbol.=' selected="selected" ';
            }
            $keep_min_symbol.='>'.$value.'</option>';
    }
    
    print '<script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready(function(){
        '.wpestate_date_picker_translation("property_date").'
    });
    //]]>
    </script>
    <p class="meta-options"> 
    <div id="googleMap" style="width:100%;height:380px;margin-bottom:30px;"></div>    
    <p class="meta-options"> 
        <a class="button" href="#" id="admin_place_pin">'.esc_html__( 'Place Pin with Listing Address','wprentals').'</a>
    </p>
    '.esc_html__( 'Latitude:','wprentals').'  <input type="text" id="property_latitude" style="margin-right:20px;" size="40" name="property_latitude" value="' . $gmap_lat . '">
    '.esc_html__( 'Longitude:','wprentals').' <input type="text" id="property_longitude" style="margin-right:20px;" size="40" name="property_longitude" value="' . $gmap_long . '">
    <p>
    <p class="meta-options"> 
    <label for="google_camera_angle" >'.esc_html__( 'Google View Camera Angle','wprentals').'</label>
    <input type="text" id="google_camera_angle" style="margin-right:0px;" size="5" name="google_camera_angle" value="'.$google_camera_angle.'">
    
    </p>';
        
    $page_custom_zoom  = get_post_meta($mypost, 'page_custom_zoom', true);
    if ($page_custom_zoom==''){
        $page_custom_zoom=16;
    }
    
    print '
     <p class="meta-options">
       <label for="page_custom_zoom">'.esc_html__( 'Zoom Level for map (1-20)','wprentals').'</label><br />
       <select name="page_custom_zoom" id="page_custom_zoom">';
      
      for ($i=1;$i<21;$i++){
           print '<option value="'.$i.'"';
           if($page_custom_zoom==$i){
               print ' selected="selected" ';
           }
           print '>'.$i.'</option>';
       }
        
     print'
       </select>
    ';   
    
    
     
}
endif; // end   map_estate_box 






///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Agent box function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('agentestate_box') ):
function agentestate_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
   
    $mypost         =   $post->ID;
    $originalpost   =   $post;
    $agent_list     =   '';
    $picked_agent   =   wpsestate_get_author($mypost);
    $blogusers = get_users( 'blog_id=1&orderby=nicename' );
  
    foreach ( $blogusers as $user ) {     
        $the_id       =  $user->ID;
        $agent_list  .=  '<option value="' . $the_id . '"  ';
        if ($the_id == $picked_agent) {
           $agent_list.=' selected="selected" ';
        }
        $user_info = get_userdata($the_id);
        $username = $user_info->user_login;
        $first_name = $user_info->first_name;
        $last_name = $user_info->last_name;
        $agent_list.= '>' .  $user->user_login .' - '.$first_name.' '.$last_name.'</option>';
    }

  
    
    wp_reset_postdata();
    $post = $originalpost;
    $originalAuthor = get_post_meta($mypost, 'original_author',true );
    //print ($originalAuthor);
    print '
    <label for="property_zip">'.esc_html__( 'Listing Owner: ','wprentals').'</label><br />
    <select id="property_agent" style="width: 237px;" name="property_agent">
        <option value="">none</option>
        <option value=""></option>
        '. $agent_list .'
    </select>';  
}
endif; // end   agentestate_box  





///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Features And Amenties function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('amenities_estate_box') ):
function amenities_estate_box($post) {
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    global $post;
    $mypost             =   $post->ID;
    $feature_list_array =   array();
    $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
    $feature_list_array =   explode( ',',$feature_list);
    $counter            =   0;
    
    print ' <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
    foreach($feature_list_array as $key => $value){
        $counter++;
        $post_var_name=  str_replace(' ','_', trim($value) );
      
        if( ($counter-1) % 3 == 0){
            print'<tr>';
        }
        $input_name =   wpestate_limit45(sanitize_title( $post_var_name ));
        $input_name =   sanitize_key($input_name);
      
        if (function_exists('icl_translate') ){
            $value     =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
        }
        print '     
        <td width="33%" valign="top" align="left">
            <p class="meta-options"> 
            <input type="hidden"    name="'.$input_name.'" value="">
            <input type="checkbox"  name="'.$input_name.'" value="1" ';
        
        if (esc_html(get_post_meta($mypost, $input_name, true)) == 1) {
            print' checked="checked" ';
        }
        print' />
            <label for="'.$input_name.'">'.stripslashes($value).'</label>
            </p>
        </td>';
        if($counter % 3 == 0){
            print'</tr>';
        }
    }
    
    print '</table>';
}
endif; // end   amenities_estate_box  





///////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Property custom fields
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_box') ): 
function estate_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    $mypost = $post->ID;
    
    print' 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="33%" align="left" valign="top">
          <p class="meta-options">
          <label for="property_address">'.esc_html__( 'Address: ','wprentals').'</label><br />
          <textarea type="text" id="property_address"  size="40" name="property_address" rows="3" cols="42">' . esc_html(get_post_meta($mypost, 'property_address', true)) . '</textarea>
          </p>
      </td>
      
      <td width="33%" align="left" valign="top">
          <p class="meta-options">
          <label for="property_county">'.esc_html__( 'County: ','wprentals').'</label><br />
          <input type="text" id="property_county"  size="40" name="property_county" value="' . esc_html(get_post_meta($mypost, 'property_county', true)) . '">
          </p>
      </td>
      
      <td width="33%" align="left" valign="top">
           <p class="meta-options">
          <label for="property_state">'.esc_html__( 'State: ','wprentals').'</label><br />
          <input type="text" id="property_state" size="40" name="property_state" value="' . esc_html(get_post_meta($mypost, 'property_state', true)) . '">
          </p>
      </td>
    </tr>

    <tr>
      <td align="left" valign="top">   
          <p class="meta-options">
          <label for="property_zip">'.esc_html__( 'Zip: ','wprentals').'</label><br />
          <input type="text" id="property_zip" size="40" name="property_zip" value="' . esc_html(get_post_meta($mypost, 'property_zip', true)) . '">
          </p>
      </td>

      <td align="left" valign="top">
          <p class="meta-options">
          <label for="property_country">'.esc_html__( 'Country: ','wprentals').'</label><br />

          ';
      print wpestate_country_list(esc_html(get_post_meta($mypost, 'property_country', true)));
      print '     
          </p>
      </td>

    
    </tr>

    <tr>';
     
    $status_values          =   esc_html( get_option('wp_estate_status_list') );
    $status_values_array    =   explode(",",$status_values);
    $prop_stat              =   stripslashes( get_post_meta($mypost, 'property_status', true) );
    $property_status        =   '';
    foreach ($status_values_array as $key=>$value) {
        if (function_exists('icl_translate') ){
            //do_action( 'wpml_register_single_string', 'wpestate','property_status', $value );
            //$value     =   icl_translate('wpestate','wp_estate_property_status_'.$value, stripslashes($value) ) ;             
             do_action( 'wpml_register_single_string', 'wpestate','property_status_'.$value, $value );
        }

        $value = stripslashes(trim($value));
        $property_status.='<option value="' . $value . '"';
        if ($value == $prop_stat) {
            $property_status.='selected="selected"';
        }
        $property_status.='>' . $value . '</option>';
    }


    print'
    <td align="left" valign="top">
        <p class="meta-options">
           <label for="property_status">'.esc_html__( 'Listing Status:','wprentals').'</label><br />
           <select id="property_status" style="width: 237px;" name="property_status">
           <option value="normal">normal</option>
           ' . $property_status . '
           </select>
       </p>
    </td>';
 
      print '
      <td align="left" valign="top">  
           <p class="meta-options"> 
              <input type="hidden" name="prop_featured" value="0">
              <input type="checkbox"  id="prop_featured" name="prop_featured" value="1" ';
              if (intval(get_post_meta($mypost, 'prop_featured', true)) == 1) {
                  print'checked="checked"';
              }
              print' />
              <label for="prop_featured">'.esc_html__( 'Make it Featured','wprentals').'</label>
          </p>
     </td>

      <td align="left" valign="top">          
      </td>
    </tr>
    </table> 

    ';
}
endif; // end   estate_box 








///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Country list function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_country_list') ): 
function wpestate_country_list($selected,$class='') {
    //$countries = array(esc_html__('Afghanistan','wprentals'),esc_html__('Albania','wprentals'),esc_html__('Algeria','wprentals'),esc_html__('American Samoa','wprentals'),esc_html__('Andorra','wprentals'),esc_html__('Angola','wprentals'),esc_html__('Anguilla','wprentals'),esc_html__('Antarctica','wprentals'),esc_html__('Antigua and Barbuda','wprentals'),esc_html__('Argentina','wprentals'),esc_html__('Armenia','wprentals'),esc_html__('Aruba','wprentals'),esc_html__('Australia','wprentals'),esc_html__('Austria','wprentals'),esc_html__('Azerbaijan','wprentals'),esc_html__('Bahamas','wprentals'),esc_html__('Bahrain','wprentals'),esc_html__('Bangladesh','wprentals'),esc_html__('Barbados','wprentals'),esc_html__('Belarus','wprentals'),esc_html__('Belgium','wprentals'),esc_html__('Belize','wprentals'),esc_html__('Benin','wprentals'),esc_html__('Bermuda','wprentals'),esc_html__('Bhutan','wprentals'),esc_html__('Bolivia','wprentals'),esc_html__('Bosnia and Herzegowina','wprentals'),esc_html__('Botswana','wprentals'),esc_html__('Bouvet Island','wprentals'),esc_html__('Brazil','wprentals'),esc_html__('British Indian Ocean Territory','wprentals'),esc_html__('Brunei Darussalam','wprentals'),esc_html__('Bulgaria','wprentals'),esc_html__('Burkina Faso','wprentals'),esc_html__('Burundi','wprentals'),esc_html__('Cambodia','wprentals'),esc_html__('Cameroon','wprentals'),esc_html__('Canada','wprentals'),esc_html__('Cape Verde','wprentals'),esc_html__('Cayman Islands','wprentals'),esc_html__('Central African Republic','wprentals'),esc_html__('Chad','wprentals'),esc_html__('Chile','wprentals'),esc_html__('China','wprentals'),esc_html__('Christmas Island','wprentals'),esc_html__('Cocos (Keeling) Islands','wprentals'),esc_html__('Colombia','wprentals'),esc_html__('Comoros','wprentals'),esc_html__('Congo','wprentals'),esc_html__('Congo, the Democratic Republic of the','wprentals'),esc_html__('Cook Islands','wprentals'),esc_html__('Costa Rica','wprentals'),esc_html__('Cote dIvoire','wprentals'),esc_html__('Croatia (Hrvatska)','wprentals'),esc_html__('Cuba','wprentals'),esc_html__('Curacao','wprentals'),esc_html__('Cyprus','wprentals'),esc_html__('Czech Republic','wprentals'),esc_html__('Denmark','wprentals'),esc_html__('Djibouti','wprentals'),esc_html__('Dominica','wprentals'),esc_html__('Dominican Republic','wprentals'),esc_html__('East Timor','wprentals'),esc_html__('Ecuador','wprentals'),esc_html__('Egypt','wprentals'),esc_html__('El Salvador','wprentals'),esc_html__('Equatorial Guinea','wprentals'),esc_html__('Eritrea','wprentals'),esc_html__('Estonia','wprentals'),esc_html__('Ethiopia','wprentals'),esc_html__('Falkland Islands (Malvinas)','wprentals'),esc_html__('Faroe Islands','wprentals'),esc_html__('Fiji','wprentals'),esc_html__('Finland','wprentals'),esc_html__('France','wprentals'),esc_html__('France Metropolitan','wprentals'),esc_html__('French Guiana','wprentals'),esc_html__('French Polynesia','wprentals'),esc_html__('French Southern Territories','wprentals'),esc_html__('Gabon','wprentals'),esc_html__('Gambia','wprentals'),esc_html__('Georgia','wprentals'),esc_html__('Germany','wprentals'),esc_html__('Ghana','wprentals'),esc_html__('Gibraltar','wprentals'),esc_html__('Greece','wprentals'),esc_html__('Greenland','wprentals'),esc_html__('Grenada','wprentals'),esc_html__('Guadeloupe','wprentals'),esc_html__('Guam','wprentals'),esc_html__('Guatemala','wprentals'),esc_html__('Guinea','wprentals'),esc_html__('Guinea-Bissau','wprentals'),esc_html__('Guyana','wprentals'),esc_html__('Haiti','wprentals'),esc_html__('Heard and Mc Donald Islands','wprentals'),esc_html__('Holy See (Vatican City State)','wprentals'),esc_html__('Honduras','wprentals'),esc_html__('Hong Kong','wprentals'),esc_html__('Hungary','wprentals'),esc_html__('Iceland','wprentals'),esc_html__('India','wprentals'),esc_html__('Indonesia','wprentals'),esc_html__('Iran (Islamic Republic of)','wprentals'),esc_html__('Iraq','wprentals'),esc_html__('Ireland','wprentals'),esc_html__('Israel','wprentals'),esc_html__('Italy','wprentals'),esc_html__('Jamaica','wprentals'),esc_html__('Japan','wprentals'),esc_html__('Jordan','wprentals'),esc_html__('Kazakhstan','wprentals'),esc_html__('Kenya','wprentals'),esc_html__('Kiribati','wprentals'),esc_html__('Korea, Democratic People Republic of','wprentals'),esc_html__('Korea, Republic of','wprentals'),esc_html__('Kuwait','wprentals'),esc_html__('Kyrgyzstan','wprentals'),esc_html__('Lao, People Democratic Republic','wprentals'),esc_html__('Latvia','wprentals'),esc_html__('Lebanon','wprentals'),esc_html__('Lesotho','wprentals'),esc_html__('Liberia','wprentals'),esc_html__('Libyan Arab Jamahiriya','wprentals'),esc_html__('Liechtenstein','wprentals'),esc_html__('Lithuania','wprentals'),esc_html__('Luxembourg','wprentals'),esc_html__('Macau','wprentals'),esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals'),esc_html__('Madagascar','wprentals'),esc_html__('Malawi','wprentals'),esc_html__('Malaysia','wprentals'),esc_html__('Maldives','wprentals'),esc_html__('Mali','wprentals'),esc_html__('Malta','wprentals'),esc_html__('Marshall Islands','wprentals'),esc_html__('Martinique','wprentals'),esc_html__('Mauritania','wprentals'),esc_html__('Mauritius','wprentals'),esc_html__('Mayotte','wprentals'),esc_html__('Mexico','wprentals'),esc_html__('Micronesia, Federated States of','wprentals'),esc_html__('Moldova, Republic of','wprentals'),esc_html__('Monaco','wprentals'),esc_html__('Mongolia','wprentals'),esc_html__('Montserrat','wprentals'),esc_html__('Morocco','wprentals'),esc_html__('Mozambique','wprentals'),esc_html__('Montenegro','wprentals'),esc_html__('Myanmar','wprentals'),esc_html__('Namibia','wprentals'),esc_html__('Nauru','wprentals'),esc_html__('Nepal','wprentals'),esc_html__('Netherlands','wprentals'),esc_html__('Netherlands Antilles','wprentals'),esc_html__('New Caledonia','wprentals'),esc_html__('New Zealand','wprentals'),esc_html__('Nicaragua','wprentals'),esc_html__('Niger','wprentals'),esc_html__('Nigeria','wprentals'),esc_html__('Niue','wprentals'),esc_html__('Norfolk Island','wprentals'),esc_html__('Northern Mariana Islands','wprentals'),esc_html__('Norway','wprentals'),esc_html__('Oman','wprentals'),esc_html__('Pakistan','wprentals'),esc_html__('Palau','wprentals'),esc_html__('Panama','wprentals'),esc_html__('Papua New Guinea','wprentals'),esc_html__('Paraguay','wprentals'),esc_html__('Peru','wprentals'),esc_html__('Philippines','wprentals'),esc_html__('Pitcairn','wprentals'),esc_html__('Poland','wprentals'),esc_html__('Portugal','wprentals'),esc_html__('Puerto Rico','wprentals'),esc_html__('Qatar','wprentals'),esc_html__('Reunion','wprentals'),esc_html__('Romania','wprentals'),esc_html__('Russian Federation','wprentals'),esc_html__('Rwanda','wprentals'),esc_html__('Saint Kitts and Nevis','wprentals'),esc_html__('Saint Lucia','wprentals'),esc_html__('Saint Vincent and the Grenadines','wprentals'),esc_html__('Samoa','wprentals'),esc_html__('San Marino','wprentals'),esc_html__('Sao Tome and Principe','wprentals'),esc_html__('Saudi Arabia','wprentals'),esc_html__('Serbia','wprentals'),esc_html__('Senegal','wprentals'),esc_html__('Seychelles','wprentals'),esc_html__('Sierra Leone','wprentals'),esc_html__('Singapore','wprentals'),esc_html__('Slovakia (Slovak Republic)','wprentals'),esc_html__('Slovenia','wprentals'),esc_html__('Solomon Islands','wprentals'),esc_html__('Somalia','wprentals'),esc_html__('South Africa','wprentals'),esc_html__('South Georgia and the South Sandwich Islands','wprentals'),esc_html__('Spain','wprentals'),esc_html__('Sri Lanka','wprentals'),esc_html__('St. Helena','wprentals'),esc_html__('St. Pierre and Miquelon','wprentals'),esc_html__('Sudan','wprentals'),esc_html__('Suriname','wprentals'),esc_html__('Svalbard and Jan Mayen Islands','wprentals'),esc_html__('Swaziland','wprentals'),esc_html__('Sweden','wprentals'),esc_html__('Switzerland','wprentals'),esc_html__('Syrian Arab Republic','wprentals'),esc_html__('Taiwan, Province of China','wprentals'),esc_html__('Tajikistan','wprentals'),esc_html__('Tanzania, United Republic of','wprentals'),esc_html__('Thailand','wprentals'),esc_html__('Togo','wprentals'),esc_html__('Tokelau','wprentals'),esc_html__('Tonga','wprentals'),esc_html__('Trinidad and Tobago','wprentals'),esc_html__('Tunisia','wprentals'),esc_html__('Turkey','wprentals'),esc_html__('Turkmenistan','wprentals'),esc_html__('Turks and Caicos Islands','wprentals'),esc_html__('Tuvalu','wprentals'),esc_html__('Uganda','wprentals'),esc_html__('Ukraine','wprentals'),esc_html__('United Arab Emirates','wprentals'),esc_html__('United Kingdom','wprentals'),esc_html__('United States','wprentals'),esc_html__('United States Minor Outlying Islands','wprentals'),esc_html__('Uruguay','wprentals'),esc_html__('Uzbekistan','wprentals'),esc_html__('Vanuatu','wprentals'),esc_html__('Venezuela','wprentals'),esc_html__('Vietnam','wprentals'),esc_html__('Virgin Islands (British)','wprentals'),esc_html__('Virgin Islands (U.S.)','wprentals'),esc_html__('Wallis and Futuna Islands','wprentals'),esc_html__('Western Sahara','wprentals'),esc_html__('Yemen','wprentals'),esc_html__('Yugoslavia','wprentals'),esc_html__('Zambia','wprentals'),esc_html__('Zimbabwe','wprentals'));

    
    
    $countries = array(     'Afghanistan'           => esc_html__('Afghanistan','wprentals'),
                            'Albania'               => esc_html__('Albania','wprentals'),
                            'Algeria'               => esc_html__('Algeria','wprentals'),
                            'American Samoa'        => esc_html__('American Samoa','wprentals'),
                            'Andorra'               => esc_html__('Andorra','wprentals'),
                            'Angola'                => esc_html__('Angola','wprentals'),
                            'Anguilla'              => esc_html__('Anguilla','wprentals'),
                            'Antarctica'            => esc_html__('Antarctica','wprentals'),
                            'Antigua and Barbuda'   => esc_html__('Antigua and Barbuda','wprentals'),
                            'Argentina'             => esc_html__('Argentina','wprentals'),
                            'Armenia'               => esc_html__('Armenia','wprentals'),
                            'Aruba'                 => esc_html__('Aruba','wprentals'),
                            'Australia'             => esc_html__('Australia','wprentals'),
                            'Austria'               => esc_html__('Austria','wprentals'),
                            'Azerbaijan'            => esc_html__('Azerbaijan','wprentals'),
                            'Bahamas'               => esc_html__('Bahamas','wprentals'),
                            'Bahrain'               => esc_html__('Bahrain','wprentals'),
                            'Bangladesh'            => esc_html__('Bangladesh','wprentals'),
                            'Barbados'              => esc_html__('Barbados','wprentals'),
                            'Belarus'               => esc_html__('Belarus','wprentals'),
                            'Belgium'               => esc_html__('Belgium','wprentals'),
                            'Belize'                => esc_html__('Belize','wprentals'),
                            'Benin'                 => esc_html__('Benin','wprentals'),
                            'Bermuda'               => esc_html__('Bermuda','wprentals'),
                            'Bhutan'                => esc_html__('Bhutan','wprentals'),
                            'Bolivia'               => esc_html__('Bolivia','wprentals'),
                            'Bosnia and Herzegowina'=> esc_html__('Bosnia and Herzegowina','wprentals'),
                            'Botswana'              => esc_html__('Botswana','wprentals'),
                            'Bouvet Island'         => esc_html__('Bouvet Island','wprentals'),
                            'Brazil'                => esc_html__('Brazil','wprentals'),
                            'British Indian Ocean Territory'=> esc_html__('British Indian Ocean Territory','wprentals'),
                            'Brunei Darussalam'     => esc_html__('Brunei Darussalam','wprentals'),
                            'Bulgaria'              => esc_html__('Bulgaria','wprentals'),
                            'Burkina Faso'          => esc_html__('Burkina Faso','wprentals'),
                            'Burundi'               => esc_html__('Burundi','wprentals'),
                            'Cambodia'              => esc_html__('Cambodia','wprentals'),
                            'Cameroon'              => esc_html__('Cameroon','wprentals'),
                            'Canada'                => esc_html__('Canada','wprentals'),
                            'Cape Verde'            => esc_html__('Cape Verde','wprentals'),
                            'Cayman Islands'        => esc_html__('Cayman Islands','wprentals'),
                            'Central African Republic'  => esc_html__('Central African Republic','wprentals'),
                            'Chad'                  => esc_html__('Chad','wprentals'),
                            'Chile'                 => esc_html__('Chile','wprentals'),
                            'China'                 => esc_html__('China','wprentals'),
                            'Christmas Island'      => esc_html__('Christmas Island','wprentals'),
                            'Cocos (Keeling) Islands' => esc_html__('Cocos (Keeling) Islands','wprentals'),
                            'Colombia'              => esc_html__('Colombia','wprentals'),
                            'Comoros'               => esc_html__('Comoros','wprentals'),
                            'Congo'                 => esc_html__('Congo','wprentals'),
                            'Congo, the Democratic Republic of the' => esc_html__('Congo, the Democratic Republic of the','wprentals'),
                            'Cook Islands'          => esc_html__('Cook Islands','wprentals'),
                            'Costa Rica'            => esc_html__('Costa Rica','wprentals'),
                            'Cote dIvoire'          => esc_html__('Cote dIvoire','wprentals'),
                            'Croatia'               => esc_html__('Croatia','wprentals'),
                            'Cuba'                  => esc_html__('Cuba','wprentals'),
                            'Curacao'               => esc_html__('Curacao','wprentals'),
                            'Cyprus'                => esc_html__('Cyprus','wprentals'),
                            'Czech Republic'        => esc_html__('Czech Republic','wprentals'),
                            'Denmark'               => esc_html__('Denmark','wprentals'),
                            'Djibouti'              => esc_html__('Djibouti','wprentals'),
                            'Dominica'              => esc_html__('Dominica','wprentals'),
                            'Dominican Republic'    => esc_html__('Dominican Republic','wprentals'),
                            'East Timor'            => esc_html__('East Timor','wprentals'),
                            'Ecuador'               => esc_html__('Ecuador','wprentals'),
                            'Egypt'                 => esc_html__('Egypt','wprentals'),
                            'El Salvador'           => esc_html__('El Salvador','wprentals'),
                            'Equatorial Guinea'     => esc_html__('Equatorial Guinea','wprentals'),
                            'Eritrea'               => esc_html__('Eritrea','wprentals'),
                            'Estonia'               => esc_html__('Estonia','wprentals'),
                            'Ethiopia'              => esc_html__('Ethiopia','wprentals'),
                            'Falkland Islands (Malvinas)' => esc_html__('Falkland Islands (Malvinas)','wprentals'),
                            'Faroe Islands'         => esc_html__('Faroe Islands','wprentals'),
                            'Fiji'                  => esc_html__('Fiji','wprentals'),
                            'Finland'               => esc_html__('Finland','wprentals'),
                            'France'                => esc_html__('France','wprentals'),
                            'France Metropolitan'   => esc_html__('France Metropolitan','wprentals'),
                            'French Guiana'         => esc_html__('French Guiana','wprentals'),
                            'French Polynesia'      => esc_html__('French Polynesia','wprentals'),
                            'French Southern Territories' => esc_html__('French Southern Territories','wprentals'),
                            'Gabon'                 => esc_html__('Gabon','wprentals'),
                            'Gambia'                => esc_html__('Gambia','wprentals'),
                            'Georgia'               => esc_html__('Georgia','wprentals'),
                            'Germany'               => esc_html__('Germany','wprentals'),
                            'Ghana'                 => esc_html__('Ghana','wprentals'),
                            'Gibraltar'             => esc_html__('Gibraltar','wprentals'),
                            'Greece'                => esc_html__('Greece','wprentals'),
                            'Greenland'             => esc_html__('Greenland','wprentals'),
                            'Grenada'               => esc_html__('Grenada','wprentals'),
                            'Guadeloupe'            => esc_html__('Guadeloupe','wprentals'),
                            'Guam'                  => esc_html__('Guam','wprentals'),
                            'Guatemala'             => esc_html__('Guatemala','wprentals'),
                            'Guinea'                => esc_html__('Guinea','wprentals'),
                            'Guinea-Bissau'         => esc_html__('Guinea-Bissau','wprentals'),
                            'Guyana'                => esc_html__('Guyana','wprentals'),
                            'Haiti'                 => esc_html__('Haiti','wprentals'),
                            'Heard and Mc Donald Islands'  => esc_html__('Heard and Mc Donald Islands','wprentals'),
                            'Holy See (Vatican City State)'=> esc_html__('Holy See (Vatican City State)','wprentals'),
                            'Honduras'              => esc_html__('Honduras','wprentals'),
                            'Hong Kong'             => esc_html__('Hong Kong','wprentals'),
                            'Hungary'               => esc_html__('Hungary','wprentals'),
                            'Iceland'               => esc_html__('Iceland','wprentals'),
                            'India'                 => esc_html__('India','wprentals'),
                            'Indonesia'             => esc_html__('Indonesia','wprentals'),
                            'Iran (Islamic Republic of)'  => esc_html__('Iran (Islamic Republic of)','wprentals'),
                            'Iraq'                  => esc_html__('Iraq','wprentals'),
                            'Ireland'               => esc_html__('Ireland','wprentals'),
                            'Israel'                => esc_html__('Israel','wprentals'),
                            'Italy'                 => esc_html__('Italy','wprentals'),
                            'Jamaica'               => esc_html__('Jamaica','wprentals'),
                            'Japan'                 => esc_html__('Japan','wprentals'),
                            'Jordan'                => esc_html__('Jordan','wprentals'),
                            'Kazakhstan'            => esc_html__('Kazakhstan','wprentals'),
                            'Kenya'                 => esc_html__('Kenya','wprentals'),
                            'Kiribati'              => esc_html__('Kiribati','wprentals'),
                            'Korea, Democratic People Republic of'  => esc_html__('Korea, Democratic People Republic of','wprentals'),
                            'Korea, Republic of'    => esc_html__('Korea, Republic of','wprentals'),
                            'Kuwait'                => esc_html__('Kuwait','wprentals'),
                            'Kyrgyzstan'            => esc_html__('Kyrgyzstan','wprentals'),
                            'Lao, People Democratic Republic' => esc_html__('Lao, People Democratic Republic','wprentals'),
                            'Latvia'                => esc_html__('Latvia','wprentals'),
                            'Lebanon'               => esc_html__('Lebanon','wprentals'),
                            'Lesotho'               => esc_html__('Lesotho','wprentals'),
                            'Liberia'               => esc_html__('Liberia','wprentals'),
                            'Libyan Arab Jamahiriya'=> esc_html__('Libyan Arab Jamahiriya','wprentals'),
                            'Liechtenstein'         => esc_html__('Liechtenstein','wprentals'),
                            'Lithuania'             => esc_html__('Lithuania','wprentals'),
                            'Luxembourg'            => esc_html__('Luxembourg','wprentals'),
                            'Macau'                 => esc_html__('Macau','wprentals'),
                            'Macedonia, The Former Yugoslav Republic of'    => esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals'),
                            'Madagascar'            => esc_html__('Madagascar','wprentals'),
                            'Malawi'                => esc_html__('Malawi','wprentals'),
                            'Malaysia'              => esc_html__('Malaysia','wprentals'),
                            'Maldives'              => esc_html__('Maldives','wprentals'),
                            'Mali'                  => esc_html__('Mali','wprentals'),
                            'Malta'                 => esc_html__('Malta','wprentals'),
                            'Marshall Islands'      => esc_html__('Marshall Islands','wprentals'),
                            'Martinique'            => esc_html__('Martinique','wprentals'),
                            'Mauritania'            => esc_html__('Mauritania','wprentals'),
                            'Mauritius'             => esc_html__('Mauritius','wprentals'),
                            'Mayotte'               => esc_html__('Mayotte','wprentals'),
                            'Mexico'                => esc_html__('Mexico','wprentals'),
                            'Micronesia, Federated States of'    => esc_html__('Micronesia, Federated States of','wprentals'),
                            'Moldova, Republic of'  => esc_html__('Moldova, Republic of','wprentals'),
                            'Monaco'                => esc_html__('Monaco','wprentals'),
                            'Mongolia'              => esc_html__('Mongolia','wprentals'),
                            'Montserrat'            => esc_html__('Montserrat','wprentals'),
                            'Morocco'               => esc_html__('Morocco','wprentals'),
                            'Mozambique'            => esc_html__('Mozambique','wprentals'),
                            'Montenegro'            => esc_html__('Montenegro','wprentals'),
                            'Myanmar'               => esc_html__('Myanmar','wprentals'),
                            'Namibia'               => esc_html__('Namibia','wprentals'),
                            'Nauru'                 => esc_html__('Nauru','wprentals'),
                            'Nepal'                 => esc_html__('Nepal','wprentals'),
                            'Netherlands'           => esc_html__('Netherlands','wprentals'),
                            'Netherlands Antilles'  => esc_html__('Netherlands Antilles','wprentals'),
                            'New Caledonia'         => esc_html__('New Caledonia','wprentals'),
                            'New Zealand'           => esc_html__('New Zealand','wprentals'),
                            'Nicaragua'             => esc_html__('Nicaragua','wprentals'),
                            'Niger'                 => esc_html__('Niger','wprentals'),
                            'Nigeria'               => esc_html__('Nigeria','wprentals'),
                            'Niue'                  => esc_html__('Niue','wprentals'),
                            'Norfolk Island'        => esc_html__('Norfolk Island','wprentals'),
                            'Northern Mariana Islands' => esc_html__('Northern Mariana Islands','wprentals'),
                            'Norway'                => esc_html__('Norway','wprentals'),
                            'Oman'                  => esc_html__('Oman','wprentals'),
                            'Pakistan'              => esc_html__('Pakistan','wprentals'),
                            'Palau'                 => esc_html__('Palau','wprentals'),
                            'Panama'                => esc_html__('Panama','wprentals'),
                            'Papua New Guinea'      => esc_html__('Papua New Guinea','wprentals'),
                            'Paraguay'              => esc_html__('Paraguay','wprentals'),
                            'Peru'                  => esc_html__('Peru','wprentals'),
                            'Philippines'           => esc_html__('Philippines','wprentals'),
                            'Pitcairn'              => esc_html__('Pitcairn','wprentals'),
                            'Poland'                => esc_html__('Poland','wprentals'),
                            'Portugal'              => esc_html__('Portugal','wprentals'),
                            'Puerto Rico'           => esc_html__('Puerto Rico','wprentals'),
                            'Qatar'                 => esc_html__('Qatar','wprentals'),
                            'Reunion'               => esc_html__('Reunion','wprentals'),
                            'Romania'               => esc_html__('Romania','wprentals'),
                            'Russian Federation'    => esc_html__('Russian Federation','wprentals'),
                            'Rwanda'                => esc_html__('Rwanda','wprentals'),
                            'Saint Kitts and Nevis' => esc_html__('Saint Kitts and Nevis','wprentals'),
                            'Saint Lucia'           => esc_html__('Saint Lucia','wprentals'),
                            'Saint Vincent and the Grenadines' => esc_html__('Saint Vincent and the Grenadines','wprentals'),
                            'Samoa'                 => esc_html__('Samoa','wprentals'),
                            'San Marino'            => esc_html__('San Marino','wprentals'),
                            'Sao Tome and Principe' => esc_html__('Sao Tome and Principe','wprentals'),
                            'Saudi Arabia'          => esc_html__('Saudi Arabia','wprentals'),
                            'Serbia'                => esc_html__('Serbia','wprentals'),
                            'Senegal'               => esc_html__('Senegal','wprentals'),
                            'Seychelles'            => esc_html__('Seychelles','wprentals'),
                            'Sierra Leone'          => esc_html__('Sierra Leone','wprentals'),
                            'Singapore'             => esc_html__('Singapore','wprentals'),
                            'Slovakia (Slovak Republic)'=> esc_html__('Slovakia (Slovak Republic)','wprentals'),
                            'Slovenia'              => esc_html__('Slovenia','wprentals'),
                            'Solomon Islands'       => esc_html__('Solomon Islands','wprentals'),
                            'Somalia'               => esc_html__('Somalia','wprentals'),
                            'South Africa'          => esc_html__('South Africa','wprentals'),
                            'South Georgia and the South Sandwich Islands' => esc_html__('South Georgia and the South Sandwich Islands','wprentals'),
                            'Spain'                 => esc_html__('Spain','wprentals'),
                            'Sri Lanka'             => esc_html__('Sri Lanka','wprentals'),
                            'St. Helena'            => esc_html__('St. Helena','wprentals'),
                            'St. Pierre and Miquelon'=> esc_html__('St. Pierre and Miquelon','wprentals'),
                            'Sudan'                 => esc_html__('Sudan','wprentals'),
                            'Suriname'              => esc_html__('Suriname','wprentals'),
                            'Svalbard and Jan Mayen Islands'    => esc_html__('Svalbard and Jan Mayen Islands','wprentals'),
                            'Swaziland'             => esc_html__('Swaziland','wprentals'),
                            'Sweden'                => esc_html__('Sweden','wprentals'),
                            'Switzerland'           => esc_html__('Switzerland','wprentals'),
                            'Syrian Arab Republic'  => esc_html__('Syrian Arab Republic','wprentals'),
                            'Taiwan, Province of China' => esc_html__('Taiwan, Province of China','wprentals'),
                            'Tajikistan'            => esc_html__('Tajikistan','wprentals'),
                            'Tanzania, United Republic of'=> esc_html__('Tanzania, United Republic of','wprentals'),
                            'Thailand'              => esc_html__('Thailand','wprentals'),
                            'Togo'                  => esc_html__('Togo','wprentals'),
                            'Tokelau'               => esc_html__('Tokelau','wprentals'),
                            'Tonga'                 => esc_html__('Tonga','wprentals'),
                            'Trinidad and Tobago'   => esc_html__('Trinidad and Tobago','wprentals'),
                            'Tunisia'               => esc_html__('Tunisia','wprentals'),
                            'Turkey'                => esc_html__('Turkey','wprentals'),
                            'Turkmenistan'          => esc_html__('Turkmenistan','wprentals'),
                            'Turks and Caicos Islands'  => esc_html__('Turks and Caicos Islands','wprentals'),
                            'Tuvalu'                => esc_html__('Tuvalu','wprentals'),
                            'Uganda'                => esc_html__('Uganda','wprentals'),
                            'Ukraine'               => esc_html__('Ukraine','wprentals'),
                            'United Arab Emirates'  => esc_html__('United Arab Emirates','wprentals'),
                            'United Kingdom'        => esc_html__('United Kingdom','wprentals'),
                            'United States'         => esc_html__('United States','wprentals'),
                            'United States Minor Outlying Islands'  => esc_html__('United States Minor Outlying Islands','wprentals'),
                            'Uruguay'               => esc_html__('Uruguay','wprentals'),
                            'Uzbekistan'            => esc_html__('Uzbekistan','wprentals'),
                            'Vanuatu'               => esc_html__('Vanuatu','wprentals'),
                            'Venezuela'             => esc_html__('Venezuela','wprentals'),
                            'Vietnam'               => esc_html__('Vietnam','wprentals'),
                            'Virgin Islands (British)'=> esc_html__('Virgin Islands (British)','wprentals'),
                            'Virgin Islands (U.S.)' => esc_html__('Virgin Islands (U.S.)','wprentals'),
                            'Wallis and Futuna Islands' => esc_html__('Wallis and Futuna Islands','wprentals'),
                            'Western Sahara'        => esc_html__('Western Sahara','wprentals'),
                            'Yemen'                 => esc_html__('Yemen','wprentals'),
                            'Yugoslavia'            => esc_html__('Yugoslavia','wprentals'),
                            'Zambia'                => esc_html__('Zambia','wprentals'),
                            'Zimbabwe'              => esc_html__('Zimbabwe','wprentals')
        );

    
    
    if ($selected == '') {
        $selected = get_option('wp_estate_general_country');
    }
    
    $country_select = '<select id="property_country"  name="property_country" class="'.$class.'">';

   
    foreach ($countries as $key=>$country) {
        $country_select.='<option value="' . $key . '"';
        if (strtolower($selected) == strtolower ($key) ) {
            $country_select.='selected="selected"';
        }
        $country_select.='>' . $country . '</option>';
    }

    $country_select.='</select>';
    return $country_select;
}
endif; // end   wpestate_country_list 



if( !function_exists('wpestate_agent_list') ):
    function wpestate_agent_list($mypost) {
        return $agent_list;
    }
endif; // end   wpestate_agent_list



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Manage property lists
///////////////////////////////////////////////////////////////////////////////////////////////////////////
add_filter( 'manage_edit-estate_property_columns', 'wpestate_my_columns' );

if( !function_exists('wpestate_my_columns') ):
    function wpestate_my_columns( $columns ) {
        $slice=array_slice($columns,2,2);
        unset( $columns['comments'] );
        unset( $slice['comments'] );
        $splice=array_splice($columns, 2); 
        $columns['estate_id']   = esc_html__( 'Id','wprentals');
        $columns['estate_image']   = esc_html__( 'Image','wprentals');
        $columns['estate_action']   = esc_html__( 'Action','wprentals');
        $columns['estate_category'] = esc_html__( 'Category','wprentals');
        $columns['estate_autor']    = esc_html__( 'User','wprentals');
        $columns['estate_status']   = esc_html__( 'Status','wprentals');
        $columns['estate_price']    = esc_html__( 'Price per night','wprentals');
        return  array_merge($columns,array_reverse($slice));
    }
endif; // end   wpestate_my_columns  


add_action( 'manage_posts_custom_column', 'wpestate_populate_columns' );
if( !function_exists('wpestate_populate_columns') ):
    function wpestate_populate_columns( $column ) {
        $the_id=get_the_ID();
        
        if ( 'estate_id' == $column ) {
           print $the_id;
        }
        
        if ( 'estate_image' == $column ) {
           echo get_the_post_thumbnail($the_id,'wpestate_user_thumb');
        }
            
    
        if ( 'estate_status' == $column ) {
            $estate_status = get_post_status(get_the_ID()); 
            if($estate_status=='publish'){
                echo esc_html__( 'published','wprentals');
            }else{
                print $estate_status;
            }

            $pay_status    = get_post_meta(get_the_ID(), 'pay_status', true);
            if($pay_status!=''){
                echo " | ".$pay_status;
            }

        } 

        if ( 'estate_autor' == $column ) {
            $user_id=wpsestate_get_author(get_the_ID());
            $estate_autor = get_the_author_meta('display_name');; 
            echo '<a href="'.get_edit_user_link($user_id).'" >'. $estate_autor.'</a>';
        } 

        if ( 'estate_action' == $column ) {
            $estate_action = get_the_term_list( get_the_ID(), 'property_action_category', '', ', ', '');
            print $estate_action;
        }
        elseif ( 'estate_category' == $column ) {
            $estate_category = get_the_term_list( get_the_ID(), 'property_category', '', ', ', '');
            print $estate_category ;
        }
        
        if ( 'estate_price' == $column ) {
            $currency                   =   esc_html( get_option('wp_estate_currency_label_main', '') );
            $where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
            wpestate_show_price(get_the_ID(),$currency,$where_currency,0);
        }
    }
endif; // end   wpestate_populate_columns 






add_filter( 'manage_edit-estate_property_sortable_columns', 'wpestate_sort_me' );
if( !function_exists('wpestate_sort_me') ):
    function wpestate_sort_me( $columns ) {
      
        $columns['estate_autor'] = 'estate_autor';
        $columns['estate_price'] = 'estate_price';
        return $columns;
    }
endif; // end   wpestate_sort_me 


add_filter( 'request', 'bs_event_date_column_orderby' );
function bs_event_date_column_orderby( $vars ) {
  
    if ( isset( $vars['orderby'] ) && 'estate_price' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'property_price',
            'orderby' => 'meta_value_num'
        ) );
    }
    
    
      if ( isset( $vars['orderby'] ) && 'estate_autor' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'orderby' => 'author'
        ) );
    }
    
   

    return $vars;
}


add_action( 'property_city_edit_form_fields',   'wpestate_property_city_callback_function', 10, 2);
add_action( 'property_city_add_form_fields',    'wpestate_property_city_callback_add_function', 10, 2 );  
add_action( 'created_property_city',            'wpestate_property_city_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_city',             'wpestate_property_city_save_extra_fields_callback', 10, 2);

if( !function_exists('wpestate_property_city_callback_function') ):
    function wpestate_property_city_callback_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
        <table class="form-table">
        <tbody>    
            <tr class="form-field">
                <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wprentals').'</label></th>
                <td> 
                    <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
                    <p class="description">'.esc_html__( 'Page id for this term','wprentals').'</p>
                </td>

                <tr valign="top">
                    <th scope="row"><label for="category_featured_image">'.esc_html__( 'Featured Image','wprentals').'</label></th>
                    <td>
                        <input id="category_featured_image" type="text" class="postform" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                        <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr> 

                <tr valign="top">
                    <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals').'</label></th>
                    <td>
                        <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                    </td>
                </tr> 



                <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_city" />


            </tr>
        </tbody>
        </table>';
    }
endif;



if( !function_exists('wpestate_property_city_callback_add_function') ):
    function wpestate_property_city_callback_add_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
        <div class="form-field">
        <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wprentals').'</label>
            <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
        </div>

        <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wprentals').'</label>
            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals').'" />
           <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

        </div>     

        <div class="form-field">
        <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals').'</label>
            <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
        </div> 
        <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_city" />
        ';
    }
endif;

if( !function_exists('wpestate_property_city_save_extra_fields_callback') ):
    function wpestate_property_city_save_extra_fields_callback($term_id ){
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
             update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif;
///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Tie area with city
///////////////////////////////////////////////////////////////////////////////////////////////////////////
add_action( 'property_area_edit_form_fields',   'wpestate_property_area_callback_function', 10, 2);
add_action( 'property_area_add_form_fields',    'wpestate_property_area_callback_add_function', 10, 2 );  
add_action( 'created_property_area',            'wpestate_property_area_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_area',             'wpestate_property_area_save_extra_fields_callback', 10, 2);
add_filter('manage_edit-property_area_columns', 'ST4_columns_head');  
add_filter('manage_property_area_custom_column','ST4_columns_content_taxonomy', 10, 3); 




if( !function_exists('ST4_columns_head') ):
    function ST4_columns_head($new_columns) {   
        $new_columns = array(
            'cb'            => '<input type="checkbox" />',
            'name'          => esc_html__( 'Name','wprentals'),
           
            'city'          => esc_html__( 'City','wprentals'),
            'header_icon'   => '',
            'slug'          => esc_html__( 'Slug','wprentals'),
            'posts'         => esc_html__( 'Posts','wprentals'),
            'id'            => __('ID','wprentals'),
            );
        return $new_columns;
    } 
endif; // end   ST4_columns_head  


if( !function_exists('ST4_columns_content_taxonomy') ):
    function ST4_columns_content_taxonomy($out, $column_name, $term_id) {  
        if ($column_name == 'city') {    
            $term_meta= get_option( "taxonomy_$term_id");
            print $term_meta['cityparent'] ;
        }  
        if ($column_name == 'id') {    
            print $term_id;
        }
    }  
endif; // end   ST4_columns_content_taxonomy  




if( !function_exists('wpestate_property_area_callback_add_function') ):
    function wpestate_property_area_callback_add_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $cityparent                 =   $term_meta['cityparent'] ? $term_meta['cityparent'] : ''; 
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $cityparent                 =   wpestate_get_all_cities();
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
            <div class="form-field">
            <label for="term_meta[cityparent]">'. esc_html__( 'Which city has this area','wprentals').'</label>
                <select name="term_meta[cityparent]" class="postform">  
                    '.$cityparent.'
                </select>
            </div>
            ';

         print'
            <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wprentals').'</label>
                <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
            </div>

            <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wprentals').'</label>
                <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals').'" />
                <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

            </div> 


            <div class="form-field">
            <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals').'</label>
                <input id="category_featured_image" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
            </div>  
            <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_area" />
            ';
    }
endif; // end     




if( !function_exists('wpestate_property_area_callback_function') ):
    function wpestate_property_area_callback_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $cityparent                 =   $term_meta['cityparent'] ? $term_meta['cityparent'] : ''; 
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   '';
            if(isset( $term_meta['category_featured_image'])){
                $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            }
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';

            $cityparent =   wpestate_get_all_cities($cityparent);
        }else{
            $cityparent                 =   wpestate_get_all_cities();
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
            <table class="form-table">
            <tbody>
                    <tr class="form-field">
                            <th scope="row" valign="top"><label for="term_meta[cityparent]">'. esc_html__( 'Which city has this area','wprentals').'</label></th>
                            <td> 
                                <select name="term_meta[cityparent]" class="postform">  
                                 '.$cityparent.'
                                    </select>
                                <p class="description">'.esc_html__( 'City that has this area','wprentals').'</p>
                            </td>
                    </tr>

                   <tr class="form-field">
                            <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wprentals').'</label></th>
                            <td> 
                                <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
                                <p class="description">'.esc_html__( 'Page id for this term','wprentals').'</p>
                            </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="logo_image">'.esc_html__( 'Featured Image','wprentals').'</label></th>
                        <td>
                            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals').'" />
                            <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                        </td>
                    </tr> 

                    <tr valign="top">
                        <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals').'</label></th>
                        <td>
                          <input id="category_featured_image" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                        </td>
                    </tr> 


                    <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_area" />




              </tbody>
             </table>';
    }
endif; // end     



if( !function_exists('wpestate_get_all_cities') ): 
    function wpestate_get_all_cities($selected=''){
        $taxonomy       =   'property_city';
        $args = array(
            'hide_empty'    => false
        );
        $tax_terms      =   get_terms($taxonomy,$args);
        $select_city    =   '';

        foreach ($tax_terms as $tax_term) {             
            $select_city.= '<option value="' . $tax_term->name.'" ';
            if($tax_term->name == $selected){
                $select_city.= ' selected="selected" ';
            }
            $select_city.= ' >' . $tax_term->name . '</option>'; 
        }
        return $select_city;
    }
endif; // end   wpestate_get_all_cities 




if( !function_exists('wpestate_property_area_save_extra_fields_callback') ):
    function wpestate_property_area_save_extra_fields_callback($term_id ){
          if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif; // end     


add_action( 'init', 'wpestate_my_custom_post_status' );
if( !function_exists('wpestate_my_custom_post_status') ):
    function wpestate_my_custom_post_status(){
        register_post_status( 'expired', array(
                'label'                     => esc_html__(  'expired', 'wprentals' ),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Membership Expired <span class="count">(%s)</span>', 'Membership Expired <span class="count">(%s)</span>','wprentals' ),
        ) );
        
        register_post_status( 'disabled', array(
                    'label'                     => esc_html__(  'disabled', 'wprentals' ),
                    'public'                    => false,
                    'exclude_from_search'       => false,
                    'show_in_admin_all_list'    => true,
                    'show_in_admin_status_list' => true,
                    'label_count'               => _n_noop( 'Disabled by user <span class="count">(%s)</span>', 'Disabled by user <span class="count">(%s)</span>','wprentals' ),
            ) );
        
    }
endif; // end   wpestate_my_custom_post_status  







///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Tie area with city
// property_category
//property_action_category
///////////////////////////////////////////////////////////////////////////////////////////////////////////
add_action('property_category_edit_form_fields',   'wpestate_property_category_callback_function', 10, 2);
add_action('property_category_add_form_fields',    'wpestate_property_category_callback_add_function', 10, 2 );  
add_action('created_property_category',            'wpestate_property_category_save_extra_fields_callback', 10, 2);
add_action('edited_property_category',             'wpestate_property_category_save_extra_fields_callback', 10, 2);



if( !function_exists('wpestate_property_category_callback_function') ):
    function wpestate_property_category_callback_function($tag){
       if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
        <table class="form-table">
        <tbody>    
            <tr class="form-field">
                <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wprentals').'</label></th>
                <td> 
                    <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
                    <p class="description">'.esc_html__( 'Page id for this term','wprentals').'</p>
                </td>

                <tr valign="top">
                    <th scope="row"><label for="category_featured_image">'.esc_html__( 'Featured Image','wprentals').'</label></th>
                    <td>
                        <input id="category_featured_image" type="text" class="postform" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                        <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr> 

                <tr valign="top">
                    <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals').'</label></th>
                    <td>
                        <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                    </td>
                </tr> 



                <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_category" />


            </tr>
        </tbody>
        </table>';
    }
endif; // end     


if( !function_exists('wpestate_property_category_callback_add_function') ):
    function wpestate_property_category_callback_add_function($tag){
         if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
        <div class="form-field">
        <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wprentals').'</label>
            <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
        </div>

        <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wprentals').'</label>
            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals').'" />
           <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

        </div>     

        <div class="form-field">
        <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals').'</label>
            <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
        </div> 
        <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_category" />
        ';
    }
endif; // end     


if( !function_exists('wpestate_property_category_save_extra_fields_callback') ):
    function wpestate_property_category_save_extra_fields_callback($term_id ){
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
             update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif; // end     


add_action( 'property_action_category_edit_form_fields',   'wpestate_property_action_category_callback_function', 10, 2);
add_action( 'property_action_category_add_form_fields',    'wpestate_property_action_category_callback_add_function', 10, 2 );  
add_action( 'created_property_action_category',            'wpestate_property_action_category_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_action_category',             'wpestate_property_action_category_save_extra_fields_callback', 10, 2);



if( !function_exists('wpestate_property_action_category_callback_function') ):
    function wpestate_property_action_category_callback_function($tag){
       if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
        <table class="form-table">
        <tbody>    
            <tr class="form-field">
                <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wprentals').'</label></th>
                <td> 
                    <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
                    <p class="description">'.esc_html__( 'Page id for this term','wprentals').'</p>
                </td>

                <tr valign="top">
                    <th scope="row"><label for="category_featured_image">'.esc_html__( 'Featured Image','wprentals').'</label></th>
                    <td>
                        <input id="category_featured_image" type="text" class="postform" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                        <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr> 

                <tr valign="top">
                    <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals').'</label></th>
                    <td>
                        <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                    </td>
                </tr> 



                <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_action_category" />


            </tr>
        </tbody>
        </table>';
    }
endif; // end     


if( !function_exists('wpestate_property_action_category_callback_add_function') ):
    function wpestate_property_action_category_callback_add_function($tag){
       if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
        <div class="form-field">
        <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wprentals').'</label>
            <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
        </div>

        <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wprentals').'</label>
            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wprentals').'" />
           <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

        </div>     

        <div class="form-field">
        <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wprentals').'</label>
            <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
        </div> 
        <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_action_category" />
        ';
    
    }
endif; // end     


if( !function_exists('wpestate_property_action_category_save_extra_fields_callback') ):
    function wpestate_property_action_category_save_extra_fields_callback($term_id ){
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
             update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif; // end     




if( !function_exists('wpestate_return_country_list_translated') ): 
function wpestate_return_country_list_translated($selected='') {
    $countries = array(     'Afghanistan'           => esc_html__('Afghanistan','wprentals'),
                            'Albania'               => esc_html__('Albania','wprentals'),
                            'Algeria'               => esc_html__('Algeria','wprentals'),
                            'American Samoa'        => esc_html__('American Samoa','wprentals'),
                            'Andorra'               => esc_html__('Andorra','wprentals'),
                            'Angola'                => esc_html__('Angola','wprentals'),
                            'Anguilla'              => esc_html__('Anguilla','wprentals'),
                            'Antarctica'            => esc_html__('Antarctica','wprentals'),
                            'Antigua and Barbuda'   => esc_html__('Antigua and Barbuda','wprentals'),
                            'Argentina'             => esc_html__('Argentina','wprentals'),
                            'Armenia'               => esc_html__('Armenia','wprentals'),
                            'Aruba'                 => esc_html__('Aruba','wprentals'),
                            'Australia'             => esc_html__('Australia','wprentals'),
                            'Austria'               => esc_html__('Austria','wprentals'),
                            'Azerbaijan'            => esc_html__('Azerbaijan','wprentals'),
                            'Bahamas'               => esc_html__('Bahamas','wprentals'),
                            'Bahrain'               => esc_html__('Bahrain','wprentals'),
                            'Bangladesh'            => esc_html__('Bangladesh','wprentals'),
                            'Barbados'              => esc_html__('Barbados','wprentals'),
                            'Belarus'               => esc_html__('Belarus','wprentals'),
                            'Belgium'               => esc_html__('Belgium','wprentals'),
                            'Belize'                => esc_html__('Belize','wprentals'),
                            'Benin'                 => esc_html__('Benin','wprentals'),
                            'Bermuda'               => esc_html__('Bermuda','wprentals'),
                            'Bhutan'                => esc_html__('Bhutan','wprentals'),
                            'Bolivia'               => esc_html__('Bolivia','wprentals'),
                            'Bosnia and Herzegowina'=> esc_html__('Bosnia and Herzegowina','wprentals'),
                            'Botswana'              => esc_html__('Botswana','wprentals'),
                            'Bouvet Island'         => esc_html__('Bouvet Island','wprentals'),
                            'Brazil'                => esc_html__('Brazil','wprentals'),
                            'British Indian Ocean Territory'=> esc_html__('British Indian Ocean Territory','wprentals'),
                            'Brunei Darussalam'     => esc_html__('Brunei Darussalam','wprentals'),
                            'Bulgaria'              => esc_html__('Bulgaria','wprentals'),
                            'Burkina Faso'          => esc_html__('Burkina Faso','wprentals'),
                            'Burundi'               => esc_html__('Burundi','wprentals'),
                            'Cambodia'              => esc_html__('Cambodia','wprentals'),
                            'Cameroon'              => esc_html__('Cameroon','wprentals'),
                            'Canada'                => esc_html__('Canada','wprentals'),
                            'Cape Verde'            => esc_html__('Cape Verde','wprentals'),
                            'Cayman Islands'        => esc_html__('Cayman Islands','wprentals'),
                            'Central African Republic'  => esc_html__('Central African Republic','wprentals'),
                            'Chad'                  => esc_html__('Chad','wprentals'),
                            'Chile'                 => esc_html__('Chile','wprentals'),
                            'China'                 => esc_html__('China','wprentals'),
                            'Christmas Island'      => esc_html__('Christmas Island','wprentals'),
                            'Cocos (Keeling) Islands' => esc_html__('Cocos (Keeling) Islands','wprentals'),
                            'Colombia'              => esc_html__('Colombia','wprentals'),
                            'Comoros'               => esc_html__('Comoros','wprentals'),
                            'Congo'                 => esc_html__('Congo','wprentals'),
                            'Congo, the Democratic Republic of the' => esc_html__('Congo, the Democratic Republic of the','wprentals'),
                            'Cook Islands'          => esc_html__('Cook Islands','wprentals'),
                            'Costa Rica'            => esc_html__('Costa Rica','wprentals'),
                            'Cote dIvoire'          => esc_html__('Cote dIvoire','wprentals'),
                            'Croatia (Hrvatska)'    => esc_html__('Croatia (Hrvatska)','wprentals'),
                            'Cuba'                  => esc_html__('Cuba','wprentals'),
                            'Curacao'               => esc_html__('Curacao','wprentals'),
                            'Cyprus'                => esc_html__('Cyprus','wprentals'),
                            'Czech Republic'        => esc_html__('Czech Republic','wprentals'),
                            'Denmark'               => esc_html__('Denmark','wprentals'),
                            'Djibouti'              => esc_html__('Djibouti','wprentals'),
                            'Dominica'              => esc_html__('Dominica','wprentals'),
                            'Dominican Republic'    => esc_html__('Dominican Republic','wprentals'),
                            'East Timor'            => esc_html__('East Timor','wprentals'),
                            'Ecuador'               => esc_html__('Ecuador','wprentals'),
                            'Egypt'                 => esc_html__('Egypt','wprentals'),
                            'El Salvador'           => esc_html__('El Salvador','wprentals'),
                            'Equatorial Guinea'     => esc_html__('Equatorial Guinea','wprentals'),
                            'Eritrea'               => esc_html__('Eritrea','wprentals'),
                            'Estonia'               => esc_html__('Estonia','wprentals'),
                            'Ethiopia'              => esc_html__('Ethiopia','wprentals'),
                            'Falkland Islands (Malvinas)' => esc_html__('Falkland Islands (Malvinas)','wprentals'),
                            'Faroe Islands'         => esc_html__('Faroe Islands','wprentals'),
                            'Fiji'                  => esc_html__('Fiji','wprentals'),
                            'Finland'               => esc_html__('Finland','wprentals'),
                            'France'                => esc_html__('France','wprentals'),
                            'France Metropolitan'   => esc_html__('France Metropolitan','wprentals'),
                            'French Guiana'         => esc_html__('French Guiana','wprentals'),
                            'French Polynesia'      => esc_html__('French Polynesia','wprentals'),
                            'French Southern Territories' => esc_html__('French Southern Territories','wprentals'),
                            'Gabon'                 => esc_html__('Gabon','wprentals'),
                            'Gambia'                => esc_html__('Gambia','wprentals'),
                            'Georgia'               => esc_html__('Georgia','wprentals'),
                            'Germany'               => esc_html__('Germany','wprentals'),
                            'Ghana'                 => esc_html__('Ghana','wprentals'),
                            'Gibraltar'             => esc_html__('Gibraltar','wprentals'),
                            'Greece'                => esc_html__('Greece','wprentals'),
                            'Greenland'             => esc_html__('Greenland','wprentals'),
                            'Grenada'               => esc_html__('Grenada','wprentals'),
                            'Guadeloupe'            => esc_html__('Guadeloupe','wprentals'),
                            'Guam'                  => esc_html__('Guam','wprentals'),
                            'Guatemala'             => esc_html__('Guatemala','wprentals'),
                            'Guinea'                => esc_html__('Guinea','wprentals'),
                            'Guinea-Bissau'         => esc_html__('Guinea-Bissau','wprentals'),
                            'Guyana'                => esc_html__('Guyana','wprentals'),
                            'Haiti'                 => esc_html__('Haiti','wprentals'),
                            'Heard and Mc Donald Islands'  => esc_html__('Heard and Mc Donald Islands','wprentals'),
                            'Holy See (Vatican City State)'=> esc_html__('Holy See (Vatican City State)','wprentals'),
                            'Honduras'              => esc_html__('Honduras','wprentals'),
                            'Hong Kong'             => esc_html__('Hong Kong','wprentals'),
                            'Hungary'               => esc_html__('Hungary','wprentals'),
                            'Iceland'               => esc_html__('Iceland','wprentals'),
                            'India'                 => esc_html__('India','wprentals'),
                            'Indonesia'             => esc_html__('Indonesia','wprentals'),
                            'Iran (Islamic Republic of)'  => esc_html__('Iran (Islamic Republic of)','wprentals'),
                            'Iraq'                  => esc_html__('Iraq','wprentals'),
                            'Ireland'               => esc_html__('Ireland','wprentals'),
                            'Israel'                => esc_html__('Israel','wprentals'),
                            'Italy'                 => esc_html__('Italy','wprentals'),
                            'Jamaica'               => esc_html__('Jamaica','wprentals'),
                            'Japan'                 => esc_html__('Japan','wprentals'),
                            'Jordan'                => esc_html__('Jordan','wprentals'),
                            'Kazakhstan'            => esc_html__('Kazakhstan','wprentals'),
                            'Kenya'                 => esc_html__('Kenya','wprentals'),
                            'Kiribati'              => esc_html__('Kiribati','wprentals'),
                            'Korea, Democratic People Republic of'  => esc_html__('Korea, Democratic People Republic of','wprentals'),
                            'Korea, Republic of'    => esc_html__('Korea, Republic of','wprentals'),
                            'Kuwait'                => esc_html__('Kuwait','wprentals'),
                            'Kyrgyzstan'            => esc_html__('Kyrgyzstan','wprentals'),
                            'Lao, People Democratic Republic' => esc_html__('Lao, People Democratic Republic','wprentals'),
                            'Latvia'                => esc_html__('Latvia','wprentals'),
                            'Lebanon'               => esc_html__('Lebanon','wprentals'),
                            'Lesotho'               => esc_html__('Lesotho','wprentals'),
                            'Liberia'               => esc_html__('Liberia','wprentals'),
                            'Libyan Arab Jamahiriya'=> esc_html__('Libyan Arab Jamahiriya','wprentals'),
                            'Liechtenstein'         => esc_html__('Liechtenstein','wprentals'),
                            'Lithuania'             => esc_html__('Lithuania','wprentals'),
                            'Luxembourg'            => esc_html__('Luxembourg','wprentals'),
                            'Macau'                 => esc_html__('Macau','wprentals'),
                            'Macedonia, The Former Yugoslav Republic of'    => esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals'),
                            'Madagascar'            => esc_html__('Madagascar','wprentals'),
                            'Malawi'                => esc_html__('Malawi','wprentals'),
                            'Malaysia'              => esc_html__('Malaysia','wprentals'),
                            'Maldives'              => esc_html__('Maldives','wprentals'),
                            'Mali'                  => esc_html__('Mali','wprentals'),
                            'Malta'                 => esc_html__('Malta','wprentals'),
                            'Marshall Islands'      => esc_html__('Marshall Islands','wprentals'),
                            'Martinique'            => esc_html__('Martinique','wprentals'),
                            'Mauritania'            => esc_html__('Mauritania','wprentals'),
                            'Mauritius'             => esc_html__('Mauritius','wprentals'),
                            'Mayotte'               => esc_html__('Mayotte','wprentals'),
                            'Mexico'                => esc_html__('Mexico','wprentals'),
                            'Micronesia, Federated States of'    => esc_html__('Micronesia, Federated States of','wprentals'),
                            'Moldova, Republic of'  => esc_html__('Moldova, Republic of','wprentals'),
                            'Monaco'                => esc_html__('Monaco','wprentals'),
                            'Mongolia'              => esc_html__('Mongolia','wprentals'),
                            'Montserrat'            => esc_html__('Montserrat','wprentals'),
                            'Morocco'               => esc_html__('Morocco','wprentals'),
                            'Mozambique'            => esc_html__('Mozambique','wprentals'),
                            'Montenegro'            => esc_html__('Montenegro','wprentals'),
                            'Myanmar'               => esc_html__('Myanmar','wprentals'),
                            'Namibia'               => esc_html__('Namibia','wprentals'),
                            'Nauru'                 => esc_html__('Nauru','wprentals'),
                            'Nepal'                 => esc_html__('Nepal','wprentals'),
                            'Netherlands'           => esc_html__('Netherlands','wprentals'),
                            'Netherlands Antilles'  => esc_html__('Netherlands Antilles','wprentals'),
                            'New Caledonia'         => esc_html__('New Caledonia','wprentals'),
                            'New Zealand'           => esc_html__('New Zealand','wprentals'),
                            'Nicaragua'             => esc_html__('Nicaragua','wprentals'),
                            'Niger'                 => esc_html__('Niger','wprentals'),
                            'Nigeria'               => esc_html__('Nigeria','wprentals'),
                            'Niue'                  => esc_html__('Niue','wprentals'),
                            'Norfolk Island'        => esc_html__('Norfolk Island','wprentals'),
                            'Northern Mariana Islands' => esc_html__('Northern Mariana Islands','wprentals'),
                            'Norway'                => esc_html__('Norway','wprentals'),
                            'Oman'                  => esc_html__('Oman','wprentals'),
                            'Pakistan'              => esc_html__('Pakistan','wprentals'),
                            'Palau'                 => esc_html__('Palau','wprentals'),
                            'Panama'                => esc_html__('Panama','wprentals'),
                            'Papua New Guinea'      => esc_html__('Papua New Guinea','wprentals'),
                            'Paraguay'              => esc_html__('Paraguay','wprentals'),
                            'Peru'                  => esc_html__('Peru','wprentals'),
                            'Philippines'           => esc_html__('Philippines','wprentals'),
                            'Pitcairn'              => esc_html__('Pitcairn','wprentals'),
                            'Poland'                => esc_html__('Poland','wprentals'),
                            'Portugal'              => esc_html__('Portugal','wprentals'),
                            'Puerto Rico'           => esc_html__('Puerto Rico','wprentals'),
                            'Qatar'                 => esc_html__('Qatar','wprentals'),
                            'Reunion'               => esc_html__('Reunion','wprentals'),
                            'Romania'               => esc_html__('Romania','wprentals'),
                            'Russian Federation'    => esc_html__('Russian Federation','wprentals'),
                            'Rwanda'                => esc_html__('Rwanda','wprentals'),
                            'Saint Kitts and Nevis' => esc_html__('Saint Kitts and Nevis','wprentals'),
                            'Saint Lucia'           => esc_html__('Saint Lucia','wprentals'),
                            'Saint Vincent and the Grenadines' => esc_html__('Saint Vincent and the Grenadines','wprentals'),
                            'Samoa'                 => esc_html__('Samoa','wprentals'),
                            'San Marino'            => esc_html__('San Marino','wprentals'),
                            'Sao Tome and Principe' => esc_html__('Sao Tome and Principe','wprentals'),
                            'Saudi Arabia'          => esc_html__('Saudi Arabia','wprentals'),
                            'Serbia'                => esc_html__('Serbia','wprentals'),
                            'Senegal'               => esc_html__('Senegal','wprentals'),
                            'Seychelles'            => esc_html__('Seychelles','wprentals'),
                            'Sierra Leone'          => esc_html__('Sierra Leone','wprentals'),
                            'Singapore'             => esc_html__('Singapore','wprentals'),
                            'Slovakia (Slovak Republic)'=> esc_html__('Slovakia (Slovak Republic)','wprentals'),
                            'Slovenia'              => esc_html__('Slovenia','wprentals'),
                            'Solomon Islands'       => esc_html__('Solomon Islands','wprentals'),
                            'Somalia'               => esc_html__('Somalia','wprentals'),
                            'South Africa'          => esc_html__('South Africa','wprentals'),
                            'South Georgia and the South Sandwich Islands' => esc_html__('South Georgia and the South Sandwich Islands','wprentals'),
                            'Spain'                 => esc_html__('Spain','wprentals'),
                            'Sri Lanka'             => esc_html__('Sri Lanka','wprentals'),
                            'St. Helena'            => esc_html__('St. Helena','wprentals'),
                            'St. Pierre and Miquelon'=> esc_html__('St. Pierre and Miquelon','wprentals'),
                            'Sudan'                 => esc_html__('Sudan','wprentals'),
                            'Suriname'              => esc_html__('Suriname','wprentals'),
                            'Svalbard and Jan Mayen Islands'    => esc_html__('Svalbard and Jan Mayen Islands','wprentals'),
                            'Swaziland'             => esc_html__('Swaziland','wprentals'),
                            'Sweden'                => esc_html__('Sweden','wprentals'),
                            'Switzerland'           => esc_html__('Switzerland','wprentals'),
                            'Syrian Arab Republic'  => esc_html__('Syrian Arab Republic','wprentals'),
                            'Taiwan, Province of China' => esc_html__('Taiwan, Province of China','wprentals'),
                            'Tajikistan'            => esc_html__('Tajikistan','wprentals'),
                            'Tanzania, United Republic of'=> esc_html__('Tanzania, United Republic of','wprentals'),
                            'Thailand'              => esc_html__('Thailand','wprentals'),
                            'Togo'                  => esc_html__('Togo','wprentals'),
                            'Tokelau'               => esc_html__('Tokelau','wprentals'),
                            'Tonga'                 => esc_html__('Tonga','wprentals'),
                            'Trinidad and Tobago'   => esc_html__('Trinidad and Tobago','wprentals'),
                            'Tunisia'               => esc_html__('Tunisia','wprentals'),
                            'Turkey'                => esc_html__('Turkey','wprentals'),
                            'Turkmenistan'          => esc_html__('Turkmenistan','wprentals'),
                            'Turks and Caicos Islands'  => esc_html__('Turks and Caicos Islands','wprentals'),
                            'Tuvalu'                => esc_html__('Tuvalu','wprentals'),
                            'Uganda'                => esc_html__('Uganda','wprentals'),
                            'Ukraine'               => esc_html__('Ukraine','wprentals'),
                            'United Arab Emirates'  => esc_html__('United Arab Emirates','wprentals'),
                            'United Kingdom'        => esc_html__('United Kingdom','wprentals'),
                            'United States'         => esc_html__('United States','wprentals'),
                            'United States Minor Outlying Islands'  => esc_html__('United States Minor Outlying Islands','wprentals'),
                            'Uruguay'               => esc_html__('Uruguay','wprentals'),
                            'Uzbekistan'            => esc_html__('Uzbekistan','wprentals'),
                            'Vanuatu'               => esc_html__('Vanuatu','wprentals'),
                            'Venezuela'             => esc_html__('Venezuela','wprentals'),
                            'Vietnam'               => esc_html__('Vietnam','wprentals'),
                            'Virgin Islands (British)'=> esc_html__('Virgin Islands (British)','wprentals'),
                            'Virgin Islands (U.S.)' => esc_html__('Virgin Islands (U.S.)','wprentals'),
                            'Wallis and Futuna Islands' => esc_html__('Wallis and Futuna Islands','wprentals'),
                            'Western Sahara'        => esc_html__('Western Sahara','wprentals'),
                            'Yemen'                 => esc_html__('Yemen','wprentals'),
                            'Yugoslavia'            => esc_html__('Yugoslavia','wprentals'),
                            'Zambia'                => esc_html__('Zambia','wprentals'),
                            'Zimbabwe'              => esc_html__('Zimbabwe','wprentals')
        );
    if($selected!=''){
        $countries= array_change_key_case($countries, CASE_LOWER);
        if ( isset( $countries[$selected]) ) {
            return $countries[$selected];
        }
    }else{
        return $countries;
    }
}
endif;

if( !function_exists('wpestate_show_custom_field')):
    function wpestate_show_custom_field( $show,$slug,$name,$label,$type,$order,$dropdown_values,$post_id,$value=''){
    
        // get value
        if($value ==''){
            $value          =   esc_html(get_post_meta($post_id, $slug, true));
            if( $type == 'numeric'  ){
                
                $value          =   (get_post_meta($post_id, $slug, true));
                if($value!==''){
                   $value =  floatval ($value);
                }
                
                
            }else{
                $value          =   esc_html(get_post_meta($post_id, $slug, true));
            }
      
        }
        
        
        $template='';
        if ( $type =='long text' ){
            $template.= '<label for="'.$slug.'">'.$label.' '.__('(*text)','wprentals').' </label>';
            $template.= '<textarea type="text" class="form-control" id="'.$slug.'"  size="0" name="'.$slug.'" rows="3" cols="42">' .$value. '</textarea>'; 
        }else if( $type =='short text' ){
            $template.=  '<label for="'.$slug.'">'.$label.' '.__('(*text)','wprentals').' </label>';
            $template.=  '<input type="text" class="form-control" id="'.$slug.'" size="40" name="'.$slug.'" value="' . $value . '">';
        }else if( $type =='numeric'  ){
            $template.=  '<label for="'.$slug.'">'.$label.' '.__('(*numeric)','wprentals').' </label>';
            $template.=  '<input type="text" class="form-control" id="'.$slug.'" size="40" name="'.$slug.'" value="' . $value . '">';
        }else if( $type =='date' ){
            $template.=  '<label for="'.$slug.'">'.$label.' '.__('(*date)','wprentals').' </label>';
            $template.=  '<input type="text" class="form-control" id="'.$slug.'" size="40" name="'.$slug.'" value="' .$value . '">';
            $template.= wpestate_date_picker_translation_return($slug);
        }else if( $type =='dropdown' ){
            $dropdown_values_array=explode(',',$dropdown_values);
           
            $template.= '<label for="'.$slug.'">'.$label.' </label>';
            $template.= '<select id="'.$slug.'"  name="'.$slug.'" >';
            $template.= '<option value="">'.esc_html__('Not Available','wprentals').'</option>';
            foreach($dropdown_values_array as $key=>$value_drop){
                $value_drop= stripslashes($value_drop);
              
                $template.= '<option value="'.trim($value_drop).'"';
                if( trim( html_entity_decode($value,ENT_QUOTES) ) == trim( html_entity_decode ($value_drop,ENT_QUOTES) ) ){
        
                    $template.=' selected ';
                }
                if (function_exists('icl_translate') ){
                    $value_drop = apply_filters('wpml_translate_single_string', $value_drop,'custom field value','custom_field_value'.$value_drop );
                }
                
                
                $template.= '>'.trim($value_drop).'</option>';
            }
            $template.= '</select>';
        }
        
        if($show==1){
            print $template;
        }else{
            return $template;
        }
        
    }
endif;
?>