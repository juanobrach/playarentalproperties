<?php
////////////////////////////////////////////////////////////////////////////////
/// Facebook  Login
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_facebook_login') ):

function estate_facebook_login($get_vars){
    //https://developers.facebook.com/docs/php/gettingstarted
    session_start();
    $facebook_api               =   esc_html ( get_option('wp_estate_facebook_api','') );
    $facebook_secret            =   esc_html ( get_option('wp_estate_facebook_secret','') );
 
    $fb = new Facebook\Facebook([
            'app_id'  => $facebook_api,
            'app_secret' => $facebook_secret,
            'default_graph_version' => 'v2.12',
        ]);
    $helper = $fb->getRedirectLoginHelper();
        

    $secret      =   $facebook_secret;
    try {
        $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
         // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
    exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    
    
    // Logged in
    // var_dump($accessToken->getValue());

    // The OAuth 2.0 client handler helps us manage access tokens
    $oAuth2Client = $fb->getOAuth2Client();

    // Get the access token metadata from /debug_token
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    //echo '<h3>Metadata</h3>';
    //var_dump($tokenMetadata);

    // Validation (these will throw FacebookSDKException's when they fail)
    $tokenMetadata->validateAppId($facebook_api); 
    
    // If you know the user ID this access token belongs to, you can validate it here
    //$tokenMetadata->validateUserId('123');
    $tokenMetadata->validateExpiration();

    if (! $accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
        try {
          $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
          echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
          exit;
        }

    // echo '<h3>Long-lived</h3>';
    //  var_dump($accessToken->getValue());
    }

    $_SESSION['fb_access_token'] = (string) $accessToken;
    
    try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $fb->get('/me?fields=id,email,name,first_name,last_name', $accessToken);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    $user = $response->getGraphUser();

    
    if(isset($user['name'])){
        $full_name=$user['name'];
    }
    if(isset($user['email'])){
        $email=$user['email'];
    }
    $identity_code=$secret.$user['id'];  
    wpestate_register_user_via_google($email,$full_name,$identity_code,$user['first_name'],$user['last_name']); 
    $info                   = array();
    $info['user_login']     = $full_name;
    $info['user_password']  = $identity_code;
    $info['remember']       = true;

    $user_signon            = wp_signon( $info, true );
        
        
    if ( is_wp_error($user_signon) ){ 
        wp_redirect( esc_url(home_url() ) ); exit(); 
    }else{
        wpestate_update_old_users($user_signon->ID);
        wpestate_calculate_new_mess();
        wp_redirect( wpestate_get_template_link('user_dashboard_profile.php') );
        exit();
    }
               
    
    
    
  
}

endif; // end   estate_facebook_login 






////////////////////////////////////////////////////////////////////////////////
/// estate_google_oauth_login  Login
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_google_oauth_login') ):

    function estate_google_oauth_login($get_vars){
       // set_include_path( get_include_path() . PATH_SEPARATOR . get_template_directory().'/libs/resources');
        $allowed_html   =   array();
        require_once  get_template_directory()."/libs/resources/base.php";
        require_once  get_template_directory()."/libs/resources/src2/Google/autoload.php";
        
        $google_client_id       =   esc_html ( get_option('wp_estate_google_oauth_api','') );
        $google_client_secret   =   esc_html ( get_option('wp_estate_google_oauth_client_secret','') );
        $google_redirect_url    =   wpestate_get_template_link('user_dashboard_profile.php');
        $google_developer_key   =   esc_html ( get_option('wp_estate_google_api_key','') );

        //$google_client_id = '789776065323-47hjb2931cl5ag9881gcpfcn0qq9e72n.apps.googleusercontent.com';
        //$google_client_secret = 'q9kSQCz2Pif1e1wLDeaYRoUl';
      
        
        
        $gClient = new Google_Client();
        $gClient->setApplicationName('Login to WpRentals');
        $gClient->setClientId($google_client_id);
        $gClient->setClientSecret($google_client_secret);
        $gClient->setRedirectUri($google_redirect_url);
        $gClient->setDeveloperKey($google_developer_key);
        $google_oauthV2 = new Google_Service_Oauth2($gClient);

        if (isset($_GET['code'])) { 
            $code= sanitize_text_field ( wp_kses($_GET['code'],$allowed_html) );
            $gClient->authenticate($code);
        }



        if ($gClient->getAccessToken()) 
        {    
            
            $allowed_html      =   array();
            $dashboard_url     =   wpestate_get_template_link('user_dashboard_profile.php');
            $user              =   $google_oauthV2->userinfo->get();
            $full_name         =   wp_kses($user['name'], $allowed_html);
            $email             =   wp_kses($user['email'], $allowed_html);
        
            $user_id           =   $user['id'];
            $full_name         =   wp_kses($user['name'], $allowed_html);
            $email             =   wp_kses($user['email'], $allowed_html);
            $full_name         =   str_replace(' ','.',$full_name);  
            
            $first_name=$last_name='';
            if(isset($user['familyName'])){
                $last_name=$user['familyName'];
            }  
            if(isset($user['givenName'])){
                $first_name=$user['givenName'];
            }
            
            wpestate_register_user_via_google($email,$full_name,$user_id,$first_name,$last_name); 
            $wordpress_user_id=username_exists($full_name);
            wp_set_password( $code, $wordpress_user_id ) ;

            $info                   = array();
            $info['user_login']     = $full_name;
            $info['user_password']  = $code;
            $info['remember']       = true;
            $user_signon            = wp_signon( $info, true );



            if ( is_wp_error($user_signon) ){ 
                wp_redirect( esc_url(home_url()) );  exit();
            }else{
                wpestate_update_old_users($user_signon->ID);
                wpestate_calculate_new_mess();
                wp_redirect($dashboard_url);exit();
            }
          
        }   
    }

endif; // end   estate_google_oauth_login 

////////////////////////////////////////////////////////////////////////////////
/// Open ID Login
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('estate_open_id_login') ):

