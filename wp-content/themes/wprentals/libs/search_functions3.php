<?php
function wpestate_argumets_builder($input,$is_half=''){
    global $query_meta;
    $query_meta         =   0;
    $adv_search_what    =   get_option('wp_estate_adv_search_what','');
    $adv_search_how     =   get_option('wp_estate_adv_search_how','');
    $adv_search_label   =   get_option('wp_estate_adv_search_label','');
    $adv_search_icon    =   get_option('wp_estate_search_field_label','');
    $adv_search_type    =   get_option('wp_estate_adv_search_type','');
     
    if( $adv_search_type=='newtype' || $adv_search_type=='oldtype'){
        if($is_half==1){ //$is_half means has price for type 1 and 2
    
            $adv_search_what   =   get_option('wp_estate_adv_search_what_half',true);
            $adv_search_how    =   get_option('wp_estate_adv_search_how_half',true);
       }else{
            $adv_search_what    =   get_option('wp_estate_adv_search_what_classic',true);
            $adv_search_how     =   get_option('wp_estate_adv_search_how_classic',true);
        }
        
    } else if($adv_search_type=='type4' ){
        
        $adv_search_what[]='property_category';
        $adv_search_how[]='like';
        $adv_search_label[]='';
        
        $adv_search_what[]='property_action_category';
        $adv_search_how[]='like';
        $adv_search_label[]='';
    }
    $move_map=0;
    if ( isset($input['move_map']) ){
        $move_map=intval($input['move_map']);
    }
    
  
    //////////////////////////////////////////begin
    $tax_array  =   array();
    $meta_array =   array();
   
    foreach($adv_search_what as $key=>$term ){
        $term   =   sanitize_key($term);
        
        if( rentals_is_tax_case($term) ){
            $tax_element    = wpestate_add_tax_element($term,$adv_search_how[$key],$input);
            if(!empty($tax_element)){
               $tax_array[] = $tax_element;
            }
        }else{
            // is_meta_case
            $meta_element = wpestate_add_meta_element($term,$adv_search_how[$key],$input);
            if(!empty($meta_element)){
               $meta_array[] = $meta_element;
            }
        }
        
        if( strtolower($term)=='location'){
            $location_array =   wpestate_apply_location($tax_array,$meta_array,$input);
            $tax_array      =   $location_array['tax_already_made'];
            $meta_array     =   $location_array['meta_already_made'];
        }
        
        
    }
    $paged  =   1;
    $paged  =   get_query_var('paged') ? get_query_var('paged') : 1;
   
    if( isset($_REQUEST['newpage']) ){
        $paged  = intval($_REQUEST['newpage']);
    }

    
 
    
    $prop_no    =   intval ( get_option('wp_estate_prop_no', '') );
    $book_from  =   '';
    $book_to    =   '';
    if( isset($input['check_in'])){
        $book_from      =  sanitize_text_field( $input['check_in']);
    }
    
    if( isset($input['check_out'])){
        $book_to        =  sanitize_text_field( $input['check_out'] );
    }
    

    
    $args = array(
        'cache_results'             =>  false,
        'update_post_meta_cache'    =>  false,
        'update_post_term_cache'    =>  false,
        
        'post_type'       => 'estate_property',
        'post_status'     => 'publish',
        'paged'           => $paged,
        'posts_per_page'  => $prop_no,
        'meta_key'        => 'listing_name',
        'orderby'         => 'meta_value',
        'order'           => 'ASC',
        'meta_query'      => $meta_array,
        'tax_query'       => $tax_array
    );  
    
         
   
   
   
    if( $move_map==1 ){
        $args['meta_query']   =$meta_array  =   wpestate_map_pan_filtering($input,$meta_array);
    }


    $features = array();
    $features = wpestate_add_feature_to_search($input,$is_half);
    
    
    
    $meta_ids=array();
    if(!empty($args['meta_query']) ){
        $meta_results           =   wpestate_add_meta_post_to_search($meta_array);
        $meta_ids               =   $meta_results[0];
        $args['meta_query']     =   $meta_results[1];
    }
    
    if(!empty($features) && !empty($meta_ids) ){
        $features= array_intersect ($features,$meta_ids);
        if( empty($features) ){
            $features[]=0;
        }
        
    }else{
     
        if( empty($features) ){
            $features=$meta_ids;
              
        }
    }
    

    if(!empty($features)){
        $args['post__in']=$features;
    }
   
    
    
    
    
    if( $move_map != 1 ){
        if( get_option('wp_estate_use_geo_location','')=='yes' && isset($input['geo_lat']) && isset($input['geo_long']) && $input['geo_lat']!='' && $input['geo_long']!='' ){
            
          
            $geo_lat  = $input['geo_lat'];
            $geo_long = $input['geo_long'];
            $geo_rad  = $input['geo_rad'];
            $args     = wpestate_geo_search_filter_function($args, $geo_lat, $geo_long, $geo_rad);
              
        } 
    }

    //check the or in meta situation for location
    if ($query_meta==0 && isset( $args['meta_query'][0]['relation']) && $args['meta_query'][0]['relation']==='OR' && isset($args['post__in']) && $args['post__in'][0]==0 ){
        print 'kKUK_de_mare';
        unset($args['post__in']);
    }
    
    
   
   
   
   
    ////////////////////////////////////////////////////////////////////////////
    // if we have check in and check out dates we need to double loop
    ////////////////////////////////////////////////////////////////////////////    
    if ( $book_from!='' && $book_to!='' ){  
        $args[ 'posts_per_page'] =  -1; 
        $args[ 'order'] =  'ASC'; 
        $prop_selection =   new WP_Query($args);
		error_log('PropSelection:' . json_encode($prop_selection));
        $num            =   $prop_selection->found_posts;
        $right_array    =   array();
        $right_array[]  =   0;
        while ($prop_selection->have_posts()): $prop_selection->the_post(); 
            $post_id=get_the_ID();
          
            if( wpestate_check_booking_valability($book_from,$book_to,$post_id) ){
                $right_array[]=$post_id;
            }
        endwhile;
    
        
        wp_reset_postdata();
        $args = array(
            'cache_results'           =>    false,
            'update_post_meta_cache'  =>    false,
            'update_post_term_cache'  =>    false,
            'meta_key'                =>    'listing_name',
            'orderby'                 =>    'meta_value',
			'order'                   =>    'ASC',
            'post_type'               =>    'estate_property',
            'post_status'             =>    'publish',
            'paged'                   =>    $paged,
            'posts_per_page'          =>    $prop_no,
            'post__in'                =>    $right_array
        );
   
     
    }

    // add filters
    add_filter( 'posts_orderby', 'wpestate_my_order' );
    if( isset($input['keyword_search']) ){
        global $keyword;
        $keyword= stripslashes($input['keyword_search']);
        add_filter( 'posts_where', 'wpestate_title_filter', 10, 2 );
    }
    
    
    $prop_selection =   new WP_Query($args);
    
     
    //remove 
    remove_filter( 'posts_orderby', 'wpestate_my_order' );
   
    if( isset($input['keyword_search']) ){
        remove_filter( 'posts_where', 'wpestate_title_filter', 10, 2 );
    }   
        
    $return_arguments       =   array();
    $return_arguments[0]    =   $prop_selection;
    $return_arguments[1]    =   $args;
     
    return $return_arguments;
    
    
    
    

}






