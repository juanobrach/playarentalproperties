<?php

//add_action( 'wp_ajax_nopriv_wpestate_custom_ondemand_pin_load', 'wpestate_custom_ondemand_pin_load' );  
//add_action( 'wp_ajax_wpestate_custom_ondemand_pin_load', 'wpestate_custom_ondemand_pin_load' );
//
//if( !function_exists('wpestate_custom_ondemand_pin_load') ):
//    
//    function wpestate_custom_ondemand_pin_load(){
//        wp_suspend_cache_addition(false);
//        global $keyword;
//        $currency               =   esc_html( get_option('wp_estate_currency_label_main', '') );
//        $where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
//        $counter                =   0;
//        $paged                  =   intval($_POST['newpage']);
//        
//        ob_start(); 
//        //$compute            =   wprentals_search_results_total($_REQUEST,'ajax');
//        
//        $compute = wpestate_argumets_builder($_REQUEST);
//
//
//        $prop_selection     =   $compute[0];
//        $args               =   $compute[1];
//        $compare_submit     =   wpestate_get_template_link('compare_listings.php');
//        $markers            =   array();
//        $return_string      =   '';
//        
//
//            print '<span id="scrollhere"></span>';
//            $listing_unit_style_half    =   get_option('wp_estate_listing_unit_style_half','');
//
//     
//            if( $prop_selection->have_posts() ){
//                while ($prop_selection->have_posts()): $prop_selection->the_post(); 
//                    if($listing_unit_style_half == 1){
//                        get_template_part('templates/property_unit_wide');
//                    }else{
//                        get_template_part('templates/property_unit');        
//                    }
//                    $markers[]=wpestate_pin_unit_creation( get_the_ID(),$currency,$where_currency,$counter );
//                endwhile;
//                kriesi_pagination_ajax($prop_selection->max_num_pages, $range =2,$paged,'pagination_ajax_search_home'); 
//            }else{
//                print '<span class="no_results">'. esc_html__( "We didn't find any results","wprentals").'</>';
//            }
//      
//            $templates = ob_get_contents();
//        ob_end_clean(); 
//        
//        $return_string .=   '<div class="half_map_results">'.$prop_selection->found_posts.' '.esc_html__( ' Results found!','wprentals').'</div>';
//        $return_string .=   $templates;
//        
//        echo json_encode(array('added'=>true,'arguments'=>json_encode($args), 'markers'=>json_encode($markers),'response'=>$return_string ));
//        die();
//
//       wp_suspend_cache_addition(false);     
//       die();
//  }
//  
// endif; // end   ajax_filter_listings 
// 
//
// 
// 
// 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
// 
//if (!function_exists('wprentals_search_results_total')):
//function wprentals_search_results_total($input,$search='search'){
//    global $keyword;
//    $allowed_html           =   array();
//    $adv_search_what        =   get_option('wp_estate_adv_search_what','');
//    $search_type            =   get_option('wp_estate_adv_search_type','');
//    $adv_search_label       =   get_option('wp_estate_adv_search_label','');
//    $book_from              =   '';
//    $book_to                =   '';
//    
//
//    
//    if($search_type=='newtype' || $search_type=='oldtype' ){
//      print '---------classic -----------------';
//        $args   = wprentals_search_function_global_arg_type1_2($input);
//        print_r($args);
//    }else{  
//        $temp_args=array();
//        if(in_array('Location', $adv_search_what)){
//            $temp_args   = wprentals_search_function_global_arg_type1_2($input);   
//        }
//        
//        print_r($temp_args);
//  
//        $args = wprentals_search_results_custom($_REQUEST,$temp_args,$search);
//       // $args=$temp_args;
//    }
//    
//    
//    
//    
//    
//    
//    
//
//    $paged      =   get_query_var('paged') ? get_query_var('paged') : 1;
//    $prop_no    =   intval ( get_option('wp_estate_prop_no', '') );
//  
//   
//    
//    if( isset($input['check_in'])){
//        $book_from      =  sanitize_text_field( wp_kses ( $input['check_in'],$allowed_html) );
//    }
//    if( isset($input['check_out'])){
//        $book_to        =  sanitize_text_field( wp_kses ( $input['check_out'],$allowed_html) );
//    }
// 
//    if( in_array('check_in', $adv_search_what)  ){
//        $key          =     array_search ('check_in', $adv_search_what);
//        $string       =     wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
//        $slug         =     sanitize_key($string);
//        if(isset( $input[$slug])){
//            $book_from    =     sanitize_text_field( wp_kses ( $input[$slug],$allowed_html) );
//        }
//    }
//    if( in_array('check_out', $adv_search_what)  ){
//        $key          =     array_search ('check_out', $adv_search_what);
//        $string       =     wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
//        $slug         =     sanitize_key($string);
//        if(isset( $input[$slug])){
//            $book_to    =     sanitize_text_field( wp_kses ( $input[$slug],$allowed_html) );
//        }
//    } 
//    
//    
//    
//    
//  //  print '-----------------------------------------';print_r($args);
//    
//    if( get_option('wp_estate_use_geo_location','')=='yes' && isset($input['geo_lat']) && isset($input['geo_long']) && $input['geo_lat']!='' && $input['geo_long']!='' ){
//        print'xxxxxxxxxxxxxxxxxx';
//          $geo_lat  = $input['geo_lat'];
//          $geo_long = $input['geo_long'];
//          $geo_rad  = $input['geo_rad'];
//          $args     = wpestate_geo_search_filter_function($args, $geo_lat, $geo_long, $geo_rad);
//      }
//    print '**********************************************';
//    
//    ////////////////////////////////////////////////////////////////////////////
//    // if we have check in and check out dates we need to double loop
//    ////////////////////////////////////////////////////////////////////////////    
//    if ( $book_from!='' && $book_to!='' ){   
//        $prop_selection =   new WP_Query($args);
// 
//        $num            =   $prop_selection->found_posts;
//        $right_array    =   array();
//        $right_array[]  =   0;
//        while ($prop_selection->have_posts()): $prop_selection->the_post(); 
//            $post_id=get_the_ID();
//            print 'check for dates '.$post_id;
//            if( wpestate_check_booking_valability($book_from,$book_to,$post_id) ){
//                $right_array[]=$post_id;
//            }
//        endwhile;
//    
//        
//        wp_reset_postdata();
//        $args = array(
//            'cache_results'           =>    false,
//            'update_post_meta_cache'  =>    false,
//            'update_post_term_cache'  =>    false,
//            'meta_key'                =>    'prop_featured',
//            'orderby'                 =>    'meta_value',
//            'post_type'               =>    'estate_property',
//            'post_status'             =>    'publish',
//            'paged'                   =>    $paged,
//            'posts_per_page'          =>    $prop_no,
//            'post__in'                =>    $right_array
//        );
//   
//        
//    }
//
//    add_filter( 'posts_orderby', 'wpestate_my_order' );
//        if( isset($input['keyword_search']) ){
//            $keyword= $input['keyword_search'];
//            add_filter( 'posts_where', 'wpestate_title_filter', 10, 2 );
//        }
//        $prop_selection =   new WP_Query($args);
//    remove_filter( 'posts_orderby', 'wpestate_my_order' );
//   
//    if( isset($input['keyword_search']) ){
//        remove_filter( 'posts_where', 'wpestate_title_filter', 10, 2 );
//    }   
//        
//    $return_arguments       =   array();
//    $return_arguments[0]    =   $prop_selection;
//    $return_arguments[1]    =   $args;
//     
//    return $return_arguments;
//    
//    
//}
//endif;
//


