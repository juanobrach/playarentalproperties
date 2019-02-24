/*global $, jQuery, ajaxcalls_vars, document, control_vars, window, map, setTimeout, Modernizr, property_vars*/
jQuery(window).scroll(function ($) {
    "use strict";
    var scroll = jQuery(window).scrollTop();
    if (scroll >= 400) {
        if (!Modernizr.mq('only all and (max-width: 1180px)')) {
            jQuery(".property_menu_wrapper_hidden").fadeIn(400);
            jQuery(".property_menu_wrapper").fadeOut(400);
        }
    } else {
        jQuery(".property_menu_wrapper_hidden").fadeOut(400);
        jQuery(".property_menu_wrapper").fadeIn(400);
    }
});





jQuery(document).ready(function ($) {
    "use strict";
    redo_listing_sidebar();
    var today, booking_error;
    booking_error = 0;
    today = new Date();
    wpestare_booking_retrive_cookies();

    if( $('#listing_description').outerHeight() > 169 ){
        $('#view_more_desc').show();
    }
    
    //180
    var sidebar_padding=0;
    
    $('#view_more_desc').click(function(event){
       
        
        var new_margin = 0;
        if( $(this).hasClass('lessismore') ){
         
            $(this).text(property_vars.viewmore).removeClass('lessismore');
            $('#listing_description .panel-body').css('max-height','129px').css('overflow','hidden');
          
            if ( !jQuery('#primary').hasClass('listing_type_1') ){
                $('#primary').css('margin-top',sidebar_padding);
            }
           
           
        }else{
            sidebar_padding=$('.listingsidebar').css('margin-top');
            
            $(this).text(property_vars.viewless).addClass('lessismore');
            $('#listing_description .panel-body').css('max-height','100%').css('overflow','initial');
            
            if ( !jQuery('#primary').hasClass('listing_type_1') ){
                new_margin = $('.property_header').outerHeight() - 390;
                new_margin = 180-new_margin;

                if(new_margin <180){
                    $('#primary').css('margin-top',new_margin+'px');
                }else{
                    $('#primary').css('margin-top','0px');
                }
            }
          
        }
        
        
    });
    
    
    ////////////////////////////////////////////////////////////////////////////
    /// tooltip property
    ////////////////////////////////////////////////////////////////////////////     
    $('#listing_main_image_photo').bind('mousemove', function (e) {
        $('#tooltip-pic').css({'top': e.pageY, 'left': e.pageX, 'z-index': '1'});
    });
    setTimeout(function () {
        $('#tooltip-pic').fadeOut("fast");
    }, 10000);
    /////////////////////////////////////////////////////////////////////////////////////////
    // booking form calendars
    /////////////////////////////////////////////////////////////////////////////////////////
    function show_booking_costs() {
        var guest_fromone, guest_no, fromdate, todate, property_id, ajaxurl;
        ajaxurl             =   control_vars.admin_url + 'admin-ajax.php';
        property_id         =   $("#listing_edit").val();
        
        fromdate            =   $("#start_date").val();
        fromdate            =   wpestate_convert_selected_days(fromdate);
        
        todate              =   $("#end_date").val();
        todate              =   wpestate_convert_selected_days(todate);
        
        guest_no            =   parseInt( jQuery('#booking_guest_no_wrapper').attr('data-value'),10);
        
        $('.cost_row_extra input').each(function(){
            $(this).prop("checked", false);
        })
                
                
        if (fromdate === '' || todate === '') {
            jQuery('#show_cost_form').remove();
            return;
        }

       
        guest_fromone       =   parseInt( jQuery('#submit_booking_front').attr('data-guestfromone'),10);  
        if (document.getElementById('submit_booking_front_instant')) {
            guest_fromone       =   parseInt( jQuery('#submit_booking_front_instant').attr('data-guestfromone'),10);  
        }
         
         
         
        if( isNaN(guest_fromone) ){
            guest_fromone=0;
        } 
              
        if(isNaN(guest_no)){
            guest_no=0;
        }
 
              
        if(guest_fromone===1 && guest_no<1 ){
            return;
        }
        
         
        jQuery('#booking_form_request_mess').empty().hide();
        if(fromdate>todate && todate!=='' ){
            jQuery('#booking_form_request_mess').show().empty().append(property_vars.nostart)
            jQuery('#show_cost_form').remove(); 
            return;
        }
        
     
             
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'            :   'wpestate_ajax_show_booking_costs',
                'fromdate'          :   fromdate,
                'todate'            :   todate,
                'property_id'       :   property_id,
                'guest_no'          :   guest_no,
                'guest_fromone'     :   guest_fromone
            },
            success: function (data) {
                jQuery('#show_cost_form,.cost_row_instant').remove();
                jQuery('#add_costs_here').before(data);
                redo_listing_sidebar();
            },
            error: function (errorThrown) {
             
            }
        });
    }
   
    
    
    
    
  
    
    var booking_started=0;
    $('#end_date').change(function () {
        var prop_id=jQuery('#listing_edit').val();
        wpestate_setCookie('booking_prop_id_cookie',  prop_id , 1)
        wpestate_setCookie('booking_end_date_cookie',  $('#end_date').val() , 1)
        booking_started=1;
        show_booking_costs();
    });
    
    $('#start_date').change(function () {
        var prop_id=jQuery('#listing_edit').val();
        wpestate_setCookie('booking_prop_id_cookie',  prop_id , 1)
        wpestate_setCookie('booking_start_date_cookie',  $('#start_date').val() , 1)
        if( booking_started===1){
            show_booking_costs();
        }
    });
    
    $('#booking_guest_no_wrapper_list li').click(function (){
        var prop_id=jQuery('#listing_edit').val();
        wpestate_setCookie('booking_prop_id_cookie',  prop_id , 1)
        var booking_guest_no    =   parseInt( jQuery('#booking_guest_no_wrapper').attr('data-value') ); 
        wpestate_setCookie('booking_guest_cookie',  booking_guest_no , 1)
        if( booking_started===1){
            show_booking_costs();
        }
    });
    
    
    function wpestare_booking_retrive_cookies(){
        var booking_guest_cookie        =   wpestate_getCookie( "booking_guest_cookie");
        var booking_start_date_cookie   =   wpestate_getCookie("booking_start_date_cookie");
        var booking_end_date_cookie     =   wpestate_getCookie("booking_end_date_cookie");
        var booking_prop_id             =   wpestate_getCookie("booking_prop_id_cookie");
        var prop_id                     =   jQuery('#listing_edit').val();


        if ( prop_id === booking_prop_id &&  property_vars.logged_in==="yes" ){
            if(booking_start_date_cookie!==''){
                jQuery('#start_date').val(booking_start_date_cookie);
            }

            if(booking_end_date_cookie!==''){
                jQuery('#end_date').val(booking_end_date_cookie);
            }

            if(booking_guest_cookie!==''){
                jQuery('#booking_guest_no_wrapper').attr('data-value',booking_guest_cookie);
                jQuery('#booking_guest_no_wrapper .text_selection').html(booking_guest_cookie+' '+property_vars.guests);
            }


            if(booking_start_date_cookie!=='' && booking_end_date_cookie!=='' && booking_guest_cookie!==''){
              
                show_booking_costs();
            
                // jQuery('#booking_guest_no_wrapper_list li').trigger('click');
            }

        }


    }




    
    $('#booking_form_request li').click(function (event){
        event.preventDefault();
        var guest_fromone, guest_overload;
        
        guest_overload      =   parseInt(jQuery('#submit_booking_front').attr('data-overload'),10);
        guest_fromone       =   parseInt( jQuery('#submit_booking_front').attr('data-guestfromone'),10);
        if (document.getElementById('submit_booking_front_instant')) {
            guest_overload      =   parseInt(jQuery('#submit_booking_front_instant').attr('data-overload'),10);
            guest_fromone       =   parseInt( jQuery('#submit_booking_front_instant').attr('data-guestfromone'),10);
        }
        if( ( guest_overload===1 &&  booking_started===1) || guest_fromone===1 ){
            show_booking_costs();
        }
    });
    
    

    $("#start_date,#end_date").change(function (event) {
        $(this).parent().removeClass('calendar_icon');
    });

    /////////////////////////////////////////////////////////////////////////////////////////
    // contact host
    /////////////////////////////////////////////////////////////////////////////////////////
    function wpestate_show_contact_owner_form(booking_id, agent_id) {
        var  ajaxurl;
        ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
        
        
        jQuery('#contact_owner_modal').modal();
        enable_actions_modal_contact();
//       if( property_vars.logged_in==="yes" ){ }else{
//            login_modal_type=2;
//            $('#topbarlogin').trigger('click');

//        
    }


    $('#contact_host,#contact_me_long').click(function () {
        var booking_id, agent_id;
        agent_id    =   0;
        booking_id  =   $(this).attr('data-postid');
        wpestate_show_contact_owner_form(booking_id, agent_id);
    });

    $('#contact_me_long_owner').click(function () {
        var agent_id, booking_id;
        booking_id =   0;
        agent_id  =   $(this).attr('data-postid');
        wpestate_show_contact_owner_form(booking_id, agent_id);
    });

    function enable_actions_modal_contact() {
        jQuery('#contact_owner_modal').on('hidden.bs.modal', function (e) {
            jQuery('#contact_owner_modal').hide();
        });
        var today =new Date().getTime();

        $("#booking_from_date").datepicker({
          //  dateFormat : "yy-mm-dd",
            minDate: today
        }, jQuery.datepicker.regional[control_vars.datepick_lang]);

        $("#booking_from_date").change(function () {
            var  prev_date = new Date($('#booking_from_date').val());
            prev_date =wpestate_UTC_addDays( jQuery('#booking_from_date' ).val(),0 );
            
            jQuery("#booking_to_date").datepicker("destroy");
            jQuery("#booking_to_date").datepicker({
           //     dateFormat : "yy-mm-dd",
                minDate: prev_date
            }, jQuery.datepicker.regional[control_vars.datepick_lang]);
        });
        
        $("#booking_from_date").datepicker('widget').wrap('<div class="ll-skin-melon"/>'); 
 
        $("#booking_to_date").datepicker({
         //   dateFormat : "yy-mm-dd",
            minDate: today
        }, jQuery.datepicker.regional[control_vars.datepick_lang]);

        $("#booking_to_date").datepicker('widget').wrap('<div class="ll-skin-melon"/>'); 

        $("#booking_from_date,#booking_to_date").change(function (event) {
            $(this).parent().removeClass('calendar_icon');
        });

        $('#submit_mess_front').click(function (event) {
            event.preventDefault();
            var ajaxurl, subject, booking_from_date, booking_to_date, booking_guest_no, message, nonce, agent_property_id, agent_id;
            ajaxurl              =   control_vars.admin_url + 'admin-ajax.php';
            booking_from_date       =   $("#booking_from_date").val();
            booking_to_date         =   $("#booking_to_date").val();
            booking_guest_no        =   $("#booking_guest_no").val();
            message                 =   $("#booking_mes_mess").val();
            agent_property_id       =   $("#agent_property_id").val();
            agent_id                =   $('#agent_id').val();
            nonce                   =   $("#security-register-mess-front").val();

            var contact_u_email     =   $("#contact_u_email").val();
            var contact_u_name      =   $("#contact_u_name").val();

            if (subject === '' || message === '') {
                $("#booking_form_request_mess_modal").empty().append(property_vars.plsfill);
                return;
            }
            if( property_vars.logged_in!=="yes" && ( contact_u_email === '' || contact_u_name === '')) {
                $("#booking_form_request_mess_modal").empty().append(property_vars.plsfill);
                return;
            }

       

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'            :   'wpestate_mess_front_end',
                    'message'           :   message,
                    'booking_guest_no'  :   booking_guest_no,
                    'booking_from_date' :   booking_from_date,
                    'booking_to_date'   :   booking_to_date,
                    'agent_property_id' :   agent_property_id,
                    'agent_id'          :   agent_id,
                    'contact_u_email'   :   contact_u_email,
                    'contact_u_name'    :   contact_u_name,
                    'security-register' :   nonce
                },
                success: function (data) {
                 
                    $("#booking_form_request_mess_modal").empty().append(data);
                    setTimeout(function () {
                        $('#contact_owner_modal').modal('hide');
                      
                            // reset contact form
                            $("#booking_form_request_mess_modal").empty()
                            $("#contact_u_email").val('');
                            $("#contact_u_name").val('');
                            $("#booking_from_date").val('');
                            $("#booking_to_date").val('');
                            $("#booking_guest_no").val('1');
                            $("#booking_mes_mess").val('');
                            $('#submit_mess_front').unbind('click')
                    
                    
                    }, 2000);
                    
                  
                    
                },
                error: function (errorThrown) {
                  
                }

            });
        });
    }


    /////////////////////////////////////////////////////////////////////////////////////////
    // extra expenses front
    /////////////////////////////////////////////////////////////////////////////////////////

    $('.cost_row_extra input').click(function(){
        var key_to_add,row_to_add,total_value,value_to_add,value_how,value_name,parent,fromdate,todate,listing_edit,booking_guest_no,cost_value_show;
          
        parent= $(this).parent().parent();
        value_to_add    =   parseFloat( parent.attr('data-value_add') );
        value_to_add    =   parseFloat( wpestate_booking_form_currency_convert(value_to_add) );
              
        value_how           =   parseFloat ( parent.attr('data-value_how') );
        value_name          =   parent.attr('data-value_name');
        key_to_add          =   jQuery(this).attr('data-key');
        fromdate            =   wpestate_convert_selected_days( jQuery("#start_date").val() );
        todate              =   wpestate_convert_selected_days( jQuery("#end_date").val() );
        listing_edit        =   jQuery('#listing_edit').val();
        booking_guest_no    =   parseInt( jQuery('#booking_guest_no_wrapper').attr('data-value') ); 
        cost_value_show     =   parent.find('.cost_value_show').text();
        var firstDate   =   new Date(fromdate);
        var secondDate  =   new Date(todate);
        var oneDay      =   24*60*60*1000;
        var diffDays    =   Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)))
   
        var total_curent    =   parseFloat( $('#total_cost_row .cost_value').attr('data_total_price') );
        total_curent        =   parseFloat( wpestate_booking_form_currency_convert(total_curent) );
        
        if(booking_guest_no === 0 || isNaN(booking_guest_no)){
            booking_guest_no=1;
        }
        
        
        if( ($(this).is(":checked")) ){
            
            if(value_how===0){
                total_value = value_to_add;
            }else if( value_how === 1 ){
                total_value = value_to_add * diffDays;
            }else if( value_how === 2 ){
                total_value = value_to_add * booking_guest_no;
            }else if( value_how === 3 ){
                total_value = value_to_add * diffDays*booking_guest_no;
            }
            
    
            
            row_to_add='<div class="cost_row" id="'+estate_makeSafeForCSS(value_name)+'" data-added="'+total_value+'"><div class="cost_explanation">'+value_name+'</div><div class="cost_value">'+estate_format_number_with_currency( total_value.toFixed(2) )+'</div></div>';
            $('#total_cost_row').before(row_to_add);
            
            var new_early_bird_before_convert;
            var new_early_bird  =   parseFloat( $('#early_bird_discount').attr('data-early-bird') );
            new_early_bird      =   parseFloat( wpestate_booking_form_currency_convert(new_early_bird) );
             
            if( isNaN(new_early_bird) ||new_early_bird === 0){
                new_early_bird=0;
            }
            new_early_bird.toFixed(2);
            
              
           
          
            
            
          
            total_curent    =   total_curent    +   new_early_bird;
            total_curent    =   total_curent    +   total_value;
            if(new_early_bird !==0){
                new_early_bird  =   new_early_bird  +    total_value * property_vars.early_discount/100;
              
                var new_early_bird_before_convert =  wpestate_booking_form_currency_convert_back(new_early_bird);
                new_early_bird.toFixed(2);
            }
            
            total_curent    =   total_curent    -   new_early_bird;
            total_curent    =   total_curent.toFixed(2);
           
            var  total_curent_deposit=total_curent;
            if(control_vars.include_expeses==='no'){
                var cleaning_fee=parseFloat ( $('.cleaning_fee_value').attr('data_cleaning_fee') );
                cleaning_fee.toFixed(2);
                var city_fee=parseFloat ( $('.city_fee_value').attr('data_city_fee') );
                city_fee.toFixed(2);
                total_curent_deposit=total_curent_deposit-cleaning_fee-city_fee;
                total_curent_deposit.toFixed(2);
            }
            
            
            
            $('#total_cost_row .cost_value').text( estate_format_number_with_currency( total_curent ) );
            var total_curent_before_convert = wpestate_booking_form_currency_convert_back(total_curent);
            
            $('#total_cost_row .cost_value').attr('data_total_price',total_curent_before_convert);
             
            var new_depozit =   wpestate_instant_book_depozit(total_curent_deposit);
            var new_balance =   total_curent-new_depozit;
         
    
          
            $('.instant_depozit_value').text(estate_format_number_with_currency( new_depozit.toFixed(2) ) );
            $('.instant_balance_value').text(estate_format_number_with_currency( new_balance.toFixed(2) ) );
            $('#early_bird_discount').text(estate_format_number_with_currency( new_early_bird.toFixed(2) ) );
            $('#early_bird_discount').attr('data-early-bird',new_early_bird_before_convert);
         
         
        } else{
            value_name           =  estate_makeSafeForCSS(value_name);
            var remove_row_value =  parseFloat( $('#'+value_name).attr('data-added') );
           
              
            $('#'+value_name).remove();
            
            var new_early_bird =   parseFloat( $('#early_bird_discount').attr('data-early-bird') );
            if( isNaN(new_early_bird) ||new_early_bird === 0){
                new_early_bird=0;
            }
            var new_early_bird_before_convert =  wpestate_booking_form_currency_convert(new_early_bird);
            new_early_bird.toFixed(2);

            total_curent    =   total_curent    +   new_early_bird_before_convert; 
            total_curent    =   total_curent    -   remove_row_value;
            
            if(new_early_bird !==0){
                new_early_bird  =   new_early_bird_before_convert  -   remove_row_value * property_vars.early_discount/100;
                new_early_bird_before_convert =  wpestate_booking_form_currency_convert_back(new_early_bird);
                new_early_bird.toFixed(2);
             
            }
            
          
            total_curent    =   total_curent    -   new_early_bird;
           
             
            total_curent = total_curent.toFixed(2);
            
             var  total_curent_deposit=total_curent;
            if(control_vars.include_expeses==='no'){
                var cleaning_fee=parseFloat ( $('.cleaning_fee_value').attr('data_cleaning_fee') );
                cleaning_fee.toFixed(2);
                var city_fee=parseFloat ( $('.city_fee_value').attr('data_city_fee') );
                city_fee.toFixed(2);
                total_curent_deposit=total_curent_deposit-cleaning_fee-city_fee;
                total_curent_deposit.toFixed(2);
            }
            
            
            $('#total_cost_row .cost_value').text( estate_format_number_with_currency (total_curent) );
            var total_curent_before_convert = wpestate_booking_form_currency_convert_back(total_curent);
            $('#total_cost_row .cost_value').attr('data_total_price',total_curent_before_convert);
            
            var new_depozit =   wpestate_instant_book_depozit(total_curent_deposit);
            var new_balance =   total_curent-new_depozit;
             
            $('.instant_depozit_value').text(estate_format_number_with_currency(new_depozit) );
            $('.instant_balance_value').text(estate_format_number_with_currency(new_balance) );
            $('#early_bird_discount').text(estate_format_number_with_currency(new_early_bird) );
            $('#early_bird_discount').attr('data-early-bird',new_early_bird_before_convert);
        }
        redo_listing_sidebar();
        
    });


    function wpestate_instant_book_depozit(total_price){
        var deposit=0;
       
    
        if (  control_vars.wp_estate_book_down_fixed_fee === '0') {

            if(control_vars.wp_estate_book_down === '' || control_vars.wp_estate_book_down === '0'){
                deposit                =   0;
            }else{
                deposit                =  control_vars.wp_estate_book_down*total_price/100;
            }
        }else{
            deposit = control_vars.wp_estate_book_down_fixed_fee;
        }
        return deposit;
       
    }



    
