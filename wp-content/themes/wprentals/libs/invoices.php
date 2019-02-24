<?php
// register the custom post type
add_action( 'after_setup_theme', 'wpestate_create_invoice_type' );

if( !function_exists('wpestate_create_invoice_type') ):
function wpestate_create_invoice_type() {
register_post_type( 'wpestate_invoice',
		array(
			'labels' => array(
				'name'          => esc_html__(  'Invoices','wprentals'),
				'singular_name' => esc_html__(  'Invoices','wprentals'),
				'add_new'       => esc_html__( 'Add New Invoice','wprentals'),
                'add_new_item'          =>  esc_html__( 'Add Invoice','wprentals'),
                'edit'                  =>  esc_html__( 'Edit Invoice' ,'wprentals'),
                'edit_item'             =>  esc_html__( 'Edit Invoice','wprentals'),
                'new_item'              =>  esc_html__( 'New Invoice','wprentals'),
                'view'                  =>  esc_html__( 'View Invoices','wprentals'),
                'view_item'             =>  esc_html__( 'View Invoices','wprentals'),
                'search_items'          =>  esc_html__( 'Search Invoices','wprentals'),
                'not_found'             =>  esc_html__( 'No Invoices found','wprentals'),
                'not_found_in_trash'    =>  esc_html__( 'No Invoices found','wprentals'),
                'parent'                =>  esc_html__( 'Parent Invoice','wprentals')
			),
		'public'            => false,
                'show_ui'           =>true,
                'show_in_nav_menus' =>true,
                'show_in_menu'      =>true,
                'show_in_admin_bar' =>true,
		'has_archive'       => true,
		'rewrite' => array('slug' => 'invoice'),
		'supports' => array('title'),
		'can_export' => true,
		'register_meta_box_cb' => 'wpestate_add_pack_invoices',
                'menu_icon'=>get_template_directory_uri().'/img/invoices.png',
                'exclude_from_search'   => true    
		)
	);
}
endif; // end   wpestate_create_invoice_type  

////////////////////////////////////////////////////////////////////////////////////////////////
// Add Invoice metaboxes
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_pack_invoices') ):
    function wpestate_add_pack_invoices() {	
            add_meta_box(  'estate_invoice-sectionid',  esc_html__(  'Invoice Details', 'wprentals' ),'wpestate_invoice_details','wpestate_invoice' ,'normal','default');
    }
endif; // end   wpestate_add_pack_invoices  



