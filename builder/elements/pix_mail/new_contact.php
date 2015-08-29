<?php
if($_POST)
{
    // PixFort Contact Form
    // $mail_type = "ce";
    // $customEmail = false;
    // $to_Email       = "pixfort.com@gmail.com"; //Replace with recipient email address
    // $subject        = 'An email from FLATPACK contact form'; //Subject line for emails
    // //-----------------------------------------------------------------------------------------
    


    include("config.php");    


    //-----------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------
    $use_reCaptcha = false;   
    if($secret != ""){
        $use_reCaptcha = true;   
    }


    /* Install headers */
    header('Expires: 0');
    header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Pragma: no-cache');
    header('Content-Type: application/json; charset=utf-8');


    if($use_reCaptcha){
        // empty response
        $response = null;
        // grab recaptcha library
        require_once "recaptchalib.php";
        // check secret key
        $reCaptcha = new ReCaptcha($secret);
    }
    require_once('api_mailchimp/MCAPI.class.php');
    require_once('api_getresponse/GetResponseAPI.class.php');
    require_once('api_campaign/CMBase.php');


    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        //exit script outputting json data
        $output = json_encode(
        array(
            'type'=>'error', 
            'text' => 'Request must come from Ajax'
        ));
        die($output);
    } 
    

    $values = array($_POST);
    $o_string = "";
    $user_Email = $to_Email;
    $pix_extra = array();
    $has_type = false;
    $the_type = "";
    foreach ($values as  $value) {
        foreach ($value as $variable => $v) {
            if(filter_var($variable, FILTER_SANITIZE_STRING) == 'pixfort_form_type'){
                if(filter_var($variable, FILTER_SANITIZE_STRING) != ''){
                    $the_type = $v;
                    $has_type =true;
                }
            }elseif(filter_var($variable, FILTER_SANITIZE_STRING) == 'g-recaptcha-response'){
                if($use_reCaptcha){
                    $response = $reCaptcha->verifyResponse(
                        $_SERVER["REMOTE_ADDR"],
                        $v
                    );
                    if ($response == null || (!$response->success)) {
                        $output = json_encode(array('type'=>'error', 'text' => 'Please check the Captcha!'));
                        die($output);
                    }
                }
            }else{
                $o_string .= filter_var($variable, FILTER_SANITIZE_STRING) . ': '. filter_var($v, FILTER_SANITIZE_STRING) ." -  \n";
                if(filter_var($variable, FILTER_SANITIZE_STRING) == 'email'){
                    $user_Email = $v;
                    if(!validMail($user_Email)) //email validation
                    {
                        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
                        die($output);
                    }
                }else{
                    $pix_extra[filter_var($variable, FILTER_SANITIZE_STRING)] = filter_var($v, FILTER_SANITIZE_STRING);
                }
                // if($use_reCaptcha){
                //     if(filter_var($variable, FILTER_SANITIZE_STRING) == 'g-recaptcha-response'){
                //         $response = $reCaptcha->verifyResponse(
                //             $_SERVER["REMOTE_ADDR"],
                //             $v
                //         );
                //         if ($response == null || (!$response->success)) {
                //             $output = json_encode(array('type'=>'error', 'text' => 'Please check the Captcha!'));
                //             die($output);
                //         }
                //     }
                // }
            }
        }
    }

    if($has_type){
        if($the_type == 'ce'){
            pixmail($o_string, $user_Email, $to_Email, $subject);
        }elseif($the_type == 'mc'){
            sendMailChimp($user_Email, $pix_extra);    
        }elseif($the_type == 'cm'){
            sendCampaign($user_Email, $pix_extra);   
        }elseif($the_type == 'gr'){
            sendGetResponse($user_Email, $pix_extra);
        }else{
            $output = json_encode(array('type'=>'error', 'text' => 'Error: Wrong pix-form-type attribute provided for the form!'));
            die($output);
        }
    }else{
        if($mail_type == 'ce'){
            pixmail($o_string, $user_Email, $to_Email, $subject);
        }elseif($mail_type == 'mc'){
            sendMailChimp($user_Email, $pix_extra);    
        }elseif($mail_type == 'cm'){
            sendCampaign($user_Email, $pix_extra);   
        }elseif($mail_type == 'gr'){
            sendGetResponse($user_Email, $pix_extra);
        }else{
            $output = json_encode(array('type'=>'error', 'text' => 'Error: Wrong mail_type attribute provided in contact.php file!'));
            die($output);
        }
    }
    
} // End POST

    function pixmail($o_string, $user_Email, $to_Email, $subject)
    {
        $final_msg = "\n"."Subscribe using flatpack form,"."\n";
        $final_msg .= $o_string;
        
        //proceed with PHP email.
        $headers = 'From: '.$user_Email.'' . "\r\n" .
        'Reply-To: '.$user_Email.'' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        
        // send mail
        $sentMail = @mail($to_Email, $subject, $final_msg, $headers);
        
        if(!$sentMail)
        {
            $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
            die($output);
        }else{
            $output = json_encode(array('type'=>'message', 'text' => 'Hi, Thank you for your email'));
            die($output);
        }
    }

    function validMail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        } else {
            return false;
        }
    }

    function sendMailChimp($mailSubscribe, $merge_vars=NULL)
    {
        if(defined('MC_APIKEY') && defined('MC_LISTID')){
            $api = new MCAPI(MC_APIKEY);
            if($api->listSubscribe(MC_LISTID, $mailSubscribe, $merge_vars) !== true){
                if($api->errorCode == 214){
                    $output = json_encode(array('type'=>'error', 'text' => 'Email Already Exists'));
                } else {
                    $output = json_encode(array('type'=>'error', 'text' => $api->errorMessage));
                    die($output);
                }
            }else{
                $output = json_encode(array('type'=>'message', 'text' => 'Hi, Thank you for your email'));
                die($output);
            }
        }
    }


    function sendCampaign($mailSubscribe, $merge_vars=NULL)
    {
        if(defined('CM_APIKEY') && defined('CM_LISTID')){
            
            $api_key = CM_APIKEY;
            $client_id = null;
            $campaign_id = null;
            $list_id = CM_LISTID;
            $cm = new CampaignMonitor( $api_key, $client_id, $campaign_id, $list_id );
            $result = $cm->subscriberAddWithCustomFields($mailSubscribe, getName($mailSubscribe), $merge_vars, null, false);
            if($result['Code'] == 0){
                $output = json_encode(array('type'=>'message', 'text' => 'Thank you for your Subscription.'));
                die($output);
            }else{
                $output = json_encode(array('type'=>'error', 'text' => 'Error : ' . $result['Message']));
                die($output);
            }
        }
    }

    function sendGetResponse($mailSubscribe, $merge_vars=NULL)
    {
        if(defined('GR_APIKEY') && defined('GR_CAMPAIGN')){
            $api = new GetResponse(GR_APIKEY);
            
            $campaign = $api->getCampaignByName(GR_CAMPAIGN);

            $subscribe = $api->addContact($campaign, getName($mailSubscribe), $mailSubscribe, 'standard', 0, $merge_vars);
            if($subscribe){
                $output = json_encode(array('type'=>'message', 'text' => 'Thank you for your Subscription.'));
                die($output);
            }else{
                $output = json_encode(array('type'=>'error', 'text' => 'Error: Email Already Exists'));
                die($output);
            }
        }
    }

    function errorLog($name,$desc)
    {
        file_put_contents(ERROR_LOG, date("m.d.Y H:i:s")." (".$name.") ".$desc."\n", FILE_APPEND);
    }

    function getName($mail)
    {
        preg_match("/([a-zA-Z0-9._-]*)@[a-zA-Z0-9._-]*$/",$mail,$matches);
        return $matches[1];
    }

?>