//if (!function_exists('wprentals_search_results_custom')):
//function wprentals_search_results_custom($input,$temp_args,$tip=''){
//    print '         in wprentals_search_results_custom                   ';
//    global $included_ids;
//    global $amm_features;
//    $real_custom_fields     =   get_option( 'wp_estate_custom_fields', true); 
//    $adv_search_what        =   get_option('wp_estate_adv_search_what','');
//    $adv_search_how         =   get_option('wp_estate_adv_search_how','');
//    $adv_search_label       =   get_option('wp_estate_adv_search_label','');                    
//    $adv_search_type        =   get_option('wp_estate_adv_search_type','');
//    $keyword                =   '';
//    $area_array             =   ''; 
//    $city_array             =   '';  
//    $action_array           =   '';
//    $categ_array            =   '';
//    $meta_query             =   '';
//    $included_ids           =   array();
//    $id_array               =   '';
//    $countystate_array      =   '';
//    $allowed_html           =   array();
//    $new_key                =   0;
//    $features               =   array(); 
//    
//   // $map_corners    =   wpestate_add_geo_map_coordinates($input);
//     
//    if($adv_search_type=='type4' ){
//        
//        $adv_search_what[]='categories';
//        $adv_search_how[]='like';
//        $adv_search_label[]='';
//        
//        $adv_search_what[]='types';
//        $adv_search_how[]='like';
//        $adv_search_label[]='';
//    }
//   
//
//    
//    foreach($adv_search_what as $key=>$term ){
//        $new_key        =   $key+1;  
//        $new_key        =   'val'.$new_key; 
//        
//        
//        if($term === 'none' || $term === 'keyword' || $term === 'property id' || $term==='check_in' || $term==='check_out'){
//            // do nothng
//        }else if( $term === 'categories' ) {
//            
//                
//                if( $tip === 'ajax' ){
//                    $input_name         =   'category_values';
//                    $input_value        =    wp_kses($input['category_values'],$allowed_html);
//                }else{
//                    $input_name         =   'filter_search_type';
//                    if(isset($input['filter_search_type'][0])){
//                        $input_value        =  wp_kses( $input['filter_search_type'][0],$allowed_html);
//                    }
//                }
//
//         
//                if ( (isset($input[$input_name])  )  && strtolower ($input_value)!='all' && $input_value!='' ){
//                    $taxcateg_include   =   array();
//                    $taxcateg_include[] =   wp_kses($input_value,$allowed_html);
//  
//                    $categ_array=array(
//                        'taxonomy'  => 'property_category',
//                        'field'     => 'slug',
//                        'terms'     => $taxcateg_include
//                    );
//                } 
//        } 
//       
//        else if($term === 'types'){ 
//                if( $tip === 'ajax' ){
//                    $input_name         =   'action_values';
//                    $input_value        =   wp_kses($input['action_values'],$allowed_html);
//                }else{
//                    $input_name         =   'filter_search_action';
//                    if(isset($input['filter_search_action'][0])){
//                        $input_value        =   wp_kses( $input['filter_search_action'][0],$allowed_html);
//                    }
//                }
//         
//                
//                if ( isset($input[$input_name])     && strtolower ($input_value)!='all' && $input_value!='' ){
//                    $taxaction_include   =   array();   
//
//                    $taxaction_include[] = wp_kses($input_value,$allowed_html);
//                    $action_array=array(
//                        'taxonomy'  => 'property_action_category',
//                        'field'     => 'slug',
//                        'terms'     => $taxaction_include
//                    );
//                }
//        }
//
//        else if($term === 'cities' ){
//           
//                if( $tip === 'ajax' ){
//                    $input_name         =    'advanced_city';
//                    $input_value        =    wp_kses($input['advanced_city'],$allowed_html);
//                }else{
//                    $input_name         =   'advanced_city';
//                    $input_value        =   '';
//                    if(isset( $input['advanced_city'])){
//                        $input_value        =    wp_kses( $input['advanced_city'],$allowed_html);
//                    }
//                }
//                
//            
//                if ( (isset($input[$input_name]) )   && strtolower ($input_value)!='all' && $input_value!='' ){
//                    $taxcity   =   array();   
//                    $taxcity[] = wp_kses($input_value,$allowed_html);
//                    $city_array = array(
//                        'taxonomy'  => 'property_city',
//                        'field'     => 'slug',
//                        'terms'     => $taxcity
//                    );
//                }
//            
//        }
//
//        else if($term === 'areas'  ){
//          
//                if( $tip === 'ajax' ){
//                    $input_name         =   'advanced_area';
//                    $input_value        =    wp_kses($input['advanced_area'],$allowed_html);
//                }else{
//                    $input_name         =   'advanced_area';
//                    $input_value        =   '';  
//                    if(isset($input['advanced_area'])){
//                        $input_value        =   wp_kses( $input['advanced_area'],$allowed_html);
//                    }
//                }
//                
//                if ( (isset($input[$input_name]) )    && strtolower ($input_value)!='all' && $input_value!='' ){
//                    $taxarea   =   array();   
//                    $taxarea[] = wp_kses($input_value,$allowed_html);
//                    $area_array = array(
//                        'taxonomy'  => 'property_area',
//                        'field'     => 'slug',
//                        'terms'     => $taxarea
//                    );
//                }
//            
//        }
//        
//        
//        else{ 
//          
//            $term         =   str_replace(' ', '_', $term);
//            $slug         =   wpestate_limit45(sanitize_title( $term )); 
//            $slug         =   sanitize_key($slug);                    
//            $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );   
//           
//            $slug_name    =   sanitize_key($string);
//            
//            $compare_array      =   array();
//            $show_slider_price ='yes';
//            
//          
//            if ( $adv_search_what[$key] === 'property country'){
//                
//                if( $tip === 'ajax' ){
//                    $term_value=  wp_kses($input['country'],$allowed_html);
//                }else{
//                    if(isset($input['advanced_country'])){
//                        $term_value=  esc_html( wp_kses( $input['advanced_country'], $allowed_html) );
//                    }
//                }
//                
//                if( $term_value!='' && $term_value!='all' && $term_value!='all' &&  $term_value != $adv_search_label[$key]){
//                    $compare_array['key']        = 'property_country';
//                    $compare_array['value']      =  wp_kses($term_value,$allowed_html);
//                    $compare_array['type']       = 'CHAR';
//                    $compare_array['compare']    = 'LIKE';
//                    //$meta_query[]                = $compare_array;
//                    $included_ids[] = $compare_array;
//                }
//                
//                
//                
//            }else if ( $adv_search_what[$key] === 'property price' && $show_slider_price ==='yes'){
//                
//                $compare_array['key']        = 'property_price';
//                
//                if( $tip === 'ajax' ){                   
//                    $price_low  = floatval($input['price_low']);
//                    $price_max  = floatval($input['price_max']);
//                }else{
//                    if( isset($input['term_id']) && isset($input['term_id'])!=''){
//                        $term_id    = intval($input['term_id']);
//                        $price_low  = floatval( $input['price_low_'.$term_id] );
//                        $price_max  = floatval( $input['price_max_'.$term_id] );
//              
//                    }else{
//                        $price_low  = floatval( $input['price_low'] );
//                        $price_max  = floatval( $input['price_max'] );
//              
//                    }
//                    
//                }
//
//                $custom_fields = get_option( 'wp_estate_multi_curr', true);
//                if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
//                    $i=intval($_COOKIE['my_custom_curr_pos']);
//                    $price_max       =   $price_max / $custom_fields[$i][2];
//                    $price_low       =   $price_low / $custom_fields[$i][2];
//                }
//                
//                $compare_array['key']        = 'property_price';
//                $compare_array['value']      = array($price_low, $price_max);
//                $compare_array['type']       = 'numeric';
//                $compare_array['compare']    = 'BETWEEN';
//                $included_ids[]= $compare_array;
//                //$meta_query[]                = $compare_array;
//                
//            }else{
//                if( $tip === 'ajax' ){
//                    $term_value= wp_kses($input['val_holder'][$key],$allowed_html);
//                }else{
//                    $term_value='';
//                 
//                    if($adv_search_what[$key]=='guest_no'){
//                        $slug_name='guest_no';
//                    }
//                    if(isset($input[$slug_name])){
//                        $term_value =  (esc_html( wp_kses($input[$slug_name], $allowed_html) ));
//                    }
//                }
//              
//                
//                // rest of things
//                
//              
//                if( $adv_search_label[$key] != $term_value && $term_value != '' && strtolower($term_value) != 'all'){ // if diffrent than the default values
//                    $compare        =   '';
//                    $search_type    =   ''; 
//                    $allowed_html   =   array();
//                    $compare        =   $adv_search_how[$key];
//
//                    if($compare === 'equal'){
//                       $compare         =   '='; 
//                       $search_type     =   'numeric';
//                       $term_value      =   floatval ($term_value );
//
//                    }else if($compare === 'greater'){
//                        $compare        = '>='; 
//                        $search_type    = 'numeric';
//                        $term_value     =  floatval ( $term_value );
//
//                    }else if($compare === 'smaller'){
//                        $compare        ='<='; 
//                        $search_type    ='numeric';
//                        $term_value     = floatval ( $term_value );
//
//                    }else if($compare === 'like'){
//                        $compare        = 'LIKE'; 
//                        $search_type    = 'CHAR';
//                        $term_value     = (wp_kses( $term_value ,$allowed_html));
//                     //   $term_value     = str_replace(' ','%',$term_value);
//                        
//                    }else if($compare === 'date bigger'){
//                        $compare        ='>=';  
//                        $search_type    ='DATE';
//                        $term_value     =  str_replace(' ', '-', $term_value);
//                        $term_value     = wp_kses( $term_value,$allowed_html );
//
//                    }else if($compare === 'date smaller'){
//                        $compare        = '<='; 
//                        $search_type    = 'DATE';
//                        $term_value     =  str_replace(' ', '-', $term_value);
//                        $term_value     = wp_kses( $term_value,$allowed_html );
//                    }
//
//                    $compare_array['key']        = $slug;
//                    $compare_array['value']      = $term_value;
//                    $compare_array['type']       = $search_type;
//                    $compare_array['compare']    = $compare;
//                    $included_ids[]              = $compare_array;
//                   
//                }// end if diffrent
//            } 
//        }////////////////// end last else
//    } ///////////////////////////////////////////// end for each adv search term
//    
//  
//    
//    if($tip === 'search'){
//        $features = wpestate_add_feature_to_search($input);
//    }
//    
//    if($tip === 'ajax'){
//        $features = wpestate_add_feature_to_search_ajax($input);
//    }
//
//    
//    
//    if($tip === 'search'){
//        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
//    }
//    
//    if($tip === 'ajax'){
//        $paged      =   intval($input['newpage']);
//        $prop_no    =   intval( get_option('wp_estate_prop_no', '') );
//    }
//    
//    
//    
//    // if we have meta query from location
//    if ( isset( $temp_args['meta_query'] ) ){
//        foreach ($temp_args['meta_query'] as $key=>$meta ){
//            if(!in_array($meta, $included_ids)){
//                $included_ids[]  = $meta;
//            }
//        }
//    }
//    
//   
//    $tax_query=array('relation' => 'AND');
//    if(!empty($categ_array)){
//       $tax_query[]= $categ_array;
//    }
//    if(!empty($action_array)){
//       $tax_query[]= $action_array;
//    }
//    if(!empty($city_array)){
//       $tax_query[]= $city_array;
//    }
//    if(!empty($area_array)){
//       $tax_query[]= $area_array;
//    }
//    
//    
//    $args = array(
//        'cache_results'             =>  false,
//        'update_post_meta_cache'    =>  false,
//        'update_post_term_cache'    =>  false,
//        
//        'post_type'       => 'estate_property',
//        'post_status'     => 'publish',
//        'paged'           => $paged,
//        'posts_per_page'  => 30,
//        'meta_key'        => 'prop_featured',
//        'orderby'         => 'meta_value',
//        'order'           => 'DESC',
//        'meta_query'      => $meta_query,
//        'tax_query'       => $tax_query
//    );  
//    
//
// 
//    
//     
//    //if we have check in and check out 
//    if( in_array('check_in', $adv_search_what)  || in_array('check_out', $adv_search_what) ){
//        unset($args['paged']);
//        $args['posts_per_page']=-1;
//    }
// 
//    //if we have tax query from location
//    if ( isset( $temp_args['tax_query'] ) ){
//        // if is google location
//        if( isset($temp_args['tax_query']['relation']) && $temp_args['tax_query']['relation']=='AND'){
//            foreach ($temp_args['tax_query'] as $key=>$tax ){
//                if(!in_array($tax, $args['tax_query'])){
//                    $args['tax_query'][]  = $tax;
//                }
//            } 
//        }
//        
//        if( isset($temp_args['tax_query']['relation']) && $temp_args['tax_query']['relation']=='OR'){
//            $args['tax_query']=$temp_args['tax_query'];
//        }
//    }
//    
//    
//    $meta_ids=array();
//
//    
//   
//    if( $map_corners['has_corners']==1 ){
//        $included_ids[] =   $map_corners['lat'];
//        $included_ids[] =   $map_corners['long'];
//        
//    }
//        
//        
//        
////    print '************';
////    print_r($included_ids);
////    print '************';
//    
//    if(!empty($included_ids)){
//        $meta_ids = wpestate_add_meta_post_to_search($included_ids);
//    }
//     
//    
//    if(!empty($features) && !empty($meta_ids) ){
//        $features= array_intersect ($features,$meta_ids);
//        if( empty($features) ){
//            $features[]=0;
//        }
//        
//    }else{
//        if( empty($features) ){
//            $features=$meta_ids;
//        }
//    }
//    
// 
//    if(!empty($features)){
//        $args['post__in']=$features;
//    }
//
//    
////    if($adv_search_type=='type4' && $tip === 'ajax'){
////        $args    =   wpestated_advanced_search_tip11_ajax($args,$input['keyword_search'],$input['filter_search_action11'],$input['filter_search_categ11']);
////    }
////    
//   
//    
//
//return $args;
//    
//    
//    
//}
//endif;
//

