////////////////////////////////////////////////////////////////////////////////////////////////
// Invoice Details
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_invoice_details') ):
    function wpestate_invoice_details( $post ) {
        global $post;
        wp_nonce_field( plugin_basename( __FILE__ ), 'estate_invoice_noncename' );

        $invoice_types      =   array( 'Listing'    => esc_html__( 'Listing','wprentals'),
                                       'Upgrade to Featured'   =>  esc_html__( 'Upgrade to Featured','wprentals'),
                                       'Publish Listing with Featured'  => esc_html__( 'Publish Listing with Featured','wprentals'),
                                       'Package'           =>esc_html__( 'Package','wprentals'),
                                       'Reservation fee'    => esc_html__( 'Reservation fee','wprentals')
                );

        $invoice_saved      =   esc_html(get_post_meta($post->ID, 'invoice_type', true));
   
        $purchase_type  =   0;
        if($invoice_saved=='Listing' || $invoice_saved==esc_html__('Listing','wprentals') ){
            $purchase_type=1;
        }else if( $invoice_saved == 'Upgrade to Featured' || $invoice_saved==esc_html__('Upgrade to Featured','wprentals')){
            $purchase_type=2;
        }else if($invoice_saved =='Publish Listing with Featured' || $invoice_saved==esc_html__('Publish Listing with Featured','wprentals') ){
            $purchase_type=3;
        }
    

        $invoice_period            =  array(esc_html__( 'One Time','wprentals'),esc_html__( 'Recurring','wprentals') ) ;
        $invoice_period_saved      =  esc_html(get_post_meta($post->ID, 'biling_type', true));
       
        $txn_id=esc_html(get_post_meta($post->ID, 'txn_id', true));

        $invoice_id     =   $post->ID;
     

        $bookid             =   esc_html(get_post_meta($invoice_id, 'item_id', true));
        $booking_prop       =   esc_html(get_post_meta($bookid, 'booking_id', true)); // property_id
        $owner_id           =   wpsestate_get_author($booking_prop);
        $agent_id           =   get_user_meta($owner_id, 'user_agent_id', true);
        
        print '<strong>'.esc_html__('Property','wprentals').':</strong> <a href="'.get_permalink($booking_prop).'" target="_blank">'.get_the_title($booking_prop).'</a>';
      
        if($agent_id==1){
            print '</br><strong>'.esc_html__('Owner ','wprentals').':</strong> Admin';
        }else{
            print '</br><strong>'.esc_html__('Owner ','wprentals').':</strong> '.get_the_title($agent_id);
        }
        ///////////////////////////////////////////////////////////////////////////////////
        wpestate_super_invoice_details($invoice_id);
        ///////////////////////////////////////////////////////////////////////////////////
        
        
  
       
        $purchase_date=esc_html(get_post_meta($post->ID, 'purchase_date', true));
        print'<div style="clear:both;"></div>
        <p class="meta-options">
            <strong>'.esc_html__( 'Invoice Id:','wprentals').' </strong>'.$post->ID.'
        </p>';
        
        $status             =   get_post_meta($post->ID, 'invoice_status', true); 
        $status_full        =   get_post_meta($post->ID, 'invoice_status_full', true); 
        $balance            =   get_post_meta($post->ID, 'balance', true); 
        $enable_wire_status =   esc_html ( get_option('wp_estate_enable_direct_pay','') );
        
        
        get_post_meta($post->ID, 'pay_status', true);
        
        if( get_post_meta($post->ID, 'pay_status', true) ==0){
           
            if($enable_wire_status==='yes' && $status!=='confirmed'){
   
                if($invoice_saved=='Package' || $invoice_saved==esc_html__('Package','wprentals') ){
                    print '<div id="activate_pack" data-invoice="'.$post->ID.'" data-item="'.get_post_meta($post->ID, 'item_id', true).'">'.esc_html__('Wire Payment Received - Activate the purchase','wprentals').'</div>';
                }else if($invoice_saved=='Reservation fee' || $invoice_saved==esc_html__('Reservation fee','wprentals')){
                    print '<div id="activate_pack_reservation_fee" data-invoice="'.$post->ID.'" data-item="'.get_post_meta($post->ID, 'item_id', true).'">'.esc_html__('Wire Payment Received - Activate the purchase','wprentals').'</div>';
                }else{
                    print '<div id="activate_pack_listing" data-invoice="'.$post->ID.'" data-item="'.get_post_meta($post->ID, 'item_id', true).' " data-type="'.$purchase_type.'">'.esc_html__('Wire Payment Received - Activate the purchase','wprentals').'</div>';      
                }
                

                print'
                <p class="meta-options" id="invnotpaid">
                    <strong>'.__('Invoice NOT paid','wprentals').' </strong>
                </p>';
            }else if ( $enable_wire_status==='yes' && $status=='confirmed' && $balance>0 ){

                if($invoice_saved=='Reservation fee' || $invoice_saved==esc_html__('Reservation fee','wprentals')){
                    print '<div id="activate_pack_reservation_fee" data-invoice="'.$post->ID.'" data-item="'.get_post_meta($post->ID, 'item_id', true).'">'.esc_html__('Wire Payment Received - Activate the purchase(invoice will be 100% paid)','wprentals').'</div>';

                }
                print'
                <p class="meta-options" id="invnotpaid">
                    <strong>'.__('Invoice NOT paid in full','wprentals').' </strong>
                </p>';




            }
           

        } else{
            print'
            <p class="meta-options">
                <strong>'.__('Invoice PAID','wprentals').' </strong>
            </p>';

        }
        
        
        print'
        <p class="meta-options">
            <label for="biling_period"><strong>'.esc_html__( 'Billing For :','wprentals').' </strong></label><br />
            '.$invoice_saved.'
        </p>

        <p class="meta-options">
            <label for="biling_type"><strong>'.esc_html__( 'Billing Type :','wprentals').' </strong></label><br />
            '.$invoice_period_saved.'
        </p>

        <p class="meta-options">
            <label for="item_id"><strong>'.esc_html__( 'Item Id (Listing or Package id)','wprentals').'</strong></label><br />
             '.wpestate_show_product_type ( esc_html(get_post_meta($post->ID, 'item_id', true)) ).'
        </p>

        <p class="meta-options">
            <label for="item_price"><strong>'.esc_html__( 'Item Price','wprentals').' </strong></label><br />
            '.  esc_html(get_post_meta($post->ID, 'item_price', true)).'
        </p> 
 
        <p class="meta-options">
            <label for="purchase_date"><strong>'.esc_html__( 'Purchase Date','wprentals').' </strong></label><br />
        ';        
        //    '.esc_html(get_post_meta($post->ID, 'purchase_date', true)).'    
    
        $time_unix = strtotime($purchase_date);
        echo gmdate( 'Y-m-d H:i:s', ( $time_unix+ ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ) );
 
        /*  if(is_numeric($purchase_date)){
           echo date('l jS \of F Y',$purchase_date);
        }else{
            print $purchase_date;
        }
        */
        print '
        </p>

        <p class="meta-options">
            <label for="buyer_id"><strong>'.esc_html__( 'User','wprentals').' </strong></label><br />';
            $user_id    =   esc_html(get_post_meta($post->ID, 'buyer_id', true));
            $user_info  =   get_userdata($user_id);
            print esc_html__( 'Username: ','wprentals').$user_info->user_login .' '.esc_html__( '/ user id','wprentals').' '. $user_id;
            print '
        </p>
        ';            
        if($txn_id!=''){
            print esc_html__( 'Paypal - Reccuring Payment ID: ','wprentals').$txn_id;
        }
        
         $details                    =   get_post_meta($invoice_id, 'renting_details', true);
    
    }
