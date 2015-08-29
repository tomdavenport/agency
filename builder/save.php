<?php

	/* CONFIG */
	
	$pathToAssets = array("elements/assets", "elements/stylesheets", "elements/fonts", "elements/images/main", "elements/images/icons", "elements/js-files", "elements/pix_mail", "elements/images/social_icons", "elements/images/switch", "elements/images/testimonials", "elements/images/uploads");
	
	$filename = "tmp/website.zip"; //use the /tmp folder to circumvent any permission issues on the root folder

	/* END CONFIG */
	
	$tmpfilename = 'tmp/website.zip';

	if (file_exists($tmpfilename)) {
	    unlink($tmpfilename);
	}	
	//unlink('tmp/website.zip');
	$form_type_export = $_POST['form_type_export'];
	$recaptcha = $_POST['recaptcha'];

	$to_Email = $_POST['to_Email'];
	$subject = $_POST['subject'];

	$MC_APIKEY = $_POST['MC_APIKEY'];
	$MC_LISTID = $_POST['MC_LISTID'];

	$CM_APIKEY = $_POST['CM_APIKEY'];
	$CM_LISTID = $_POST['CM_LISTID'];

	$GR_APIKEY = $_POST['GR_APIKEY'];
	$GR_CAMPAIGN = $_POST['GR_CAMPAIGN'];


	$pixfort_mail = "<?php
	\$mail_type = '$form_type_export';
	//-----------------------------------------------------------------------------------------
    \$to_Email       = '$to_Email'; //Replace with recipient email address
    \$subject        = '$subject'; //Subject line for emails

    // your recaptcha secret key
    \$secret = '$recaptcha';      // Add your reCAPTCHA secret key
    //-----------------------------------------------------------------------------------------

    /* Mailchimp setting */
    define('MC_APIKEY', '$MC_APIKEY'); // Your API key from here - http://admin.mailchimp.com/account/api
    define('MC_LISTID', '$MC_LISTID'); // List unique id from here - http://admin.mailchimp.com/lists/

    /* Campaign Monitor setting. */
    define('CM_APIKEY', '$CM_APIKEY'); // Your APIKEY from here - https://pixfort.createsend.com/admin/account/
    define('CM_LISTID', '$CM_LISTID'); // List ID from here - https://www.campaignmonitor.com/api/getting-started/#listid

    /* GetResponse setting. To enable a setting, uncomment (remove the '#' at the start of the line)*/
    define('GR_APIKEY', '$GR_APIKEY'); // Your API key from here - https://app.getresponse.com/my_api_key.html
    define('GR_CAMPAIGN', '$GR_CAMPAIGN'); // Campaign name from here - https://app.getresponse.com/campaign_list.html
?>";

//echo $pixfort_mail;

	$zip = new ZipArchive();
	
	$zip->open($filename, ZipArchive::CREATE);
	
	$dirs = array();
	$doc = new DOMDocument();
	$doc->recover = true;
	$doc->strictErrorChecking = false;
	libxml_use_internal_errors(true);

	foreach( $_POST['pages'] as $page=>$content2 ) {
		
		$doc->recover = true;
		$doc->strictErrorChecking = false;		
		$doc->loadHTML($content2);  
		$selector = new DOMXPath($doc);

		$result = $selector->query('//div[@class="section_pointer"]');
		

		// loop through all found items
		if ($result->length > 0) {
			foreach($result as $node) {
				//array_push($dirs, $node->getAttribute('pix-name'));
				if(!in_array('elements/images/'.$node->getAttribute('pix-name'), $dirs, true)){
					array_push($dirs, 'elements/images/'.$node->getAttribute('pix-name'));
				}

			    //echo $node->getAttribute('pix-name') . '<br>';
			}
			$pathToAssets = array_merge($pathToAssets, $dirs);
		}
		
		//print_r($pathToAssets);
		//echo $content;
	
	}
	
	//add folder structure
	foreach( $pathToAssets as $thePath ) {
	
		// Create recursive directory iterator
		$files = new RecursiveIteratorIterator(
	    	new RecursiveDirectoryIterator( $thePath ),
	    	RecursiveIteratorIterator::LEAVES_ONLY
		);
	
		foreach ($files as $name => $file) {
		
			if( $file->getFilename() != '.' && $file->getFilename() != '..' ) {
	
	    		// Get real path for current file
	    		$filePath = $file->getRealPath();
	    
	    		$temp = explode("/", $name);
	    
	    		array_shift( $temp );
	    
	    		$newName = implode("/", $temp);
	
	    		// Add current file to archive
	    		$zip->addFile($filePath, $newName);
	    	
	    	}
	    
		}
	
	}
	
	$skeleton1 = file_get_contents('elements/sk1.html');
	$skeleton2 = file_get_contents('elements/sk2.html');
	$skeleton3 = file_get_contents('elements/sk3.html');

	foreach( $_POST['pages'] as $page=>$content ) {
		$t_seo = json_decode($_POST['seo'][$page]);
		$seo_tags = '<title>'.$t_seo[0].'</title>'."\n".'<meta name="description" content="'.$t_seo[1].'">'."\n".'<meta name="keywords" content="'.$t_seo[2].'">'."\n".$t_seo[3];
		$new_content = $skeleton1 . $seo_tags . $skeleton2 . $content . $skeleton3;
		//$zip->addFromString($page.".html", $_POST['doctype']."\n".stripslashes($new_content));
		$zip->addFromString($page.".html", stripslashes($new_content));
	}

	$zip->deleteName('pix_mail\config.php');
	$zip->addFromString("pix_mail/config.php", $pixfort_mail);
	$zip->close();
	
	
	$yourfile = $filename;
	
	$file_name = basename($yourfile);
	
	header("Content-Type: application/zip");
	header("Content-Transfer-Encoding: Binary");
	header("Content-Disposition: attachment; filename=$file_name");
	header("Content-Length: " . filesize($yourfile));
	
	readfile($yourfile);
	
	unlink('website.zip');
	
	exit;
	
	
?>