function estate_open_id_login($get_vars){
    require get_template_directory().'/libs/resources/openid.php';  
    $openid         =   new LightOpenID( wpestate_get_domain_openid() );
    $allowed_html   =   array();
    if( $openid->validate() ){
        
        $dashboard_url          =   wpestate_get_template_link('user_dashboard_profile.php');
        $openid_identity        =   wp_kses( $get_vars['openid_identity'],$allowed_html);
        $openid_identity_check  =   wp_kses( $get_vars['openid_identity'],$allowed_html);
        
        
        if(strrpos  ($openid_identity_check,'google') ){
            $email                  =   wp_kses ( $get_vars['openid_ext1_value_contact_email'],$allowed_html );
            $last_name              =   wp_kses ( $get_vars['openid_ext1_value_namePerson_last'],$allowed_html );
            $first_name             =   wp_kses ( $get_vars['openid_ext1_value_namePerson_first'],$allowed_html );
            $full_name              =   $first_name.$last_name;
            $openid_identity_pos    =   strrpos  ($openid_identity,'id?id=');
            $openid_identity        =   str_split($openid_identity, $openid_identity_pos+6);
            $openid_identity_code   =   $openid_identity[1]; 
        }
        
        if(strrpos  ($openid_identity_check,'yahoo')){
          
            $email                  =   wp_kses ( $get_vars['openid_ax_value_email'] ,$allowed_html);
            $full_name              =   wp_kses ( str_replace(' ','.',$get_vars['openid_ax_value_fullname']) ,$allowed_html);            
            $openid_identity_pos    =   strrpos  ($openid_identity,'/a/.');
            $openid_identity        =   str_split($openid_identity, $openid_identity_pos+4);
            $openid_identity_code   =   $openid_identity[1]; 
        }
       
        wpestate_register_user_via_google($email,$full_name,$openid_identity_code); 
        $info                   = array();
        $info['user_login']     = $full_name;
        $info['user_password']  = $openid_identity_code;
        $info['remember']       = true;
        $user_signon            = wp_signon( $info, true );
        
 
        
        if ( is_wp_error($user_signon) ){ 
            wp_redirect( esc_url( home_url() ) );  exit();
        }else{
            wpestate_update_old_users($user_signon->ID);
            wpestate_calculate_new_mess();
            wp_redirect($dashboard_url);exit();
        }
           
        } 
    }// end  estate_open_id_login
endif; // end   estate_open_id_login  