endif; // end   wpestate_invoice_details  



if( !function_exists('wpestate_super_invoice_details') ):
    function wpestate_super_invoice_details($invoice_id){
      
        $bookid             =   esc_html(get_post_meta($invoice_id, 'item_id', true));
        $booking_from_date  =   esc_html(get_post_meta($bookid, 'booking_from_date', true));
        $booking_prop       =   esc_html(get_post_meta($bookid, 'booking_id', true)); // property_id
        $booking_to_date    =   esc_html(get_post_meta($bookid, 'booking_to_date', true));   
        $booking_guests     =   floatval(get_post_meta($bookid, 'booking_guests', true));
        $extra_options      =   get_post_meta($bookid, 'extra_options',true );
       
   
        $extra_options_array=array();
        if($extra_options!=''){ 
            $extra_options_array    =   explode(',',$extra_options);
        }

        $manual_expenses    =   get_post_meta($invoice_id, 'manual_expense', true);
        $booking_array      =   wpestate_booking_price($booking_guests,$invoice_id, $booking_prop, $booking_from_date, $booking_to_date,$bookid,$extra_options_array,$manual_expenses);

        // and you might want to convert to integer
        
        $price_per_weekeend     =   floatval(get_post_meta($booking_prop, 'price_per_weekeend', true));
        $price_per_day          =   $booking_array['default_price'];
        $cleaning_fee           =   $booking_array['cleaning_fee'];
        $city_fee               =   $booking_array['city_fee'];
        $total_price            =   get_post_meta($invoice_id, 'item_price', true);
        $default_price          =   $booking_array['default_price'];
        $wp_estate_book_down    =   get_post_meta($invoice_id, 'invoice_percent', true);

        $depozit            =   $depozit_paid               =      ( get_post_meta ( $invoice_id, 'depozit_paid', true) );
        $balance            =   0;
        $balance            =   $total_price-$depozit;
        $depozit_show       =   '';
        $balance_show       =   '';
          
        $currency                   =   esc_html( get_post_meta($invoice_id, 'invoice_currency',true) );
        $where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $details                    =   get_post_meta($invoice_id, 'renting_details', true);
        $invoice_status             =   esc_html  ( get_post_meta ( $invoice_id, 'invoice_status', true) );
      
        
        $price_show                 =   wpestate_show_price_booking_for_invoice($default_price,$currency,$where_currency,0,1);
        $price_per_weekeend_show    =   wpestate_show_price_booking_for_invoice($price_per_weekeend,$currency,$where_currency,0,1); 
        $total_price_show           =   wpestate_show_price_booking_for_invoice($total_price,$currency,$where_currency,0,1);
        $depozit_show               =   wpestate_show_price_booking_for_invoice($depozit,$currency,$where_currency,0,1);
        $balance_show               =   wpestate_show_price_booking_for_invoice($balance,$currency,$where_currency,0,1);
        $guest_price                =   wpestate_show_price_booking_for_invoice($booking_array['extra_price_per_guest'],$currency,$where_currency,1,1); 
            
        
        $invoice_saved      =   esc_html(get_post_meta($invoice_id, 'invoice_type', true));
       
        wpestate_print_create_form_invoice ($guest_price,$booking_guests,$invoice_id, $invoice_saved, $booking_from_date, $booking_to_date, $booking_array, $price_show, $details, $currency, $where_currency, $total_price, $total_price_show, $depozit_show, $balance_show,$booking_prop,$price_per_weekeend_show);
    }

