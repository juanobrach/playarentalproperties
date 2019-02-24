<?php
$current_user               =   wp_get_current_user();
$user_custom_picture        =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
$user_small_picture_id      =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
if( $user_small_picture_id == '' ){

    $user_small_picture[0]=get_template_directory_uri().'/img/default_user_small.png';
}else{
    $user_small_picture=wp_get_attachment_image_src($user_small_picture_id,'wpestate_user_thumb');
    
}
?>

   
<?php if(is_user_logged_in()){ ?>
    <div class="user_menu user_loged" id="user_menu_u">
        <div class="menu_user_picture" style="background-image: url('<?php print $user_small_picture[0]; ?>');"></div>
        <a class="menu_user_tools dropdown" id="user_menu_trigger" data-toggle="dropdown">    
        <?php
        // <i class="fa fa-bars"></i>
        echo '<span class="menu_username">'.ucwords($current_user->user_login).'</span>'; 
        ?>   <i class="fas fa-caret-down"></i> 
         </a>
       
<?php }else{ ?>
    <div class="user_menu" id="user_menu_u">   
        <div class="signuplink" id="topbarlogin"><?php esc_html_e('Login','wprentals');?></div>
        
        
        <?php if(  esc_html ( get_option('wp_estate_show_submit','') ) ==='yes'){ ?>
            <a href="<?php print wpestate_get_template_link('user_dashboard_add_step1.php');?>" id="submit_action"><?php esc_html_e('Submit Property','wprentals');?></a>
        <?php } ?>                   
       
<?php } ?>   
                  
    </div> 
        
        
<?php 

if ( 0 != $current_user->ID  && is_user_logged_in() ) {
    $username               =   $current_user->user_login ;
    $userID                 =   $current_user->ID;
    $add_link               =   wpestate_get_template_link('user_dashboard_add_step1.php');
    $dash_profile           =   wpestate_get_template_link('user_dashboard_profile.php');
    $dash_favorite          =   wpestate_get_template_link('user_dashboard_favorite.php');
    $dash_link              =   wpestate_get_template_link('user_dashboard.php');
    $dash_searches          =   wpestate_get_template_link('user_dashboard_searches.php'); 
    $dash_reservation       =   wpestate_get_template_link('user_dashboard_my_reservations.php');
    $dash_bookings          =   wpestate_get_template_link('user_dashboard_my_bookings.php');
    $dash_inbox             =   wpestate_get_template_link('user_dashboard_inbox.php');
    $dash_invoices          =   wpestate_get_template_link('user_dashboard_invoices.php');
    $logout_url             =   wp_logout_url(wpestate_wpml_logout_url());      
    $home_url               =   esc_html( home_url() );
    
?> 
    <div id="user_menu_open"> 
        <?php if($home_url!=$dash_profile){?>
                <a href="<?php print get_admin_url();?>" ><i class="fas fa-cog"></i><?php esc_html_e('go to Admin','wprentals');?></a>   
        <?php   
        }
        ?>
        
        <?php if($home_url!=$dash_link && wpestate_check_user_level()){?>
            <a href="<?php print $dash_link;?>" ><i class="fas fa-map-marker"></i><?php esc_html_e('My Listings','wprentals');?></a>
        <?php   
        }
        ?>
        
        <?php if($home_url!=$add_link && wpestate_check_user_level()){?>
            <a href="<?php print $add_link;?>" ><i class="fas fa-plus"></i><?php esc_html_e('Add New Listing','wprentals');?></a>        
        <?php   
        }
        
        if($home_url!=$dash_bookings && wpestate_check_user_level() ){?>
            <a href="<?php print get_admin_url('','edit.php?post_type=wpestate_booking','');?>" class="active_fav"><i class="far fa-folder-open"></i><?php esc_html_e('Bookings','wprentals');?></a>
        <?php   
        }
        if($home_url!=$dash_inbox){
             $no_unread=  intval(get_user_meta($userID,'unread_mess',true));?>
            <a href="<?php print get_admin_url('','edit.php?post_type=prp_request','');?>" class="active_fav"><i class="fas fa-inbox"></i><?php esc_html_e('Inbox','wprentals');?></a>
        <?php   
         }
        
        ?>
           
       
        <a href="<?php echo wp_logout_url(wpestate_wpml_logout_url());?>" title="Logout" class="menulogout"><i class="fas fa-power-off"></i><?php esc_html_e('Log Out','wprentals');?></a>
    </div>
    
<?php } ?>