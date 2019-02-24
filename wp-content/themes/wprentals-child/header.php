<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
       
        <?php
        
        if(is_singular('wpestate_invoice') || is_singular('wpestate_message')){
            echo '<meta name="robots" content="noindex">';
        }
        
        wp_head(); 
        $favicon = esc_html(get_option('wp_estate_favicon_image', ''));

        if ($favicon != '') {
            echo '<link rel="shortcut icon" href="' . $favicon . '" type="image/x-icon" />';
        } else {
            echo '<link rel="shortcut icon" href="' . get_template_directory_uri() . '/img/favicon.gif" type="image/x-icon" />';
        }

        $wide_class = 'boxed';
        $wide_status = esc_html(get_option('wp_estate_wide_status', ''));
        if ($wide_status == 1) {
            $wide_class = " wide ";
        }
        
        $header_type    =   'header_'. esc_html(get_option('wp_estate_logo_header_type', ''));
        $header_align   =   'header_align_'.  esc_html(get_option('wp_estate_logo_header_align', ''));
        $header_wide    =   'header_wide_'.esc_html(get_option('wp_estate_wide_header', ''));
        $top_menu_hover_type        =   get_option('wp_estate_top_menu_hover_type',''); 
        
        if($header_wide=='yes' ||   is_page_template( 'splash_page.php' ) ){
             $header_wide    =  "header_wide_yes";
        }
                
        if( !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) && wpestate_check_if_admin_page($post->ID) && basename(get_page_template($post->ID)) == 'splash_page.php'){
            $wide_class = " wide ";
        }
  

        $wide_page_class = '';
        $map_template = '';
        $header_map_class = '';


        if ( !is_search() && !is_404() && !is_tax() && !is_category()  && !is_tag() && basename(get_page_template($post->ID)) == 'property_list_half.php') {
            $header_map_class = 'google_map_list_header';
            $map_template = 1;
            $wide_class = " wide ";
        }
        
        if (( is_category() || is_tax() ) && get_option('wp_estate_property_list_type', '') == 2) {
            $header_map_class = 'google_map_list_header';
            $map_template = 1;
            $wide_class = " wide ";   
            if( !is_tax() ){
                $map_template = 2;
            }
        }

        if (is_page_template('advanced_search_results.php') && get_option('wp_estate_property_list_type_adv', '') == 2) {
            $header_map_class = 'google_map_list_header';
            $map_template = 1;
            $wide_class = " wide ";
        }
        
        if(is_singular('wpestate_booking') || is_singular('wpestate_invoice')){
            print '<meta name="robots" content="noindex">';
        }
        
        
      
        
        
    if (get_post_type()== 'estate_property'){
        $image_id       =   get_post_thumbnail_id();
        $share_img      =   wp_get_attachment_image_src( $image_id, 'full'); 
        $the_post       =   get_post($post->ID);
        ?>
        <meta property="og:image" content="<?php echo esc_url($share_img[0]); ?>"/>
        <meta property="og:image:secure_url" content="<?php echo esc_url($share_img[0]); ?>" />
        <meta property="og:description"        content=" <?php echo wp_strip_all_tags( $the_post->post_content);?>" />
    <?php 
    } 
    ?>
	<!-- Global site tag (gtag.js) - Google Ads: 783993654 -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-783993654"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'AW-783993654');
	</script> 
    </head>

    <?php
    global $is_top_bar_class;
    $is_top_bar_class = "";
    if (wpestate_show_top_bar()) {
        $is_top_bar_class = " top_bar_on";
    }
    
    
    $transparent_menu_global        =    get_option('wp_estate_transparent_menu','');
    $transparent_class              =    ' ';
    $property_list_type_status      =    esc_html(get_option('wp_estate_property_list_type',''));
    $property_list_type_status_adv  =    esc_html(get_option('wp_estate_property_list_type_adv',''));

    if($transparent_menu_global == 'yes'){
        if(is_tax() && $property_list_type_status == 2 ){
            $transparent_class = '';
        }else{
            $transparent_class = ' transparent_header ';
        }
        
        if( !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) && basename(get_page_template($post->ID)) == 'property_list_half.php' ){
            $transparent_class = '';
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }
    
        if (  !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) && basename(get_page_template($post->ID)) == 'advanced_search_results.php' && $property_list_type_status_adv == 2 ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }
        
        if ( is_tax() && $property_list_type_status == 2 ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }
        
        if( is_single() || is_page() ){
            if( get_post_meta($post->ID, 'transparent_status', true) === 'no' ){
                $transparent_class='';
            }
        }
        
    }else{
        
        if ( !is_search() && !is_404() && !is_tax() && !is_category() && !is_tag() && get_post_meta($post->ID, 'transparent_status', true) === 'yes' && basename(get_page_template($post->ID)) != 'property_list_half.php') {
             $transparent_class = ' transparent_header ';
        }     
          
        if(  !is_404() && !is_tax() && !is_category() && !is_tag()  && isset($post->ID) && basename(get_page_template($post->ID)) == 'property_list_half.php' ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        } 
     
        if (  !is_404() && !is_tax() && !is_category() && !is_tag() && isset($post->ID) && basename(get_page_template($post->ID)) == 'advanced_search_results.php' && $property_list_type_status_adv == 2 ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }
  
        if ( is_tax() && $property_list_type_status == 2 ){
            $is_top_bar_class=$is_top_bar_class.' is_half_map ';
        }   
    }
    
    
    $is_dashboard_page='';

    
    if( is_page() && wpestate_check_if_admin_page($post->ID) && is_user_logged_in()  ){
        $is_dashboard_page='is_dashboard_page';
        if( get_option('wp_estate_show_menu_dashboard','') =='no'){
            $is_top_bar_class.=" no_header_dash ";
        }
    }
    
    
    if(is_singular('estate_property')){
        $transparent_menu_listing = get_option('wp_estate_transparent_menu_listing','');
        if( $transparent_menu_listing == 'no'){
            $transparent_class = '';
        }else{
            $transparent_class = ' transparent_header ';
        }
        
    }
       
    $search_type                =  esc_html( get_option('wp_estate_adv_search_type',''));
    if ( $search_type!='oldtype' ){
        $search_type='is_search_type1';
    }else{
        $search_type='is_search_type2';
    }
    ?>

    <body <?php body_class($is_top_bar_class); ?>> 
        <?php get_template_part('templates/mobile_menu'); ?>
        
        <div class="website-wrapper <?php echo  'is_'.trim($transparent_class.$header_type) .' '.$is_top_bar_class.' '.$search_type;?>"  id="all_wrapper">
            <div class="container main_wrapper <?php print  $wide_class; print $is_dashboard_page; ?> ">
               <div class="master_header <?php print 'master_'.trim($transparent_class) .' '.$wide_class.' '.$header_map_class.' master_'. $header_wide.' hover_type_'.$top_menu_hover_type; ?>">
           
            
                <?php
                if (wpestate_show_top_bar() && !is_page_template( 'splash_page.php' )) {
                    get_template_part('templates/top_bar');
                }
                ?>
                    
                 <?php get_template_part('templates/mobile_menu_header'); ?>    
                    

                    <div class="header_wrapper <?php print $transparent_class . $is_top_bar_class .' '. $header_type .' '. $header_align .' '. $header_wide; ?>">
                        <div class="header_wrapper_inside">
                           
                            <div class="logo"> 

                                <a href="<?php
                                $splash_page_logo_link = get_option('wp_estate_splash_page_logo_link', '');
                                if (is_page_template('splash_page.php') && $splash_page_logo_link != '') {
                                    print $splash_page_logo_link;
                                } else {
                                    echo home_url('', 'login');
                                }
                                ?>">                                   
                                   
                                <?php
                                $logo='';
                                if( trim($transparent_class)!==''){
                                    $logo = get_option('wp_estate_transparent_logo_image', '');  
                                }else{
                                    $logo = get_option('wp_estate_logo_image', '');  
                                }
                                
                                if ($logo != '') {
                                    print '<img src="' . $logo . '" class="img-responsive retina_ready"  alt="logo"/>';
                                } else {
                                    print '<img class="img-responsive retina_ready" src="' . get_template_directory_uri() . '/img/logo.png" alt="logo"/>';
                                }
                                ?>
                                    
                                    
                                </a>
                            
                            </div>   
                            
                            <?php
                            if (esc_html(get_option('wp_estate_show_top_bar_user_login', '')) == "yes") {
                                get_template_part('templates/top_user_menu');
                            }
                            ?>   
                            
                            <nav id="access">
                                <?php wp_nav_menu(array(
                                            'theme_location'    => 'primary',
                                            'container'         => false,
                                            'walker'            => new wpestate_custom_walker()
                                        )); 
                                ?>
                            </nav><!-- #access -->
                        </div>
                    </div>

                </div> 