function wpestate_apply_location($tax_array_already_made,$meta_already_made,$input){
    $show_adv_search_general            =   get_option('wp_estate_wpestate_autocomplete','');
    $allowed_html                       =   array();
    $tax_query                          =   array();
    $meta_query                         =   array();
    $city_array                         =   array();
    $area_array                         =   array();
    $categ_array                        =   array();
    $action_array                       =   array();
  
    if($show_adv_search_general=='no'){
        if( esc_html($input['stype'])=='tax' ){
            $stype='tax';
            
            if (isset($input['search_location']) and $input['search_location'] != 'all' && $input['search_location'] != '' && $input['search_location'] != '0') {
                //////////////////////////////////////////////////////////////////////////////////////
                ///// city filters 
                //////////////////////////////////////////////////////////////////////////////////////

                $taxcity[] = sanitize_text_field ($input['search_location']);
                $city_array = array(
                    'taxonomy'     => 'property_city',
                    'field'        => 'slug',
                    'terms'        => $taxcity
                );
           
                //////////////////////////////////////////////////////////////////////////////////////
                ///// area filters 
                //////////////////////////////////////////////////////////////////////////////////////
                $taxarea[]      = sanitize_text_field($input['search_location'] );
                $area_array     = array(
                    'taxonomy'     => 'property_area',
                    'field'        => 'slug',
                    'terms'        => $taxarea
                );
            }

            $tax_query2 =   array();
            
            if( !empty($city_array) || !empty($area_array) ){
                $tax_query2 = array(
                    'relation' => 'OR',
                    $city_array,
                    $area_array
                );
            }

            
            if( !empty($tax_array_already_made) ){
                
                if(!empty( $tax_query2 )){
                    $tax_array_already_made[]=$tax_query2;
                }
                
            }else{
                            
                $tax_array_already_made =$tax_query2;
            
            }
            
            


        }else{
            $stype                      =   'meta';
            $meta_query_part            =   array();
            $meta_query['relation']     =   'AND';
            if( isset($input['search_location'])  && $input['search_location']!='' && $input['search_location'] != '0' ){
                
                $search_string              =   sanitize_text_field ( $input['search_location'] );
                $search_string              =   str_replace('-', ' ', $search_string);
                
                $meta_query_part['relation'] =   'OR';
                
                $country_array               =   array();
                $country_array['key']        =   'property_country';
                $country_array['value']      =   $search_string;
                $country_array['type']       =   'CHAR';
                $country_array['compare']    =   'LIKE'; 
                
                $meta_query_part[]           =   $country_array;

                $country_array               =   array();
                $country_array['key']        =   'property_county';
                $country_array['value']      =   $search_string;
                $country_array['type']       =   'CHAR';
                $country_array['compare']    =   'LIKE'; 
                
                $meta_query_part[]           =   $country_array;

                $country_array               =   array();
                $country_array['key']        =   'property_state';
                $country_array['value']      =   $search_string;
                $country_array['type']       =   'CHAR';
                $country_array['compare']    =   'LIKE'; 
                $meta_query_part[]           =   $country_array;
              
                $meta_already_made[]         =   $meta_query_part;  
            }
        }
    }else{
        if (isset($input['advanced_city']) and $input['advanced_city'] != 'all' && $input['advanced_city'] != '') {
            $taxcity[] = sanitize_title (    wp_kses($input['advanced_city'],$allowed_html) );
            $city_array = array(
                'taxonomy'     => 'property_city',
                'field'        => 'slug',
                'terms'        => $taxcity
            );
        }

    

        
        if (isset($input['advanced_area']) and $input['advanced_area'] != 'all' && $input['advanced_area'] != '') {
            $taxarea[] = sanitize_title (  wp_kses($input['advanced_area'],$allowed_html) );
            $area_array = array(
                'taxonomy'     => 'property_area',
                'field'        => 'slug',
                'terms'        => $taxarea
            );
        }
        
        if(!empty($city_array)){
            $tax_array_already_made[]=$city_array;
        }

        if(!empty($area_array)){
            $tax_array_already_made[]=$area_array;
        }
    
    
    
        $country_array=array();
        if( isset($input['advanced_country'])  && $input['advanced_country']!='' ){
            $country                     =   sanitize_text_field ( $input['advanced_country'] );
            $country                     =   str_replace('-', ' ', $country);
            $country_array['key']        =   'property_country';
            $country_array['value']      =   $country;
            $country_array['type']       =   'CHAR';
            $country_array['compare']    =   'LIKE'; 
            $meta_already_made[]         =   $country_array;
        }

        if( isset($input['advanced_city']) && $input['advanced_city']=='' && isset($input['property_admin_area']) && $input['property_admin_area']!=''   ){
            $admin_area_array               =   array();
            $admin_area                     =   sanitize_text_field (  $input['property_admin_area'] );
            $admin_area                     =   str_replace(" ", "-", $admin_area);
            $admin_area                     =   str_replace("\'", "", $admin_area);
            $admin_area_array['key']        =   'property_admin_area';
            $admin_area_array['value']      =   $admin_area;
            $admin_area_array['type']       =   'CHAR';
            $admin_area_array['compare']    =   'LIKE'; 
            $meta_already_made[]            =   $admin_area_array;

        }
    }
    
    
    $return_info = array(
                'tax_already_made' =>$tax_array_already_made,
                'meta_already_made'=>$meta_already_made,
    );
    return $return_info;
}