////////////////////////////////////////////////////////////////////////////////
/// Twiter API v1.1 functions
////////////////////////////////////////////////////////////////////////////////
/*
 function getConnectionWithAccessToken($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret) {
    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
    return $connection;
} */

                
//convert links to clickable format
if( !function_exists('wpestate_convert_links') ):
    function wpestate_convert_links($status,$targetBlank=true,$linkMaxLen=250){
        // the target
        $target=$targetBlank ? " target=\"_blank\" " : "";

        // convert link to url
        $status = preg_replace("/((http:\/\/|https:\/\/)[^ )]+)/e", "'<a href=\"$1\" title=\"$1\" $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status);

   
        
        // convert @ to follow
        $status = preg_replace("/(@([_a-z0-9\-]+))/i","<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>",$status);

        // convert # to search
        $status = preg_replace("/(#([_a-z0-9\-]+))/i","<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>",$status);

        // return the status
        return $status;
    }
endif;
                

//convert dates to readable format	
if( !function_exists('wpestate_convert_links') ):
    function wpestate_convert_links($a) {
        //get current timestampt
    
        $b = strtotime("now"); 
        //get timestamp when tweet created
        $c = strtotime($a);
        //get difference
        $d = $b - $c;
        //calculate different time values
        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;
        $week = $day * 7;

        if(is_numeric($d) && $d > 0) {
                //if less then 3 seconds
                if($d < 3) return "right now";
                //if less then minute
                if($d < $minute) return floor($d) . " seconds ago";
                //if less then 2 minutes
                if($d < $minute * 2) return "about 1 minute ago";
                //if less then hour
                if($d < $hour) return floor($d / $minute) . " minutes ago";
                //if less then 2 hours
                if($d < $hour * 2) return "about 1 hour ago";
                //if less then day
                if($d < $day) return floor($d / $hour) . " hours ago";
                //if more then day, but less then 2 days
                if($d > $day && $d < $day * 2) return "yesterday";
                //if less then year
                if($d < $day * 365) return floor($d / $day) . " days ago";
                //else return more than a year
                return "over a year ago";
        }
    }
endif;
 

///////////////////////////////////////////////////////////////////////////////////////////
// register google user
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_register_user_via_google') ):
    
    function wpestate_register_user_via_google($email,$full_name,$openid_identity_code,$firsname='',$lastname=''){
        if ( email_exists( $email ) ){ 
            if(username_exists($full_name) ){
                return;
            }else{
                $user_id  = wp_create_user( $full_name, $openid_identity_code,' ' );  
                
                rcapi_create_new_user($user_id,$full_name,$openid_identity_code,' ');
                   
                wpestate_update_profile($user_id); 
                wpestate_register_as_user($full_name,$user_id,$firsname,$lastname);
            }
        }else{
            if(username_exists($full_name) ){
                return;
            }else{
                $user_id  = wp_create_user( $full_name, $openid_identity_code, $email ); 
                
                rcapi_create_new_user($user_id,$full_name,$openid_identity_code,$email);
                 
                wpestate_update_profile($user_id);
                wpestate_register_as_user($full_name,$user_id,$firsname,$lastname);
            }
        }
    }
endif; // end   wpestate_register_user_via_google 




///////////////////////////////////////////////////////////////////////////////////////////
// get domain open id
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_domain_openid') ):

    function wpestate_get_domain_openid(){
        $realm_url = esc_url(get_home_url());
        $realm_url= str_replace('http://','',$realm_url);
        $realm_url= str_replace('https://','',$realm_url);  
        return $realm_url;
    }

endif; // end   wpestate_get_domain_openid 





///////////////////////////////////////////////////////////////////////////////////////////
// paypal functions - get acces token
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_access_token') ):
    function wpestate_get_access_token($url, $postdata) {
        $clientId                       =   esc_html( get_option('wp_estate_paypal_client_id','') );
        $clientSecret                   =   esc_html( get_option('wp_estate_paypal_client_secret','') );

            
        $curl = curl_init($url); 
	curl_setopt($curl, CURLOPT_POST, true); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
	curl_setopt($curl, CURLOPT_HEADER, false); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); 
#	curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
	$response = curl_exec( $curl );
	if (empty($response)) {
	    // some kind of an error happened
	    die(curl_error($curl));
	    curl_close($curl); // close cURL handler
	} else {
	    $info = curl_getinfo($curl);
		//echo "Time took: " . $info['total_time']*1000 . "ms\n";
	    curl_close($curl); // close cURL handler
		if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
			echo "Received error: " . $info['http_code']. "\n";
			echo "Raw response:".$response."\n";
			die();
	    }
	}

	// Convert the result from JSON format to a PHP array 
	$jsonResponse = json_decode( $response );
	return $jsonResponse->access_token;
    }

