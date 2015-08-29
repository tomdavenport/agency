<?php
		
	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
	
	
	$filename = "elements/preview_".generateRandomString(20).".html";
	
	$previewFile = fopen($filename, "w");

	$skeleton1 = file_get_contents('elements/sk1.html');
	$skeleton2 = file_get_contents('elements/sk2.html');
	$skeleton3 = file_get_contents('elements/sk3.html');

	$final_preview = $skeleton1 . $skeleton2 . $_POST['page'] . $skeleton3;
	
	
	
	//fwrite($previewFile, $_POST['page']);
	fwrite($previewFile, $final_preview);
	
	fclose($previewFile);
	
	header('Location: '.$filename);
	
	
?>