function wpestate_add_meta_element($term,$how,$input){
    $meta_term          =   array();
    $input_value        =   '';
   
    if($term=='property_price'){
        $price_min      =   floatval($input['price_low']);
        $price_max      =   floatval($input['price_max']);
        $custom_fields  =   get_option( 'wp_estate_multi_curr', true);
 
        if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
            $i              =   intval($_COOKIE['my_custom_curr_pos']);
            $price_max      =   $price_max / $custom_fields[$i][2];
            $price_min      =   $price_min / $custom_fields[$i][2];
        }
                
                
        $meta_term['key']        = 'property_price';
        $meta_term['value']      = array($price_min, $price_max);
        $meta_term['type']       = 'numeric';
        $meta_term['compare']    = 'BETWEEN';
        
       
        return $meta_term;
    }
    
    
    if( isset($input[$term]) ){
        $input_value        =   sanitize_text_field($input[$term]);
    }
    $allowed_html       =   array();
    
    if( $input_value==''  || $term=='check_in' || $term=='check_out' ){
        return $meta_term;
    }
    if( ( $how === 'equal' || $how === 'greater' || $how === 'smaller' ) && !is_numeric($input_value)){
        return $meta_term;
    }
    if( $how === 'like'&& $input_value=='all' ){
         return $meta_term;
    }
    
  
    
    if($how === 'equal' ){
        $compare         =   '='; 
        $search_type     =   'numeric';
        $term_value      =   floatval ($input_value );

    }else if($how === 'greater'){
        $compare        = '>='; 
        $search_type    = 'numeric';
        $term_value     =  floatval ( $input_value );

    }else if($how === 'smaller'){
        $compare        ='<='; 
        $search_type    ='numeric';
        $term_value     = floatval ( $input_value );

    }else if($how === 'like'){
        $compare        = 'LIKE'; 
        $search_type    = 'CHAR';
        $term_value     = wp_kses( $input_value ,$allowed_html);
                   
                        
    }else if($how === 'date bigger'){
        $compare        ='>=';  
        $search_type    ='DATE';
        $term_value     =  str_replace(' ', '-', $input_value);
        $term_value     = wp_kses( $input_value,$allowed_html );

    }else if($how === 'date smaller'){
        $compare        = '<='; 
        $search_type    = 'DATE';
        $term_value     =  str_replace(' ', '-', $term_value);
        $term_value     = wp_kses( $input_value,$allowed_html );
    }
    
    
 
    

    $meta_term['key']        = $term;
    $meta_term['value']      = $term_value;
    $meta_term['type']       = $search_type;
    $meta_term['compare']    = $compare;
    
    
           
       
                   
    return $meta_term;
               
    
}
