<?php
if (!is_search() && !is_tag() && !is_404() && !is_tax() && !is_category() && ( basename(get_page_template($post->ID)) === 'property_list_half.php' || get_post_type() === 'estate_property' )) {
    //do nothing for now  
} else if (( is_category() || is_tax() ) && get_option('wp_estate_property_list_type', '') ==  2 ) {
    if( !is_tax() ){
        get_template_part('header_media');
    }
    
} else if (is_page_template('advanced_search_results.php') && get_option('wp_estate_property_list_type_adv', '') == 2) {
    //do nothing for now 
} else {
    get_template_part('header_media');
}

if (get_post_type() === 'estate_property' && !is_tax() && !is_search()) {
    get_template_part('templates/property_menu_hidden');
}
?>



<?php
if ($map_template === 1) {
    print '  <div class="full_map_container">';
} else {
    if (!is_404() && !is_tax() && !is_category() && !is_search() && !is_tag()) {
        if ( wpestate_check_if_admin_page($post->ID)) {
            print '  <div class="container content_wrapper_dashboard">';
        } else {
            if ('estate_property' == get_post_type()) {
                if ( is_404()) {
                    print '<div class="content_wrapper  ' . $wide_page_class . ' row ">';
                } else {
                    print '<div itemscope itemtype="http://schema.org/RentAction"  class="content_wrapper listing_wrapper ' . $wide_page_class . ' row ">';
                }
            } else {
                if ( is_singular('estate_agent') ) {
                    get_template_part('templates/owner_details_header');
              
                }
                print '  <div class="content_wrapper ' . $wide_page_class . ' row ">';
            }
        }
    } else {
        print '  <div class="content_wrapper ' . $wide_page_class . 'row ">';
    }
}


?>