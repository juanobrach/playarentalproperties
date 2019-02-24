<?php

function wpestate_world_return_levels(){
    $levels =   array(  'test'  =>  array(
                                        'listings'=> 10,
                                        'features'=> 'all_in_one'
                                    )
                    );
    return $levels;
}


function wpestate_world_can_we_publish(){
    $current_site_level     =   get_option('wpestate_mem_level',true);
    $levels                 =   wpestate_world_return_levels();
    $current_listed         =   wpestate_how_many_lisitings();
  
    if( $levels[$current_site_level]['listings'] > $current_listed ){
        return true;
    }else{
        return false;
    }
    
}

function wpestate_how_many_lisitings(){
    $args = array(
        'post_type'         => 'estate_property',
        'post_status'       => 'any',
        'paged'             => -1,
    );
    
    $query = new WP_Query($args);
    
    $current_listed= $query->found_posts;
    wp_reset_postdata();
    wp_reset_query();
    return $current_listed;
    
}

function wpestate_my_lisitings(){
    $args = array(
        'post_type'         => 'estate_property',
        'post_status'       => 'any',
        'posts_per_page'    => 5,
    );
    
    $query      = new WP_Query($args);
    $my_posts   = array();
    
    while($query->have_posts()):
        $query->the_post();
    
        $temp_array['id']   =   get_the_ID();
        $temp_array['url']  =   get_permalink();
        $temp_array['title']=   get_the_title();
        $my_posts[]=$temp_array;
    endwhile;
    
    wp_reset_postdata();
    wp_reset_query();
    return $my_posts;
    
}

function wpestate_my_pages(){
    $args = array(
        'post_type'         => 'page',
        'post_status'       => 'any',
        'posts_per_page'    => 5,
    );
    
    $query      = new WP_Query($args);
    $my_posts   = array();
    
    while($query->have_posts()):
        $query->the_post();
    
        $temp_array['id']   =   get_the_ID();
        $temp_array['url']  =   get_permalink();
        $temp_array['title']=   get_the_title();
        $my_posts[]=$temp_array;
    endwhile;
    
    wp_reset_postdata();
    wp_reset_query();
    return $my_posts;
    
}


function wpestate_how_many_pages(){
    $args = array(
        'post_type'         => 'page',
        'post_status'       => 'any',
        'paged'             => -1,
    );
    
    $query = new WP_Query($args);
    
    $current_pages= $query->found_posts;
    wp_reset_postdata();
    wp_reset_query();
    return $current_pages;
    
}
add_action( 'wp_ajax_wpestate_cache_notice_set', 'wpestate_cache_notice_set' );  
if( !function_exists('wpestate_cache_notice_set') ):
function wpestate_cache_notice_set(){ 
  
    
    if(current_user_can('administrator')){
        update_option('wp_estate_cache_notice','yes');
           
    }
    die();

    
}
endif;

add_action( 'wp_ajax_wpestate_ajax_start_map', 'wpestate_ajax_start_map' );  
if( !function_exists('wpestate_ajax_start_map') ):
function wpestate_ajax_start_map(){ 
   
    $api_key           =   sanitize_text_field($_POST['api_key']) ;
    
    if(current_user_can('administrator')){
        update_option('wp_estate_api_key',$api_key);
    }
    die();

    
}
endif;


add_action( 'wp_ajax_wpestate_ajax_general_set', 'wpestate_ajax_general_set' );  
if( !function_exists('wpestate_ajax_general_set') ):
function wpestate_ajax_general_set(){ 
    $prices_th_separator_set   =   sanitize_text_field ($_POST['prices_th_separator_set']) ;   
    $currency_label_main       =   sanitize_text_field ($_POST['currency_label_main']) ;
    $where_currency_symbol     =   sanitize_text_field ($_POST['where_currency_symbol']) ;
    $date_lang                 =   sanitize_text_field ($_POST['date_lang']) ;
    
    if(current_user_can('administrator')){
            update_option('wp_estate_prices_th_separator',$prices_th_separator_set);
            update_option('wp_estate_currency_label_main',$currency_label_main);
            update_option('wp_estate_where_currency_symbol',$where_currency_symbol);
            update_option('wp_estate_date_lang',$date_lang);
    }
    die();

    
}
endif;


add_action( 'wp_ajax_wpestate_booking_settings', 'wpestate_booking_settings' );  
if( !function_exists('wpestate_booking_settings') ):
function wpestate_booking_settings(){ 
    $date_format_set   =   sanitize_text_field ($_POST['date_format_set']) ;   
    $setup_weekend     =   sanitize_text_field ($_POST['setup_weekend']) ;

    
    if(current_user_can('administrator')){
            update_option('wp_estate_date_format',$date_format_set);
            update_option('wp_estate_setup_weekend',$setup_weekend);
    }
    die();
    
    
}
endif;


add_action( 'wp_ajax_wpestate_booking_payment', 'wpestate_booking_payment' );  
if( !function_exists('wpestate_booking_payment') ):
function wpestate_booking_payment(){ 
    $include_expenses      =   sanitize_text_field ($_POST['include_expenses']) ;   
    $book_down             =   sanitize_text_field ($_POST['book_down']) ;   
    $book_down_fixed_fee   =   sanitize_text_field ($_POST['book_down_fixed_fee']) ;   
    $service_fee           =   sanitize_text_field ($_POST['service_fee']) ;   
    $service_fee_fixed_fee =   sanitize_text_field ($_POST['service_fee_fixed_fee']) ; 
     
    if(current_user_can('administrator')){
            update_option('wp_estate_include_expenses',$include_expenses);
            update_option('wp_estate_book_down',$book_down);
            update_option('wp_estate_book_down_fixed_fee',$book_down_fixed_fee);
            update_option('wp_estate_service_fee',$service_fee);
            update_option('wp_estate_service_fee_fixed_fee',$service_fee_fixed_fee);
    }
    die();

}
endif;