function wpestate_add_tax_element($term,$how,$input){
 
    $taxcateg_include       =   array();
    $taxonomy_term          =   array();
    $input_value            =   '';
    
    if( isset( $input[$term] )){                
        $input_value        =    sanitize_text_field (rawurldecode($input[$term]));
        $taxcateg_include[] =   $input_value;
    }
    
    if(strtolower ($input_value)!='all' && $input_value!=''){
        $taxonomy_term=array(
            'taxonomy'  => $term,
            'field'     => 'slug',
            'terms'     => $taxcateg_include
        );
    }
    
    return $taxonomy_term;
    
}









function rentals_is_tax_case($term){
    if($term=='property_category' || $term=='property_action_category' || $term=='property_city' || $term=='property_area' ){
        return true;
    }
    return false;
    
}


if( !function_exists('wpestate_build_dropdown_adv_new') ):
function wpestate_build_dropdown_adv_new($appendix,$term,$term_value,$dropdown_list,$label){
    $extraclass='';
    $caret_class='';
    $wrapper_class='';
    $return_string='';
    $is_half=0;
    $allowed_html =array();
            
    if($appendix==''){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
    }else  if($appendix=='sidebar-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_sidebar '; 
    } else  if($appendix=='shortcode-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
        $wrapper_class = 'listing_filter_select';
    } else  if($appendix=='mobile-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
        $wrapper_class = '';
    }else  if($appendix=='half-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
        $wrapper_class = '';
      
        $appendix='';
        $is_half=1;
    }
    
   

        $term_value= str_replace('-', ' ', $term_value);

 
        $return_string.=  '<div class="dropdown custom_icon_class  form-control '.$wrapper_class.'"> ';
        $return_string.=  '<div data-toggle="dropdown" id="'.sanitize_key( $appendix.$term ).'_toogle" class="'.$extraclass.'"   data-value="'.( esc_attr( $term_value) ).'">';
          
        if (  $term=='property_category' || $term=='property_action_category' || $term=='property_city' || $term=='property_area' 
                || $term=='property_county' || $term=='property_country'){
                if( strtolower($term_value) =='' ||  strtolower ($term_value) =='all'  ){

                        if($term=='property_category'){
                            $return_string.=   wpestate_category_labels_dropdowns('main');
                        }else if($term=='property_action_category'){
                            $return_string.=  wpestate_category_labels_dropdowns('second');
                        }else if($term=='property_city' ){
                            $return_string.= esc_html__('All Cities','wprentals');
                        }else if($term=='property_area'){
                            $return_string.=esc_html__('All Areas','wprentals');
                        }else if($term=='property_county'){
                            $return_string.= esc_html__('All Counties/States','wprentals');
                        }else if($term=='property_country'){
                            $return_string.= esc_html__('All Countries','wprentals');
                        }else{
                            $return_string.=ucfirst($label);
                        }

                }else{
                    $return_string.= ucfirst($term_value);     
                }
                
        }else{
              
                if (function_exists('icl_translate') ){
                    $term_value = apply_filters('wpml_translate_single_string', trim($term_value),'custom field value','custom_field_value'.$term_value );
                }
           
                if( strtolower ($term_value) =='all' || $term_value=='' ){
                    $return_string.= ucfirst( stripslashes( $label) );
                }else{
                    $return_string.=  ucfirst( stripslashes( $term_value) );
                }
        }
                    

            $return_string.= '
            <span class="caret '.$caret_class.'"></span>
            </div>';           
                     
                    
            $return_string.=' <input type="hidden" name="'.sanitize_key( $term ).'" id="'.sanitize_key( $appendix.$term ).'" value="';
                if(isset($_GET[$term])){
                    $return_string.= strtolower( esc_attr ( $_GET[$term] ) );
                }
          

                $return_string.='">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="'.sanitize_key( $appendix.$term ).'_toogle">
                    '.$dropdown_list.'
                </ul>        
            </div>';
                    
                       
    return $return_string;                
}
endif;