if(!function_exists('wpestate_add_feature_to_search')):
function wpestate_add_feature_to_search($input,$is_half=''){
    global $table_prefix;
    global $wpdb;
   
    $searched           =   0;
    

    if($is_half===1 && isset( $input['all_checkers'])){
        // is half map ajax
        $all_checkers=explode(",", $input['all_checkers'] );
        
        
        foreach($all_checkers as $checker => $value){
            if($value!=''){
                $searched       =   1;
            }
            $value          =   sanitize_text_field($value);
            $post_var_name  =   str_replace(' ','_', trim($value) );
            $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
            $input_name     =   sanitize_key($input_name);
            if(trim($input_name)!=''){
                $potential_ids[$checker]=
                    wpestate_get_ids_by_query(
                        $wpdb->prepare("
                            SELECT post_id
                            FROM ".$table_prefix."postmeta
                            WHERE meta_key = %s
                            AND CAST(meta_value AS UNSIGNED) = '1'
                        ",$input_name)
                    );
            }

        }
    }else{
        $feature_list_array =   array();
        $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
        $feature_list_array =   explode( ',',$feature_list);
 
        
        foreach($feature_list_array as $checker => $value){
            $value          =   sanitize_text_field($value);
            $post_var_name  =   str_replace(' ','_', trim($value) );
            $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
            $input_name     =   sanitize_key($input_name);

            if ( isset( $input[$input_name] ) && $input[$input_name]==1 ){
                $searched=1;
                $potential_ids[$checker]=
                    wpestate_get_ids_by_query(
                        $wpdb->prepare("
                            SELECT post_id
                            FROM ".$table_prefix."postmeta
                            WHERE meta_key = %s
                            AND CAST(meta_value AS UNSIGNED) = '1'
                        ",$input_name)
                    );
            }
        }
    }
    
    $ids=[];
    if(!empty($potential_ids)){
        foreach($potential_ids as $key=>$temp_ids){
            if(count($ids)==0){
                $ids=$temp_ids;
            }else{
                $ids=array_intersect($ids,$temp_ids);
            }
        }
    }
    
    if(empty($ids) && $searched==1 ){
        $ids[]=0;
    }
   
    
    return $ids;
    
    
}
endif;











if(!function_exists('get_ids_by_query')):
function wpestate_get_ids_by_query($query){
    global $wpdb;
    //print ' ----------</br>'.$query.'</br>';
    $data=$wpdb->get_results( $query,'ARRAY_A');
  
    $results=[];
  
    foreach($data as $entry){
        $results[]=$entry['post_id'];
    }
  
   //print_r($results);
    return $results;
}
endif;









if( !function_exists('wpestate_title_filter') ):
function wpestate_title_filter( $where, $wp_query ){
    global $wpdb;
    global $keyword;
  //  $keyword= str_replace("'", " ", $keyword);
    $search_term = $wpdb->esc_like($keyword);
    $search_term = ' \'%' . $search_term . '%\'';
    $where .= ' AND ' . $wpdb->posts . '.post_title LIKE '.$search_term;
 
    
    return $where;
}

endif;



function wpestate_search_type_inject($categ_select_list,$action_select_list,$where=''){
    $allowed_html=array();
    $col_class='col-md-2';
    if($where=="half"){
        $col_class='col-md-3';
    }
  
    print'<div class="col-md-6">
                <i class="custom_icon_class_icon fas fa-keyboard"></i>

                <input type="text" id="keyword_search" class="form-control custom_icon_class_input" name="keyword_search"  placeholder="'. esc_html__('Type Keyword','wprentals').'" value="'; 

                if(isset($_GET['keyword_search'])){
                    print  esc_attr(stripslashes( wp_kses($_GET['keyword_search'], $allowed_html) ) );
                }
                print '"></div>';
       
       
                if( isset($_GET['property_category']) && $_GET['property_category']!=''&& $_GET['property_category']!='all'  ){
                    $full_name = get_term_by('slug', esc_html( wp_kses( $_GET['property_category'],$allowed_html) ),'property_category');
                    $adv_categ_value= $adv_categ_value1=$full_name->name;
                    $adv_categ_value1 = mb_strtolower ( str_replace(' ', '-', $adv_categ_value1));
                }else{
                    $adv_categ_value    =  wpestate_category_labels_dropdowns('main');
                    $adv_categ_value1   ='all';
                }
        
                print '
                <div class="'.$col_class.'">
                   <i class="custom_icon_class_icon fas fa-clone"></i>
                    <div class="dropdown form-control custom_icon_class icon_categlist " >
                        <div data-toggle="dropdown" id="adv_categ" class="filter_menu_trigger     "  data-value="'.strtolower ( rawurlencode( $adv_categ_value1)).'"> 
                            '.$adv_categ_value.'               
                        <span class="caret caret_filter"></span> </div>           
                        <input type="hidden" id="property_category" name="property_category" value="';
                        if(isset($_GET['property_category'])){
                            echo strtolower ( esc_attr( $_GET['property_category'] ) );
                        }
                       echo'">
                        <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                            '.$categ_select_list.'
                        </ul>
                    </div>    
                </div>';
       
                if(isset($_GET['property_action_category']) && $_GET['property_action_category']!='' && $_GET['property_action_category']!='all'){
                    $full_name = get_term_by('slug', esc_html( wp_kses( $_GET['property_action_category'],$allowed_html) ),'property_action_category');
                    $adv_actions_value=$adv_actions_value1= $full_name->name;
                    $adv_actions_value1 = mb_strtolower ( str_replace(' ', '-', $adv_actions_value1) );
                }else{
                    $adv_actions_value= wpestate_category_labels_dropdowns('second');
                    $adv_actions_value1='all';
                }

                print'
                <div class="'.$col_class.'">  
                    <i class="custom_icon_class_icon fas fa-boxes"></i>
                    <div class="dropdown form-control dropdown custom_icon_class icon_actionslist form-control " >
                        <div data-toggle="dropdown" id="adv_actions" class="filter_menu_trigger  " data-value="'.strtolower ( rawurlencode ( $adv_actions_value1) ).'"> 
                            '.$adv_actions_value.' 
                        <span class="caret caret_filter"></span> </div>           
                        <input type="hidden" id="property_action_category" name="property_action_category" value="'; 
                        if(isset($_GET['property_action_category'])){
                             echo  strtolower( esc_attr($_GET['property_action_category']) );

                        }; 
                        echo '">
                        <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                            '.$action_select_list.'
                        </ul>        
                    </div>
                </div>';
     
}


function wpestate_add_geo_map_coordinates($input){
  
    $long_array                 =  array();
    $lat_array                  =  array();
    $return_array               =  array();
    $return_array['long']       =   '';
    $return_array['lat']        =   '';
    $return_array['has_corners']=   0;
    
    if( !isset($input['ne_lat']) || !isset( $input['ne_lng'] )|| !isset( $input['sw_lat']) || !isset( $input['sw_lng'] ) ){
        return $return_array;
    }
    
    $ne_lat                     =  floatval($input['ne_lat']);
    $ne_lng                     =  floatval($input['ne_lng']);
    $sw_lat                     =  floatval($input['sw_lat']);
    $sw_lng                     =  floatval($input['sw_lng']);
    
    
    $min_lat    =  $sw_lat;
    $max_lat    =  $ne_lat;
        
    if($min_lat>$max_lat){
        $min_lat    =  $ne_lat;
        $max_lat    =  $sw_lat ;
    }

       
    $min_lng    =   $sw_lng;
    $max_lng    =   $ne_lng;

    if($min_lng>$max_lng){
        $min_lng = $ne_lng;
        $max_lng = $sw_lng;
    } 
        

    $long_array['key']       = 'property_longitude';
    $long_array['value']     =  array( $min_lng,$max_lng);
    $long_array['type']      = 'DECIMAL';
    $long_array['compare']   = 'BETWEEN';
    $return_array['long']    =  $long_array;

       
    $lat_array['key']       = 'property_latitude';
    $lat_array['value']     =  array( $min_lat,$max_lat);
    $lat_array['type']      = 'DECIMAL';
    $lat_array['compare']   = 'BETWEEN';
    $return_array['lat']    =  $lat_array;
    
    $return_array['has_corners']=1;
    return $return_array;
        
}


if(!function_exists('wpestate_geo_search_filter_function')):
function wpestate_geo_search_filter_function($args,$center_lat,$center_long,$radius){
    global $wpdb;
    $radius_measure = get_option('wp_estate_geo_radius_measure','');
    $earth         = 3959;
    if( $radius_measure == 'km' ) {
       $earth = 6371;
    }
  

    $wpdb_query = $wpdb->prepare( "SELECT $wpdb->posts.ID,
            ( %s * acos(
                    cos( radians(%s) ) *
                    cos( radians( latitude.meta_value ) ) *
                    cos( radians( longitude.meta_value ) - radians(%s) ) +
                    sin( radians(%s) ) *
                    sin( radians( latitude.meta_value ) )
            ) )
            AS distance, latitude.meta_value AS latitude, longitude.meta_value AS longitude
            FROM $wpdb->posts
            INNER JOIN $wpdb->postmeta
                    AS latitude
                    ON $wpdb->posts.ID = latitude.post_id
            INNER JOIN $wpdb->postmeta
                    AS longitude
                    ON $wpdb->posts.ID = longitude.post_id
            WHERE 1=1

                    AND latitude.meta_key='property_latitude'
                    AND longitude.meta_key='property_longitude'
            HAVING distance < %s
            ORDER BY $wpdb->posts.menu_order ASC, distance ASC",
            $earth,
            $center_lat,
            $center_long,
            $center_lat,
            $radius
        );
        $listing_ids = $wpdb->get_results( $wpdb_query, OBJECT_K );
      
   
        if ( $listing_ids=='') {
            $listing_ids = array();
        }
        // return post ids for main wp_query
        
        $new_ids        =   array_keys(  $listing_ids );
        
        
        if(isset($args[ 'post__in' ])){
            $original_ids   =   $args[ 'post__in' ];
        }else{
            $original_ids   =   array();
        }

        if ( !empty($new_ids) ){
         
            if( empty(  $args[ 'post__in' ] ) ){ 
                $args[ 'post__in' ] = $new_ids;
            }else if( isset ($args[ 'post__in'][0]) && $args[ 'post__in' ][0]==0 ){// no items on coustom
                $args[ 'post__in' ]=array(0); 
            }else{
           
                $intersect   =   array_intersect ( $new_ids , $original_ids );
                if( empty($intersect) ){ 
                    $intersect=array(0);
                }
                    
                $args[ 'post__in' ] =$intersect;
         
 
            }
        }else{
            $args[ 'post__in' ]=array(0);
        }
        return $args;
    
}
endif;





if(!function_exists('wprentals_location_custom_dropwdown')):
    function wprentals_location_custom_dropwdown($search_location,$label){
        $return_string = '
        <div class="dropdown form-control">
            <div data-toggle="dropdown" id="search_location"  class="filter_menu_trigger "  data-value="'; 
                if(isset($search_location['search_location'])){
                    $return_string.= esc_attr($search_location['search_location']);
                }else{
                    $return_string.= 'all';
                }
            $return_string.='">';
                
            if(isset($_GET['search_location']) && $_GET['search_location']!=''&& $_GET['search_location']!='0' ){
                $return_string.= esc_attr($search_location['search_location']);
            }else{
                $return_string.= $label;
            }
                    
            $return_string.= '<span class="caret caret_filter"></span> </div>           
            <input type="hidden" name="search_location" id="search_location_autointernal"  value="'; 
                if(isset($search_location['search_location'])){
                    $return_string.= esc_attr($search_location['search_location']);
                }   
                $wpestate_internal_search='';
            $return_string.='">
            <ul  class="dropdown-menu filter_menu search_location_autointernal_list"  id="search_location-select" role="menu" aria-labelledby="search_location'.$wpestate_internal_search.'">
                '. wprentals_places_search_select().'
            </ul>        
        </div>';
                
        return $return_string;
    }
endif;













if(!function_exists('wprentals_places_search_select')):
    function wprentals_places_search_select($with_any='',$selected=''){

        $availableTags_array    =   get_option('wpestate_autocomplete_data_select',true);
      
        
        sort($availableTags_array);
        $select_area_list       =   '';
        if($with_any==''){
            $select_area_list.='<li role="presentation" data-value="0"';
            if($selected=='0' || $selected==0){
                $select_area_list .=' selected="selected" ';
            }
            $select_area_list.='>'.esc_html__( 'any','wprentals').$selected.'</li>';
        }

        if(is_array($availableTags_array)){
            foreach($availableTags_array as $key=>$item){
                
                if( $item['label']!='' && $item['label']!='0' ){
                    $select_area_list .=   '<li role="presentation" data-tax="'. $item['category'].'" data-value="'.  $item['label'].'"';
                    if($selected!='' && $selected==$item['label']){
                        $select_area_list .=' selected="selected" ';
                    }
                    $select_area_list .= '>'. $item['label'].'</li>';
                }
            }
        }

        return $select_area_list;
    }
endif;