endif;


if( !function_exists('wpestate_print_create_form_invoice') ):
    function wpestate_print_create_form_invoice ($guest_price,$booking_guests,$invoice_id, $invoice_saved, $booking_from_date, $booking_to_date, $booking_array, $price_show, $details, $currency, $where_currency, $total_price, $total_price_show, $depozit_show, $balance_show,$booking_prop,$price_per_weekeend_show){
        
        $invoice_deposit_tobe   =   floatval  ( get_post_meta ( $invoice_id, 'depozit_to_be_paid', true) );
        $depozit_show           =   wpestate_show_price_booking_for_invoice($invoice_deposit_tobe,$currency,$where_currency,0,1);
      //  $balance                =   $total_price-$invoice_deposit_tobe;
        $balance                =   get_post_meta ( $invoice_id, 'balance', true);
        $balance_show           =   wpestate_show_price_booking_for_invoice($balance,$currency,$where_currency,0,1);     
        $current_user           =   wp_get_current_user();
        $userID                 =   $current_user->ID;
        $owner_see              =   0;
        
        if( wpsestate_get_author($booking_prop) == $userID ){
            $total_label    =   esc_html__( 'User Pays','wprentals');
            $owner_see      =   1;        
        }else{
            $total_label    =   esc_html__( 'You Pay','wprentals');
        }
   

      
        print '              
           <div class="create_invoice_form">';
            
        
                if($invoice_id!=0){
                    print '<h3>'.esc_html__( 'Invoice INV','wprentals').$invoice_id.'</h3>';
                }
                print '
                <div class="invoice_table">';
                    if($invoice_id!=0){
                        print '<div id="print_invoice" data-invoice_id="'.$invoice_id.'" ><i class="fas fa-print" aria-hidden="true" ></i></div>';
                    } 
                    print'
                    <div class="invoice_data">';
                    $extra_price_per_guest=   wpestate_show_price_booking($booking_array['extra_price_per_guest'],$currency,$where_currency,1);
       
                        if($invoice_saved=='Reservation fee' || $invoice_saved==esc_html__('Reservation fee','wprentals') ){
                            print'
                            <span class="date_interval show_invoice_period"><span class="invoice_data_legend">'.esc_html__( 'Period','wprentals').' : </span>'.wpestate_convert_dateformat_reverse($booking_from_date).' '.esc_html__( 'to','wprentals').' '.wpestate_convert_dateformat_reverse($booking_to_date).'</span>
                            <span class="date_duration show_invoice_no_nights"><span class="invoice_data_legend">'.esc_html__( 'No of nights','wprentals').': </span>'.$booking_array['numberDays'].'</span>
                            <span class="date_duration show_invoice_guests"><span class="invoice_data_legend">'.esc_html__( 'Guests','wprentals').': </span>'.$booking_guests.'</span>';

                            if($booking_array['price_per_guest_from_one']==1){
                                print'<span class="date_duration show_invoice_price_per_quest "><span class="invoice_data_legend">'.esc_html__( 'Price per Guest','wprentals').': </span>'; 
                                if( $booking_array['custom_period_quest'] == 1){
                                  _e('custom price','wprentals');
                                }else{
                                  print $extra_price_per_guest;
                                }
                                print'</span>';
                            }else{
                                print '<span class="date_duration show_invoice_price_per_night"><span class="invoice_data_legend">'.esc_html__( 'Price per night','wprentals').': </span>';
                          
                                print $price_show;
                                if($booking_array['has_custom']){
                                    print ', '.esc_html__('has custom price','wprentals');
                                }
                                if($booking_array['cover_weekend']){
                                    print ', '.esc_html__('has weekend price of','wprentals').' '.$price_per_weekeend_show;
                                }
                            
                            print '</span>';
                            }
                            
                            if($booking_array['has_custom'] || $booking_array['custom_period_quest'] == 1 ){
                                if( is_array($booking_array['custom_price_array']) ){
                                    print '<span class="invoice_data_legend show_invoice_price_details">'.__('Price details:','wprentals').'</span>';
                                    foreach($booking_array['custom_price_array'] as $date=>$price){
                                        $day_price = wpestate_show_price_booking_for_invoice($price,$currency,$where_currency,1,1); 
                                        print '<span class="price_custom_explained show_invoice_price_details">'.__('on','wprentals').' '.wpestate_convert_dateformat_reverse(date("Y-m-d",$date)).' '.__('price is','wprentals').' '.$day_price.'</span>';
                                    }
                                }
                            }
                        
                            
                            
                            
                            
                        }

                        print'    
                    </div>

                   <div class="invoice_details">
                        <div class="invoice_row header_legend">
                            <span class="inv_legend">'.esc_html__( 'Cost','wprentals').'</span>
                            <span class="inv_data">  '.esc_html__( 'Price','wprentals').'</span>
                            <span class="inv_exp">   '.esc_html__( 'Detail','wprentals').'</span>
                        </div>';


                        if (is_array($details)){   
                         
                            foreach($details as $detail){
                                if( $detail[1] != 0 ){
                                    print'<div class="invoice_row invoice_content">
                                        <span class="inv_legend">  '.$detail[0].'</span>
                                        <span class="inv_data">  '. wpestate_show_price_booking_for_invoice($detail[1],$currency,$where_currency,0,1).'</span>
                                        <span class="inv_exp"> ';
                                        if( trim($detail[0]) ==esc_html__('Security Depozit','wprentals') || trim($detail[0]) ==esc_html__('Security Deposit','wprentals')){ 
                                            esc_html_e('*refundable' ,'wprentals');
                                        }

                                        if( trim($detail[0]) ==esc_html__( 'Subtotal','wprentals') ){
                                            if($booking_array['price_per_guest_from_one']==1){
                                                if($booking_array['custom_period_quest']==1){
                                                    print $booking_array['count_days'].' '.esc_html__( 'nights','wprentals').' x '.$booking_array['curent_guest_no'].' '.esc_html__( 'guests','wprentals').' - '.esc_html__(" period with custom price per guest","wprentals");
                                                }else{
                                                   print  $extra_price_per_guest.' x '.$booking_array['count_days'].' '.esc_html__( 'nights','wprentals').' x '.$booking_array['curent_guest_no'].' '.esc_html__( 'guests','wprentals');
                                                
                                                }
                                            }else{
                                                print $booking_array['numberDays'].' '.esc_html__( 'nights','wprentals').' x ';
                                                if($booking_array['cover_weekend']){
                                                    echo esc_html__('has weekend price of','wprentals').' '.$price_per_weekeend_show;
                                                }else{
                                                    if ( $booking_array['has_custom'] !=0  ){
                                                        esc_html_e('custom price','wprentals');
                                                    }else{
                                                        print $price_show;
                                                    }
                                                }
                                            }
                                        }


                                        if($booking_array['custom_period_quest']==1){
                                            $new_guest_price=esc_html__("custom price","wprentals");
                                        }else{
                                            $new_guest_price=$guest_price.' '.esc_html__( 'per night','wprentals');
                                        }

                                        if(trim($detail[0])==esc_html__( 'Extra Guests','wprentals')){ 
                                            print $booking_array['numberDays'].' '.esc_html__( 'nights','wprentals').' x '.$booking_array['extra_guests'].' '.esc_html__('extra guests','wprentals').' x '.$new_guest_price;
                                        }

                                        if(isset($detail[2])){
                                            print $detail[2];
                                        }


                                    print'  </span>
                                       </div>';
                               
                                }//end if       if($detail[1]>0)
                           }
                        }else{
                            
                        } 
                       
//                        $manual_expenses = get_post_meta($invoice_id, 'manual_expense',true);
//                        if( is_array($manual_expenses) && !empty($manual_expenses)){
//                            foreach($manual_expenses as $key=>$expense){
//                                print'<div class="invoice_row invoice_content">
//                                    <span class="inv_legend">  '.$expense[0].'</span>
//                                    <span class="inv_data">  '. wpestate_show_price_booking_for_invoice($expense[1],$currency,$where_currency,0,1).'</span>
//                                    <span class="inv_exp"> </div>';
//                            }
//                        }
                        
                        // show Total row
                        print '  
                        <div class="invoice_row invoice_total invoice_create_print_invoice">
                            <span class="inv_legend inv_legend_total"><strong>'.$total_label.'</strong></span>
                            <span class="inv_data" id="total_amm" data-total="'.$total_price.'">'.$total_price_show.'</span>
                            <div class="deposit_show_wrapper total_inv_span"> ';

                            if($invoice_saved=='Reservation fee' || $invoice_saved==esc_html__('Reservation fee','wprentals')){
                                print'
                                <span class="inv_legend">'.esc_html__( 'Reservation Fee Required','wprentals').':</span> <span class="inv_depozit">'.$depozit_show.' </span></br>
                                <span class="inv_legend">'.esc_html__( 'Balance','wprentals').':</span> <span class="inv_depozit">'.$balance_show.'</span>';
                            }else{
                                print $invoice_saved;
                            }

                        print'
                        </div> ';
                           
                        
                        // show earnings for owner
                     
                        if( $owner_see==1 ){
                            wpestate_show_youearn( $booking_array ,$booking_prop,$total_price,$currency,$where_currency);
                        }
                           
                          print'</div>
                       </div> 
               </div>';
    }
