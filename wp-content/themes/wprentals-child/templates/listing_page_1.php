<?php
global $post;
global $current_user;
global $feature_list_array;
global $propid ;
global $post_attachments;
global $options;
global $where_currency;
global $property_description_text;     
global $property_details_text;
global $property_features_text;
global $property_adr_text;  
global $property_price_text;   
global $property_pictures_text;    
global $propid;
global $gmap_lat;  
global $gmap_long;
global $unit;
global $currency;
global $use_floor_plans;
global $favorite_text;
global $favorite_class;
global $property_action_terms_icon;
global $property_action;
global $property_category_terms_icon;
global $property_category;
global $guests;
global $bedrooms;
global $bathrooms;
global $show_sim_two;
global $guest_list;
global $post_id;

$price              =   intval   ( get_post_meta($post->ID, 'property_price', true) );
$price_label        =   esc_html ( get_post_meta($post->ID, 'property_label', true) );  
$property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');

$post_id=$post->ID; 
$guest_no_prop ='';
if(isset($_GET['guest_no_prop'])){
    $guest_no_prop = intval($_GET['guest_no_prop']);
}
$guest_list= wpestate_get_guest_dropdown('noany');
?>


<div class="row content-fixed-listing listing_type_1">

    <?php //get_template_part('templates/breadcrumbs'); ?>
    <div class=" <?php 
    if ( $options['content_class']=='col-md-12' || $options['content_class']=='none'){
        print 'col-md-8';
    }else{
       print  $options['content_class']; 
    }?> ">
    
        <?php get_template_part('templates/ajax_container'); ?>
        <?php
        while (have_posts()) : the_post();
            $image_id       =   get_post_thumbnail_id();
            $image_url      =   wp_get_attachment_image_src($image_id, 'wpestate_property_full_map');
            $full_img       =   wp_get_attachment_image_src($image_id, 'full');
            $image_url      =   $image_url[0];
            $full_img       =   $full_img [0];     
        ?>
        
     
    <div class="single-content listing-content">
        <?php
        if ( wp_is_mobile() ) {
            get_template_part ('templates/booking_form_template');
        }
        ?>
        
        
        <h1 class="entry-title entry-prop"><?php the_title(); ?>        
            <div class="property_ratings">
                <?php 
                if(wpestate_has_some_review($post->ID)!==0){
                    print wpestate_display_property_rating( $post->ID ); 
                    $args = array(
                     
                        'post_id' => $post->ID, // use post_id, not post_ID
                    );
                    $comments   =   get_comments($args);
                    $coments_no =   0;
                    $stars_total=   0;

                   
                    foreach($comments as $comment) :
                        $coments_no++;

                    endforeach;
                    print '<div class="rating_no">('.$coments_no.')</div>';
                
                } 
                ?>         
            </div> 
        </h1>
       
        <div class="listing_main_image_location">
            <?php print  $property_city.', '.$property_area; ?>        
        </div>   


        <div class="panel-wrapper imagebody_wrapper">
            <div class="panel-body imagebody imagebody_new">
                <?php  
                get_template_part('templates/property_pictures3');
                ?>
            </div> 
        </div>

        
        
        <div class="category_wrapper ">
            <div class="category_details_wrapper">
                <?php if( $property_action!='') {
                    echo $property_action; ?> <span class="property_header_separator">|</span>
                <?php } ?>
                
                <?php  if( $property_category!='') {
                    echo $property_category;?> <span class="property_header_separator">|</span> 
                <?php } ?> 
                    
                <?php  
                if($guests==1){
                    print '<span class="no_link_details">'.$guests.' '. esc_html__( 'Guest','wpestate').'</span>';
                }else{
                    print '<span class="no_link_details">'.$guests.' '. esc_html__( 'Guests','wpestate').'</span>';
                }    
                ?><span class="property_header_separator">|</span>
                
                <?php  
                if($bedrooms==1){
                    print  '<span class="no_link_details">'.$bedrooms.' '.esc_html__( 'Bedroom','wpestate').'</span>';
                }else{
                    print  '<span class="no_link_details">'.$bedrooms.' '.esc_html__( 'Bedrooms','wpestate').'</span>';
                }
                ?><span class="property_header_separator">|</span>
                
                <?php 
                if($bathrooms==1){
                    print  '<span class="no_link_details">'.$bathrooms.' '.esc_html__( 'Bath','wpestate').'</span>';
                }else{
                    print  '<span class="no_link_details">'.$bathrooms.' '.esc_html__( 'Baths','wpestate').'</span>';
                }
                ?>
            </div>

            <a href="#listing_calendar" class="check_avalability"><?php esc_html_e('Check Availability','wpestate');?></a>
			<p class="listingContact">Call now for more info! MX: <a href="tel:(+521) 984-803-5617">(+521) 984-803-5617</a> US/CA: <a href="tel:(+1) 719-219-9623">(+1) 719-219-9623</a> or fill out the form on this page.</p>
        </div>
        
        
        
        <div id="listing_description">
        <?php
            $content = get_the_content();
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            if($content!=''){   
                   
                $property_description_text =  get_option('wp_estate_property_description_text');
                if (function_exists('icl_translate') ){
                    $property_description_text     =   icl_translate('wpestate','wp_estate_property_description_text', esc_html( get_option('wp_estate_property_description_text') ) );
                }

                    
                print '<h4 class="panel-title-description">'.$property_description_text.'</h4>';
                print '<div class="panel-body">'.$content.'</div>';       
            }
        ?>
             
        </div>
        <div id="view_more_desc"><?php esc_html_e('View more','wpestate');?></div>
     
          
        <!-- property price   -->   
        <div class="panel-wrapper" id="listing_price">
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseOne"> <span class="panel-title-arrow"></span>
                <?php if($property_price_text!=''){
                    echo $property_price_text;
                } else{
                    esc_html_e('Seasonal Rates','wpestate');
                }  ?>
            </a>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    
                    <?php 
					//Print Rates on Single Property Page
					$i = 1;
						while ($i < 6) {
							$rateNumTitle = 'rate' . $i . 'Title';
							$rateNumFrom = 'rate' . $i . 'From';
							$rateNumTo = 'rate' . $i . 'To';
							$rateNumValue = 'rate' . $i . 'Value';
							$rateName = get_post_meta($post->ID, $rateNumTitle, true);
							$rateFrom = get_post_meta($post->ID, $rateNumFrom, true);
							$rateTo = get_post_meta($post->ID, $rateNumTo, true);
							$rateValue = get_post_meta($post->ID, $rateNumValue, true);
							if ($rateName !='') {
							echo '<div class="rateRow clearfix">';
							echo '<div class="rateName"><strong>' . $rateName . '</strong></div>';
							echo '<div class="rateFrom">From ' . $rateFrom . '</div>';
							echo '<div class="rateTo">To ' . $rateTo . '</div>';
							echo '<div class="rateValue">$ ' . $rateValue . ' /week.</div>';
							echo '</div>';
							}
							$i++;
						}
					
					?>
               
					
                </div>
            </div>
        </div>
        
        
        
        <div class="panel-wrapper">
            <!-- property address   -->             
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTwo">  <span class="panel-title-arrow"></span>
                <?php if($property_adr_text!=''){
                    echo $property_adr_text;
                } else{
                    esc_html_e('Property Address','wpestate');
                }
                ?>
            </a>   
            
            
            <div id="collapseTwo" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    <?php print estate_listing_address($post->ID); ?>
                </div>
                
            </div>
        </div>
      
        
        
        <!-- property details   -->  
        <div class="panel-wrapper">
            <?php                                       
            if($property_details_text=='') {
                print'<a class="panel-title" id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.esc_html__( 'Property Details', 'wpestate').'  </a>';
            }else{
                print'<a class="panel-title"  id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.$property_details_text.'  </a>';
            }
            ?>
            <div id="collapseTree" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    <?php print estate_listing_details($post->ID);?>
                </div>
            </div>
        </div>

        

        <!-- Features and Amenities -->
        <div class="panel-wrapper features_wrapper">
            <?php 

            if ( count( $feature_list_array )!=0 && !count( $feature_list_array )!=1 ){ //  if are features and ammenties
                if($property_features_text ==''){
                    print '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'.esc_html__( 'Amenities and Features', 'wpestate').'</a>';
                }else{
                    print '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'. $property_features_text.'</a>';
                }
                ?>
                <div id="collapseFour" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">
                        <?php print estate_listing_features($post->ID); ?>
                    </div>
                </div>
            <?php
            } // end if are features and ammenties
            ?>
        </div>
        
   
		 <div class="panel-wrapper calendar_wrapper">
        <div class="property_page_container ">
            <?php
            get_template_part ('/templates/show_avalability');
            wp_reset_query();
            ?>  
        </div>    
        </div>    
        <?php
        endwhile; // end of the loop
        $show_compare=1;
        ?>

        <?php     get_template_part ('/templates/listing_reviews'); ?>


        
        </div><!-- end single content -->
        
        <div class="property_page_container"> 
            <h3 class="panel-title" id="on_the_map"><?php esc_html_e('On the Map','wpestate');?></h3>
            <div class="google_map_on_list_wrapper">            
                <div id="gmapzoomplus"></div>
                <div id="gmapzoomminus"></div>
                <div id="gmapstreet"></div>
                <?php echo wpestate_show_poi_onmap();?>
                
                <div id="google_map_on_list" 
                    data-cur_lat="<?php   echo $gmap_lat;?>" 
                    data-cur_long="<?php echo $gmap_long ?>" 
                    data-post_id="<?php echo $post->ID; ?>">
                </div>
            </div>    
        </div> 
 
            
        <?php   
        $show_sim_two=1;
        get_template_part ('/templates/similar_listings');
        ?> 
    </div><!-- end 8col container-->
    
    
    
  
    
    <div class="clearfix visible-xs"></div>
    <div class=" 
        <?php
        if($options['sidebar_class']=='' || $options['sidebar_class']=='none' ){
            print ' col-md-4 '; 
        }else{
            print $options['sidebar_class'];
        }
        ?> 
        widget-area-sidebar listingsidebar2 listing_type_1" id="primary" >
        <?php
        if ( !wp_is_mobile() ) {
            get_template_part ('templates/booking_form_template');
        }
        ?>
        <div class="owner_area_wrapper_sidebar" id="listing_owner">
            <?php get_template_part ('/templates/owner_area2'); ?>
        </div>
        
        
        <?php  include(locate_template('sidebar-listing.php')); ?>
    </div>
</div>   

<?php get_footer(); ?>