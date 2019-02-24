/*global $, jQuery, document, window, tb_show, tb_remove ,admin_control_vars*/
jQuery(document).ready(function ($) {
   
    var icon_field;
//    $('.icp-auto').iconpicker();


    $('.input-group-addon').on('click',function(event){
          $('.iconpicker-items_wrapper').show();
          icon_field = $(this).parent().find('.icp-auto');
    });

    $('.iconpicker-items_wrapper_close').on('click',function(event){
        $('.iconpicker-items_wrapper').hide();
    });

    $('.iconpicker-item').on('click',function(event){
        event.preventDefault();
        var value = $(this).find('i').attr('class');
        icon_field.val(value);
        $('.iconpicker-items_wrapper').hide();
    });


    $('#icon_look_for').keydown(function(event){
        var look_for= $('#icon_look_for').val();
        var title, search_term;
        
        if(look_for!==''){
            $('.iconpicker-item').each(function() {
                title       = $(this).attr('title');
                search_term = $(this).attr('data-search-terms');
                
                if(typeof title==='undefined'){
                    title='';
                }
                if(typeof search_term==='undefined'){
                    search_term='';
                }
                
               
                if(title.indexOf(look_for) !== -1 || search_term.indexOf(look_for) !== -1){
                    $(this).show();
                }else{
                    $(this).hide();
                }
                
            });
        }else{
             $('.iconpicker-item').show();
        }
    });

        
    $('.css_modal_close').click(function(){
        $('#css_modal').hide();
    })
    
    $('#copycsscode').click(function(){
        $('#css_modal').html();
      
    })
    
    
    $('#activate_pack_reservation_fee').click(function(){
        var book_id, invoice_id,ajaxurl,type;
        
        book_id     = $(this).attr('data-item');
        invoice_id  = $(this).attr('data-invoice');
        type        = $(this).attr('data-type');
        ajaxurl     =   admin_control_vars.ajaxurl;
    
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
        data: {
            'action'        :   'wpestate_admin_activate_reservation_fee',
            'book_id'       :   book_id,
            'invoice_id'    :   invoice_id,
        },
        success: function (data) {  
            jQuery("#activate_pack_reservation_fee").remove();
            jQuery("#invnotpaid").remove(); 
        },
        error: function (errorThrown) {
        }
    });//end ajax  
        
    });
    
    
    
     $('#activate_pack_listing').click(function(){
        var item_id, invoice_id,ajaxurl,type;
        
        item_id     = $(this).attr('data-item');
        invoice_id  = $(this).attr('data-invoice');
        type        = $(this).attr('data-type');
        ajaxurl     =   admin_control_vars.ajaxurl;
    
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
        data: {
            'action'        :   'wpestate_activate_purchase_listing',
            'item_id'       :   item_id,
            'invoice_id'    :   invoice_id,
            'type'          :   type
           
        },
        success: function (data) {  
            jQuery("#activate_pack_listing").remove();
            jQuery("#invnotpaid").remove(); 
          
           
        },
        error: function (errorThrown) {}
    });//end ajax  
        
    });
    
     ///////////////////////////////////////////////////////////////////////////////
    /// activate purchase
    ///////////////////////////////////////////////////////////////////////////////
    
     $('#activate_pack').click(function(){
        var item_id, invoice_id,ajaxurl;
        
        item_id     =   $(this).attr('data-item');
        invoice_id  =   $(this).attr('data-invoice');
        ajaxurl     =   admin_control_vars.ajaxurl;
    
    
      
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
        data: {
            'action'        :   'wpestate_activate_purchase',
            'item_id'       :   item_id,
            'invoice_id'    :   invoice_id
           
        },
        success: function (data) {   
            jQuery("#activate_pack").remove();
            jQuery("#invnotpaid").remove(); 
           
        },
        error: function (errorThrown) {}
    });//end ajax  
        
    });
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    var formfield, imgurl;
     $('#splash_video_mp4_button').click(function () {
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");
            jQuery('#splash_video_mp4').val(mediaUrl);
            tb_remove();
        };
        return false;
    });


    $('#splash_video_webm_button').click(function () {
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");
            jQuery('#splash_video_webm').val(mediaUrl);
            tb_remove();
        };
        return false;
    });


    $('#splash_video_ogv_button').click(function () {
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");
            jQuery('#splash_video_ogv').val(mediaUrl);
            tb_remove();
        };
        return false;
    });
    
    
    
     $('#page_custom_video_button').click(function () {
        formfield = $('#page_custom_video').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");
        
            jQuery('#page_custom_video').val(mediaUrl);
            tb_remove();
        };
        return false;
    });
       $('#page_custom_video_webbm_button').click(function () {

        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");
            jQuery('#page_custom_video_webbm').val(mediaUrl);
            tb_remove();
        };
        return false;
    });
    
    $('#page_custom_video_ogv_button').click(function () {

        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            var mediaUrl = jQuery(html).attr("href");
            jQuery('#page_custom_video_ogv').val(mediaUrl);
            tb_remove();
        };
        return false;
    });
    
    
    $('#page_custom_image_button').click(function () {
        formfield = $('#page_custom_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#page_custom_image').val(imgurl);
            tb_remove();
        };
        return false;
    });

    $('.category_featured_image_button').click(function () {
        var parent = $(this).parent();
        formfield  = parent.find('#category_featured_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            parent.find('#category_featured_image').val(imgurl);
            var theid = $('img', html).attr('class');
            var thenum = theid.match(/\d+$/)[0];
            parent.find('#category_attach_id').val(thenum);
            tb_remove();
        };
        return false;
    });
    
    $('.category_icon_image_button').click(function () {
        var parent = $(this).parent();
        formfield  = parent.find('#category_icon_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            parent.find('#category_icon_image').val(imgurl);
            var theid = $('img', html).attr('class');
            var thenum = theid.match(/\d+$/)[0];
            parent.find('#category_attach_id').val(thenum);
            tb_remove();
        };
        return false;
    });
    