endif;    

if( !function_exists('wpestate_show_youearn') ):
    function wpestate_show_youearn( $booking_array ,$booking_prop,$total_price,$currency,$where_currency) {
        $taxes                  =   0;
        $cleaning_fee_deduct    =   0;
        $city_fee_deduct        =   0;
        $service_fee            =   0;
        if(isset($booking_array['city_fee'])){
            $city_fee_deduct=$booking_array['city_fee'];
        }
        if(isset($booking_array['cleaning_fee'])){
            $cleaning_fee_deduct=$booking_array['cleaning_fee'];
        }

      
    
        $taxes_show          =      wpestate_show_price_booking_for_invoice($booking_array['taxes'],$currency,$where_currency,0,1);
        $you_earn_show       =      wpestate_show_price_booking_for_invoice($booking_array['youearned'],$currency,$where_currency,0,1);
        $service_fee_show    =      wpestate_show_price_booking_for_invoice($booking_array['service_fee'],$currency,$where_currency,0,1);
        print'  
        <div class="invoice_row invoice_totalx invoice_total_generate_invoice">
            <span class="inv_legend"><strong>'.esc_html__( 'You Earn','wprentals').'</strong></span>
            <span class="inv_data" id="youearned" data-youearned="'.$booking_array['youearned'].'"><strong>'.$you_earn_show.'</strong></span>


            <div class="invoice_explantions">'.esc_html__('we deduct security deposit, city fees, cleaning fees and website service fee','wprentals').'</div>

            <span class="total_inv_span ">
                <span class="inv_legend show_invoice_service_fee">'.esc_html__( 'Service Fee','wprentals').':</span>
                <span id="inv_depozit show_invoice_service_fee" >'.$service_fee_show.'</span>

                <div style="width:100%"></div>

                <span class="inv_legend show_invoice_taxes">'.esc_html__( 'Taxes','wprentals').':</span>
                <span id="inv_balance show_invoice_taxes">'.$taxes_show.'</span>
            </span>    

            <div class="invoice_explantions show_invoice_taxes">'.esc_html__('*taxes are included in your earnings and you are responsible for paying these taxes','wprentals').'</div>
        </div>';
    }