function wpestate_booking_form_currency_convert(display_price){
    var return_price;
    return_price ='';
  
    
    if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) { // if we have custom curency
        return_price =     ( display_price * my_custom_curr_coef) ;
        return return_price;
    }else{
        return display_price;
    }   
     
        

}

    
function wpestate_booking_form_currency_convert_back(display_price){
    var return_price;
    return_price ='';
 
    if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) { // if we have custom curency
        return_price =     ( display_price / my_custom_curr_coef) ;
        return return_price;
    }else{
        return display_price;
    }   
     
        

}






    /////////////////////////////////////////////////////////////////////////////////////////
    // submit booking front
    /////////////////////////////////////////////////////////////////////////////////////////
    $('#submit_booking_front,#submit_booking_front_instant').click(function (event) {
        event.preventDefault();
        var scroll_val =$('#booking_form_request').offset().top -100;
        $("html, body").animate({ scrollTop: scroll_val}, 400);  
    
        if(property_vars.logged_in==="no"){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.notlog);
          
        }
        
        var guest_number, guest_overload,guestfromone,max_guest;
        if (!check_booking_form()  || booking_error === 1) {
            return;
        }
        
        guest_number = jQuery('#booking_guest_no_wrapper').attr('data-value');
        guest_number = parseInt(guest_number,10);
        
        if (isNaN(guest_number)){
            guest_number=0;
        }
       
       
        if(property_vars.rental_type==='1'){
            guest_number=1;
        }
        
        max_guest       =   parseInt  (jQuery('#submit_booking_front').attr('data-maxguest'),10);
        guest_overload  =   parseInt  (jQuery('#submit_booking_front').attr('data-overload'),10);
        guestfromone    =   parseInt  (jQuery('#submit_booking_front').attr('data-guestfromone'),10);
        
        
        if (document.getElementById('submit_booking_front_instant')) {
            max_guest       =   parseInt  (jQuery('#submit_booking_front_instant').attr('data-maxguest'),10);
            guest_overload  =   parseInt  (jQuery('#submit_booking_front_instant').attr('data-overload'),10);
            guestfromone    =   parseInt  (jQuery('#submit_booking_front_instant').attr('data-guestfromone'),10);
        }
        
        
        if (guestfromone===1 && guest_number < 1){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.noguest);
            return;
        }
       
       
        if(guest_number < 1){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.noguest);
            return;
        }
        
        if(guest_overload===0 && guest_number>max_guest){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.guestoverload+max_guest+' '+property_vars.guests);
            return;
        }
   
     
        
        if(property_vars.logged_in==="no"){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.notlog);
            login_modal_type=3;
            show_login_form(1, 0, 0);
         
        }else{
            $('#booking_form_request_mess').show().empty().removeClass('book_not_available').append(property_vars.sending);
            redo_listing_sidebar();
            check_booking_valability();
        }

    });


    function check_booking_form() {
        var book_from, book_to;
        book_from         =   $("#start_date").val();
        book_to           =   $("#end_date").val();
  
        if (book_from === '' || book_to === '') {
            $('#booking_form_request_mess').empty().addClass('book_not_available').show().append(property_vars.plsfill);
            return false;
        } else {
            return true;
        }
    }
    
   
       
   


/// end jquery
});


function estate_format_number_with_currency(number){
   
    if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1){
        if (my_custom_curr_cur_post === 'before') {    
            return  ( my_custom_curr_symbol2 ) +" "+number;
        }else{
            return number+" "+ ( my_custom_curr_symbol2 );
        }
    }else{
        if( control_vars.where_curency==='before'){
            return control_vars.curency+" "+number;
        }else{
            return number+" "+control_vars.curency;
        }   
    }
    
      
}


function estate_makeSafeForCSS(name) {
    return name.replace(/[^a-z0-9]/g, function(s) {
        var c = s.charCodeAt(0);
        if (c == 32) return '-';
        if (c >= 65 && c <= 90) return '_' + s.toLowerCase();
        return '__' + ('000' + c.toString(16)).slice(-4);
    });
}

function wpestate_setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function wpestate_getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