//spash page options

    $('#spash_header_type').change(function(){
        var value = $(this).val();
        $('.splash_image_info,.splash_slider_info,.splash_video_info,#splash_slider_images.splash_slider_info').hide();
       
        if(value=='image'){
            $('.splash_image_info').show();
        }else  if(value=='video'){
            $('.splash_video_info').show();
        }else  if(value=='image slider'){
            $('.splash_slider_info').show();
            $('#splash_slider_images.splash_slider_info').css('display','inline-block');
        }
        
    });
    
    $('#spash_header_type').trigger('change');
    
    
     $('#splash_slider_gallery_button').on( 'click', function(event) {
        event.stopPropagation();
        event.preventDefault();
        var  metaBox = $('#splash_slider_images');
        var imgContainer = metaBox.find( '.splash_thumb_wrapepr');
                    
        var imgIdInput = metaBox.find( '#splash_slider_gallery').val();
      
                    
                 
        // Accepts an optional object hash to override default values.
        var frame = new wp.media.view.MediaFrame.Select({
                // Modal title
                title: 'Select Images',

                // Enable/disable multiple select
                multiple: true,

                // Library WordPress query arguments.
                library: {
                        order: 'DESC',

                        // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
                        // 'id', 'post__in', 'menuOrder' ]
                        orderby: 'id',

                        // mime type. e.g. 'image', 'image/jpeg'
                        type: 'image',



                        // Attached to a specific post (ID).
                        //uploadedTo: post_id
                },

                button: {
                        text: 'Set Image'
                }
        });

                // Fires after the frame markup has been built, but not appended to the DOM.
                // @see wp.media.view.Modal.attach()
                frame.on( 'ready', function() { } );

                // Fires when the frame's $el is appended to its DOM container.
                // @see media.view.Modal.attach()
                frame.on( 'attach', function() {} );

                // Fires when the modal opens (becomes visible).
                // @see media.view.Modal.open()
                frame.on( 'open', function() {} );

                // Fires when the modal closes via the escape key.
                // @see media.view.Modal.close()
                frame.on( 'escape', function() {} );

                // Fires when the modal closes.
                // @see media.view.Modal.close()
                frame.on( 'close', function() {} );

                // Fires when a user has selected attachment(s) and clicked the select button.
                // @see media.view.MediaFrame.Post.mainInsertToolbar()
                frame.on( 'select', function(arguments) {
                        var attachment = frame.state().get('selection').toJSON();


                        var arrayLength = attachment.length;
                        for (var i = 0; i < arrayLength; i++) {
                            imgIdInput = metaBox.find( '#splash_slider_gallery' ).val();
                            if(imgIdInput!==''){
                                imgIdInput=imgIdInput+",";
                            }
                            $( '#splash_slider_gallery' ).val(imgIdInput+attachment[i].id+",")
                            imgContainer.append( '<div class="uploaded_thumb" data-imageid="'+attachment[i].id+'">\n\
                                <img src="'+attachment[i].sizes.thumbnail.url+'" alt="" style="max-width:100%;"/>\n\
                               <a class="splash_attach_delete"><i class="far fa-trash-alt" aria-hidden="true"></i></span></div>' );

                        }

                        //$( '#image_to_attach' ).val(imgIdInput+attachment.id+",")
                        //imgContainer.append( '<img src="'+attachment.sizes.thumbnail.url+'" alt="" style="max-width:100%;"/>' );

                        // Send the attachment id to our hidden input
                       // imgIdInput.val( attachment.id );



                } );

                // Fires when a state activates.
                frame.on( 'activate', function() {} );

                // Fires when a mode is deactivated on a region.
                frame.on( '{region}:deactivate', function() {} );
                // and a more specific event including the mode.
                frame.on( '{region}:deactivate:{mode}', function() {} );

                // Fires when a region is ready for its view to be created.
                frame.on( '{region}:create', function() {} );
                // and a more specific event including the mode.
                frame.on( '{region}:create:{mode}', function() {} );

                // Fires when a region is ready for its view to be rendered.
                frame.on( '{region}:render', function() {} );
                // and a more specific event including the mode.
                frame.on( '{region}:render:{mode}', function() {} );

                // Fires when a new mode is activated (after it has been rendered) on a region.
                frame.on( '{region}:activate', function() {} );
                // and a more specific event including the mode.
                frame.on( '{region}:activate:{mode}', function() {} );

                // Get an object representing the current state.
        frame.state();


                // Get an object representing the previous state.
        frame.lastState();

                // Open the modal.
                frame.open();  
    });
        
        
    $('.splash_attach_delete').click(function () {
        var curent;
        var img_remove = jQuery(this).parent().attr('data-imageid');
        jQuery(this).parent().remove();

        jQuery('#splash_slider_images .uploaded_thumb').each(function () {
            remove = jQuery(this).attr('data-imageid');
            curent = curent + ',' + remove;

        });
        jQuery('#splash_slider_gallery').val(curent);

        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'wpestate_delete_file',
                'attach_id': img_remove,
            },
            success: function (data) {
             

            },
            error: function (errorThrown) {
           
            }
    });//end ajax   
    });
    
    
    $('#splash_video_cover_img_button').click(function () {
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#splash_video_cover_img').val(imgurl);
            tb_remove();
        };
        return false;
    });
    
    
    $('#wp_estate_splash_overlay_image_button').click(function () {
        formfield = $('#page_custom_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#wp_estate_splash_overlay_image').val(imgurl);
            tb_remove();
        };
        return false;
    });
    
    $('#splash_image_button').click(function () {
        formfield = $('#page_custom_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#splash_image').val(imgurl);
            tb_remove();
        };
        return false;
    });
    
   // var formfield, imgurl;
   

    $('#page_custom_image_button').click(function () {
        formfield = $('#page_custom_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#page_custom_image').val(imgurl);
            tb_remove();
        };
        return false;
    });
    
    $('#page_custom_video_cover_image_button').click(function () {
        formfield = $('#page_custom_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#page_custom_video_cover_image').val(imgurl);
            tb_remove();
        };
        return false;
    });

    if (jQuery('.user-verifications').length === 1) {
        var verifications = jQuery('.user-verifications');

        verifications.on('change', 'input[type="checkbox"]', function () {
            var userID = jQuery(this).data('userid'),
                tmpIsVerified = jQuery(this).attr('checked'),
                isVerified = 0,
                editUser = jQuery(this).closest('.verify-user', jQuery('.user-verifications'));

            if (tmpIsVerified === 'checked') {
                isVerified = 1;
            }

          

            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action': 'wpestate_update_verification',
                    'userid': userID,
                    'verified': isVerified
                },
                success: function (data) {
                    switch (true) {
                        case (isVerified === 0):
                         
                            editUser.removeClass('verified');
                            break;
                        case (isVerified === 1):
                        
                            editUser.addClass('verified');
                            break;
                    }
                },
                error: function (errorThrown) {
               
                }
            });
        });
    }
   

});