endif;


/////////////////////////////////////////////////////////////////////////////////////
/// populate the invoice list with extra columns
/////////////////////////////////////////////////////////////////////////////////////

add_filter( 'manage_edit-wpestate_invoice_columns', 'wpestate_invoice_my_columns' );

if( !function_exists('wpestate_invoice_my_columns') ):
    function wpestate_invoice_my_columns( $columns ) {
        $slice=array_slice($columns,2,2);
        unset( $columns['comments'] );
        unset( $slice['comments'] );
        $splice=array_splice($columns, 2);   
        $columns['invoice_price']   = esc_html__( 'Price','wprentals');
        $columns['invoice_for']     = esc_html__( 'Billing For','wprentals');
        $columns['invoice_type']    = esc_html__( 'Invoice Type','wprentals');
        $columns['invoice_status']    = esc_html__( 'Status','wprentals');
        $columns['invoice_user']    = esc_html__( 'Purchased by User','wprentals');
        return  array_merge($columns,array_reverse($slice));
    }
endif; // end   wpestate_invoice_my_columns  


add_action( 'manage_posts_custom_column', 'wpestate_invoice_populate_columns' );

if( !function_exists('wpestate_invoice_populate_columns') ):
    function wpestate_invoice_populate_columns( $column ) {
         $the_id=get_the_ID();
         if ( 'invoice_price' == $column ) {
            echo get_post_meta($the_id, 'item_price', true);
        } 

        if ( 'invoice_for' == $column ) {
             echo get_post_meta($the_id, 'invoice_type', true);
        } 

        if ( 'invoice_type' == $column ) {
            echo get_post_meta($the_id, 'biling_type', true);
        }
        if ( 'invoice_status' == $column ) {
            $status =  esc_html(get_post_meta($the_id, 'invoice_status', true));
            if ($status === 'confirmed'){
                print $status.' / '.esc_html__( 'paid','wprentals');
            }else{
                print $status;
            }
        }
        if ( 'invoice_user' == $column ) {
             $user_id= get_post_meta($the_id, 'buyer_id', true);
             $user_info = get_userdata($user_id);
             print $user_info->user_login;
        }

    }