endif; // end   wpestate_get_access_token 


///////////////////////////////////////////////////////////////////////////////////////////
// paypal functions - make post call
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_make_post_call') ):


    function wpestate_make_post_call($url, $postdata,$token) {
      
	$curl = curl_init($url); 
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer '.$token,
				'Accept: application/json',
				'Content-Type: application/json'
				));
	
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); 
	#curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
	$response = curl_exec( $curl );
	if (empty($response)) {
	    // some kind of an error happened
	    die(curl_error($curl));
	    curl_close($curl); // close cURL handler
	} else {
	    $info = curl_getinfo($curl);
		//echo "Time took: " . $info['total_time']*1000 . "ms\n";
	    curl_close($curl); // close cURL handler
		if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
//			echo "Received error: " . $info['http_code']. "\n";
//			echo "Raw response:".$response."\n";
//			die();
	    }
	}

	// Convert the result from JSON format to a PHP array 
	$jsonResponse = json_decode($response, TRUE);
	return $jsonResponse;
    }

 

endif; // end   wpestate_make_post_call 


if( !function_exists('wpestate_make_get_call') ):


    function wpestate_make_get_call($url,$token) {
      
	$curl = curl_init($url); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer '.$token,
				'Accept: application/json',
				'Content-Type: application/json'
				));

	$response = curl_exec( $curl );
	if (empty($response)) {
	    // some kind of an error happened
	    die(curl_error($curl));
	    curl_close($curl); // close cURL handler
	} else {
	    $info = curl_getinfo($curl);
		//echo "Time took: " . $info['total_time']*1000 . "ms\n";
	    curl_close($curl); // close cURL handler
		if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
			echo "Received error: " . $info['http_code']. "\n";
			echo "Raw response:".$response."\n";
			die();
	    }
	}

	// Convert the result from JSON format to a PHP array 
	$jsonResponse = json_decode($response, TRUE);
	return $jsonResponse;
    }

 

endif; // end   wpestate_make_post_call 




function wpestate_create_paypal_payment_plan($pack_id,$token){
    $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
    $submission_curency_status      =   esc_html( get_option('wp_estate_submission_curency','') );
    $paypal_status                  =   esc_html( get_option('wp_estate_paypal_api','') );
    $billing_period                 =   get_post_meta($pack_id, 'biling_period', true);
    $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));
    $pack_name                      =   get_the_title($pack_id);
            
    $host   =   'https://api.sandbox.paypal.com';
    if($paypal_status=='live'){
        $host   =   'https://api.paypal.com';
    }
            
    $url        = $host.'/v1/oauth2/token'; 
    $postArgs   = 'grant_type=client_credentials';
  //  $token      = wpestate_get_access_token($url,$postArgs);
    $url        = $host.'/v1/payments/billing-plans/';
    $dash_profile_link = wpestate_get_template_link('user_dashboard_profile.php');
            
            
    $billing_plan = array(
        'name'                  =>  $pack_name ,
        'type'                  =>  'INFINITE',
        'description'           =>    $pack_name.esc_html__( ' package on ','wprentals').get_bloginfo('name'),
    );

    $billing_plan  [ 'payment_definitions']= array( 
                    array( 
                        'name'                  =>  $pack_name.esc_html__( ' package on ','wprentals').get_bloginfo('name'),
                        'type'                  =>  'REGULAR',
                        'frequency'             =>  $billing_period,
                        'frequency_interval'    =>  $billing_freq,
                        'amount'                =>  array(
                                                        'value'     =>  $pack_price,
                                                        'currency'  =>  $submission_curency_status,
                                                    ),
                        'cycles'     =>'0' 
                        )
            );

                                            
    $billing_plan  [ 'merchant_preferences']   =  array(
                                        'return_url'        =>  $dash_profile_link,
                                        'cancel_url'        =>  $dash_profile_link,
                                        'auto_bill_amount'  =>  'yes'
                                    );
            
    $json       = json_encode($billing_plan);
    $json_resp  = wpestate_make_post_call($url, $json,$token);
                

    
    
    if( $json_resp['state']!='ACTIVE'){
        if( wpestate_activate_paypal_payment_plan( $json_resp['id']) ){
            $to_save = array();
            $to_save['id']          =   $json_resp['id'];
            $to_save['name']        =   $json_resp['name'];
            $to_save['description'] =   $json_resp['description'];
            $to_save['type']        =   $json_resp['type'];
            $to_save['state']       =   "ACTIVE";
            $paypal_status                  =   esc_html( get_option('wp_estate_paypal_api','') );
            update_post_meta($pack_id,'paypal_payment_plan_'.$paypal_status,$to_save);
            
            return true;
        }
    }
    
   
    
    
    
  
   
}


