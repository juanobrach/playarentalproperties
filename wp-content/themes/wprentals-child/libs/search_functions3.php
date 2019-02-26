<?php
/**
* WDP custom search function for advanced search results.
* Original function in /parent-theme/libs/search_functions3.php
*
*/
function WDP_wpestate_argumets_builder($input,$is_half=''){
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
    // Uncomment this filter to rollback and order search results by ID instead by title
    // add_filter( 'posts_orderby', 'wpestate_my_order' );
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