if( !function_exists('wpestate_get_action_select_list_4all') ):
   
    function wpestate_get_action_select_list_4all($args,$taxonomy){
        
        $categ_select_list  =   get_transient('wpestate_get_select_list_'.$taxonomy);
        if($categ_select_list===false){
          
                     
            $categories         =   get_terms($taxonomy,$args);
            if($taxonomy=='property_category'){
                $categ_select_list  =   ' <li role="presentation" data-value="all">'.  wpestate_category_labels_dropdowns('main').'</li>';
            }else  if($taxonomy=='property_action_category'){
                $categ_select_list  =   ' <li role="presentation" data-value="all">'.   wpestate_category_labels_dropdowns('second').'</li>';
            }else  if($taxonomy=='property_city'){
                $categ_select_list  =   ' <li role="presentation" data-value="all">'.  esc_html__('All Cities','wprentals').'</li>';
            }else{
                $categ_select_list  =   ' <li role="presentation" data-value="all">'.  esc_html__('All Areas','wprentals').'</li>';
            }
            
 
            
            foreach ($categories as $categ) {
                $received   =   wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 
                $counter    =   $categ->count;
                if( isset($received['count'])   ){
                    $counter = $counter+$received['count'];
                }

                $categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
                if(isset($received['html'])){
                    $categ_select_list     .=   $received['html'];  
                }

            }
            $transient_appendix =   '';
            $transient_appendix =   wpestate_add_language_currency_cache($transient_appendix,1);
            set_transient('wpestate_get_action_select_list'.$transient_appendix,$categ_select_list,4*60*60);
           
        }
        return $categ_select_list;
    }
endif;