function wpestate_activate_paypal_payment_plan($paypal_plan_id,$token){
    $paypal_status                  =   esc_html( get_option('wp_estate_paypal_api','') );
    $host   =   'https://api.sandbox.paypal.com';
    if($paypal_status=='live'){
        $host   =   'https://api.paypal.com';
    }
  
    $postArgs   = 'grant_type=client_credentials';
  
    $ch = curl_init();
    $url = $host."/v1/payments/billing-plans/".$paypal_plan_id."/";  
    

    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "[{\n    \"op\": \"replace\",\n    \"path\": \"/\",\n    \"value\": {\n        \"state\": \"ACTIVE\"\n    }\n}]");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");


    $headers = array();
    $headers[] = "Content-Type: application/json";
    $headers[] = 'Authorization: Bearer '.$token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return false;
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);
    return true;
                
}



function wpestate_create_paypal_payment_agreement($pack_id,$token){
    $current_user = wp_get_current_user();
    $paypal_status                  =   esc_html( get_option('wp_estate_paypal_api','') );
    $payment_plan                   =   get_post_meta($pack_id, 'paypal_payment_plan_'.$paypal_status, true);
    $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
    $submission_curency_status      =   esc_html( get_option('wp_estate_submission_curency','') );
    $paypal_status                  =   esc_html( get_option('wp_estate_paypal_api','') );
    $billing_period                 =   get_post_meta($pack_id, 'biling_period', true);
    $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));
    $pack_name                      =   get_the_title($pack_id);
            
    $host   =   'https://api.sandbox.paypal.com';
    if($paypal_status=='live'){
        $host   =   'https://api.paypal.com';
    }
            
    $url        = $host.'/v1/oauth2/token'; 
    $postArgs   = 'grant_type=client_credentials';
  //  $token      = wpestate_get_access_token($url,$postArgs);
    
    
    $url        = $host.'/v1/payments/billing-agreements/';
    $dash_profile_link = wpestate_get_template_link('user_dashboard_profile.php');
    $billing_agreement = array(
                        'name'          => __('PayPal payment agreement','wprentals'),
                        'description'   => __('PayPal payment agreement','wprentals'),
                        'start_date'    =>  gmdate("Y-m-d\TH:i:s\Z", time()+100 ),
//                        'return_url'    =>  $dash_profile_link,
//                        'cancel_url'    =>  $dash_profile_link,
//                        'auto_bill_amount' => 'YES'
        
    );
    
    $billing_agreement['payer'] =   array(
                        'payment_method'=>'paypal',
                        'payer_info'    => array('email'=>'payer@example.com'),
    );
     
    $billing_agreement['plan'] = array(
                        'id'            =>  $payment_plan['id'],
//                        'name'          =>  $payment_plan['name'],
//                        'description'   =>  $payment_plan['description'],
//                        'type'          =>  $payment_plan['type'],
    );
    

    
    
    $json       = json_encode($billing_agreement);
    $json_resp  = wpestate_make_post_call($url, $json,$token);
        

  
    foreach ($json_resp['links'] as $link) {
            if($link['rel'] == 'execute'){
                    $payment_execute_url = $link['href'];
                    $payment_execute_method = $link['method'];
            } else 	if($link['rel'] == 'approval_url'){
                            $payment_approval_url = $link['href'];
                            $payment_approval_method = $link['method'];
                             print $link['href'];
                    }
    }



    $executor['paypal_execute']     =   $payment_execute_url;
    $executor['paypal_token']       =   $token;
    $executor['pack_id']            =   $pack_id;
    $save_data[$current_user->ID ]  =   $executor;
    update_option('paypal_pack_transfer',$save_data);
}

?>
