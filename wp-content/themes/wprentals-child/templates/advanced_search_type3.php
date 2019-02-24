<?php 
global $post;
global $adv_search_type;
$adv_search_what            =   get_option('wp_estate_adv_search_what','');
$adv_search_icon            =   get_option('wp_estate_search_field_label','');
$show_adv_search_visible    =   get_option('wp_estate_show_adv_search_visible','');
$close_class                =   '';

if($show_adv_search_visible=='no'){
    $close_class='adv-search-1-close';
}

$extended_search    =   get_option('wp_estate_show_adv_search_extended','');
$extended_class     =   '';

if ($adv_search_type==2){
     $extended_class='adv_extended_class2';
}

if ( $extended_search =='yes' ){
    $extended_class='adv_extended_class';
    if($show_adv_search_visible=='no'){
        $close_class='adv-search-1-close-extended';
    }
       
}

?>

 


<div class="adv-search-3" id="adv-search-3" > 

    <?php 
        $adv_search_label_for_form            =    ( esc_html( get_option('wp_estate_adv_search_label_for_form') ) );  
        if($adv_search_label_for_form!=''){
            print '<div id="adv-search-header-3"><h2>'.$adv_search_label_for_form.'</h2></div>';

        }
    ?>
    
    
    <form role="search" method="get" autocomplete="off"   action="<?php esc_url(print $adv_submit); ?>" >
        <?php
        if (function_exists('icl_translate') ){
            print do_action( 'wpml_add_language_form_field' );
        }
        ?>   
        
        
        <div class="adv3-holder">
            <?php
            $custom_advanced_search         =   get_option('wp_estate_custom_advanced_search','');
            $adv_search_fields_no_per_row   =   ( floatval( get_option('wp_estate_search_fields_no_per_row') ) );
            
           
            
         
                foreach($adv_search_what as $key=>$search_field){
                    $search_col         =   3;
                    $search_col_price   =   6;
                    if($adv_search_fields_no_per_row==2){
                        $search_col         =   6;
                        $search_col_price   =   12;
                    }else  if($adv_search_fields_no_per_row==3){
                        $search_col         =   4;
                        $search_col_price   =   8;
                    }
                    
                    $search_col_submit = $search_col;
                    
                    if($search_field=='property_price' ){
                        $search_col=$search_col_price;
                    }
                    if(strtolower($search_field)=='location' ){
                        $search_col=$search_col_price;
                    }
					if($search_field=='property_area'){$wdpCols = 4 . ' ';} else {$wdpCols = 2 . ' ';}
                    print '<div class="col-md-' . $wdpCols . str_replace(" ","_", stripslashes($search_field) ).' ">';
                    print wpestate_show_search_field_new($_REQUEST,'mainform',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key);
                    
                    print '</div>';
                    
                }
              
                
                print '<div class="col-md-2 '.str_replace(" ","_",$search_field).'">';
                print '<input name="submit" type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="advanced_submit_3" value="'.__('Search','wprentals').'">';
                print '</div>';
        

            if($extended_search=='yes'){
               show_extended_search('adv');
            }
            ?>
        </div>
    </form>   
    <div style="clear:both;"></div>
	
</div>  