<?php
	$mail_type = "ce";
	//-----------------------------------------------------------------------------------------
    $to_Email       = "pixfort.com@gmail.com"; //Replace with recipient email address
    $subject        = 'An email from FLATPACK contact form.'; //Subject line for emails

    // your recaptcha secret key
    $secret = "";      // Add your reCAPTCHA secret key
    //----------------------------------------------------------    -------------------------------

    /* Mailchimp setting. */
    define('MC_APIKEY', ''); // Your API key from here - http://admin.mailchimp.com/account/api
    define('MC_LISTID', ''); // List unique id from here - http://admin.mailchimp.com/lists/

    /* Campaign Monitor setting. */
    define('CM_APIKEY', ''); // Your APIKEY from here - https://pixfort.createsend.com/admin/account/
    define('CM_LISTID', ''); // List ID from here - https://www.campaignmonitor.com/api/getting-started/#listid

    /* GetResponse setting. */
    define('GR_APIKEY', ''); // Your API key from here - https://app.getresponse.com/my_api_key.html
    define('GR_CAMPAIGN', ''); // Campaign name from here - https://app.getresponse.com/campaign_list.html
?>