endif; // end   wpestate_invoice_populate_columns  


add_filter( 'manage_edit-wpestate_invoice_sortable_columns', 'wpestate_invoice_sort_me' );

if( !function_exists('wpestate_invoice_sort_me') ):
    function wpestate_invoice_sort_me( $columns ) {
        $columns['invoice_price']   = 'invoice_price';
        $columns['invoice_user']    = 'invoice_user';
        $columns['invoice_for']     = 'invoice_for';
        $columns['invoice_type']    = 'invoice_type';
        return $columns;
    }
endif; // end   wpestate_invoice_sort_me  






/////////////////////////////////////////////////////////////////////////////////////
/// insert invoice 
/////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_insert_invoice') ):
    function wpestate_insert_invoice($billing_for,$type,$pack_id,$date,$user_id,$is_featured,$is_upgrade,$paypal_tax_id){   
        $post = array(
                   'post_title'     =>  'Invoice ',
                   'post_status'    =>  'publish', 
                   'post_type'      =>  'wpestate_invoice'
               );
        $post_id =  wp_insert_post($post ); 


        if($type==2){
            $type=esc_html__( 'Recurring','wprentals');
        }else{
            $type=esc_html__( 'One Time','wprentals');
        }

        $price_submission               =   floatval( get_option('wp_estate_price_submission','') );
        $price_featured_submission      =   floatval( get_option('wp_estate_price_featured_submission','') );

        if($billing_for=='Package'){
            $price= get_post_meta($pack_id, 'pack_price', true);
        }else{
            if($is_upgrade==1){
                $price=$price_featured_submission;
            }else{
                if($is_featured==1){
                    $price=$price_featured_submission+$price_submission;
                }else{
                    $price=$price_submission;
                }
            }


        }

        update_post_meta($post_id, 'invoice_type', $billing_for);   
        update_post_meta($post_id, 'biling_type', $type);
        update_post_meta($post_id, 'item_id', $pack_id);
        update_post_meta($post_id, 'item_price',$price);
        update_post_meta($post_id, 'purchase_date', $date);
        update_post_meta($post_id, 'buyer_id', $user_id);
        update_post_meta($post_id, 'txn_id', $paypal_tax_id);
        
        //$submission_curency_status = esc_html( get_option('wp_estate_submission_curency','') );
        $submission_curency_status = wpestate_curency_submission_pick();
        update_post_meta($post_id, 'invoice_currency', $submission_curency_status);
        
        $my_post = array(
           'ID'             => $post_id,
           'post_title'     => 'Invoice '.$post_id,
        );
        wp_update_post( $my_post );
        return $post_id;
    }
endif; // end   wpestate_insert_invoice  
?>