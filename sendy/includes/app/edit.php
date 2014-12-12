<?php include('../functions.php');?>
<?php include('../login/auth.php');?>
<?php 
	//------------------------------------------------------//
	//                      VARIABLES                       //
	//------------------------------------------------------//
	
	$id = mysqli_real_escape_string($mysqli, $_POST['id']);
	$app_name = mysqli_real_escape_string($mysqli, $_POST['app_name']);
	$from_name = mysqli_real_escape_string($mysqli, $_POST['from_name']);
	$from_email = mysqli_real_escape_string($mysqli, $_POST['from_email']);
	$reply_to = mysqli_real_escape_string($mysqli, $_POST['reply_to']);
	$currency = mysqli_real_escape_string($mysqli, $_POST['currency']);
	$delivery_fee = mysqli_real_escape_string($mysqli, $_POST['delivery_fee']);
	$cost_per_recipient = mysqli_real_escape_string($mysqli, $_POST['cost_per_recipient']);
	$smtp_host = mysqli_real_escape_string($mysqli, $_POST['smtp_host']);
	$smtp_port = mysqli_real_escape_string($mysqli, $_POST['smtp_port']);
	$smtp_ssl = mysqli_real_escape_string($mysqli, $_POST['smtp_ssl']);
	$smtp_username = mysqli_real_escape_string($mysqli, $_POST['smtp_username']);
	$smtp_password = mysqli_real_escape_string($mysqli, $_POST['smtp_password']);
	$login_email = mysqli_real_escape_string($mysqli, $_POST['login_email']);
	$language = mysqli_real_escape_string($mysqli, $_POST['language']);
	$choose_limit = mysqli_real_escape_string($mysqli, $_POST['choose-limit']);
	if($choose_limit=='custom')
	{
		$reset_on_day = mysqli_real_escape_string($mysqli, $_POST['reset-on-day']);
		$monthly_limit = $_POST['monthly-limit']=='' ? 0 : mysqli_real_escape_string($mysqli, $_POST['monthly-limit']);
		$current_limit = $_POST['current-limit']=='' ? 0 : mysqli_real_escape_string($mysqli, $_POST['current-limit']);
		$current_limit = ', current_quota = '.$current_limit;
		
		//Calculate month of next reset
		$today_unix_timestamp = time();
		$day_today = strftime("%e", $today_unix_timestamp);
		$month_today = strftime("%b", $today_unix_timestamp);
		$month_next = strtotime('1 '.$month_today.' +1 month');
		$month_next = strftime("%b", $month_next);
		if($day_today<$reset_on_day) $month_to_reset = $month_today;
		else $month_to_reset = $month_next;
		
		$q = 'SELECT month_of_next_reset FROM apps WHERE id = '.$id;
		$r = mysqli_query($mysqli, $q);
		if ($r && mysqli_num_rows($r) > 0) while($row = mysqli_fetch_array($r)) $monr = $row['month_of_next_reset'];
		if($monr=='') $month_of_next_reset = ', month_of_next_reset = "'.$month_to_reset.'"';
		else $month_of_next_reset = ''; //month_of_next_reset won't be changed when re-saving
	}
	else if($choose_limit=='unlimited')
	{
		$monthly_limit = -1;
		$reset_on_day = 1;
		$month_of_next_reset = ', month_of_next_reset = ""';
		$current_limit = '';
	}
	
	//------------------------------------------------------//
	//                      FUNCTIONS                       //
	//------------------------------------------------------//
	
	if($smtp_password=='')
		$q = 'UPDATE apps SET app_name = "'.$app_name.'", from_name = "'.$from_name.'", from_email = "'.$from_email.'", reply_to = "'.$reply_to.'", currency = "'.$currency.'", delivery_fee = "'.$delivery_fee.'", cost_per_recipient = "'.$cost_per_recipient.'", smtp_host = "'.$smtp_host.'", smtp_port = "'.$smtp_port.'", smtp_ssl = "'.$smtp_ssl.'", smtp_username = "'.$smtp_username.'", allocated_quota = "'.$monthly_limit.'", day_of_reset = "'.$reset_on_day.'" '.$month_of_next_reset.' '.$current_limit.' WHERE id = '.$id.' AND userID = '.get_app_info('userID');
	else
		$q = 'UPDATE apps SET app_name = "'.$app_name.'", from_name = "'.$from_name.'", from_email = "'.$from_email.'", reply_to = "'.$reply_to.'", currency = "'.$currency.'", delivery_fee = "'.$delivery_fee.'", cost_per_recipient = "'.$cost_per_recipient.'", smtp_host = "'.$smtp_host.'", smtp_port = "'.$smtp_port.'", smtp_ssl = "'.$smtp_ssl.'", smtp_username = "'.$smtp_username.'", smtp_password = "'.$smtp_password.'", allocated_quota = "'.$monthly_limit.'", day_of_reset = "'.$reset_on_day.'" '.$month_of_next_reset.' '.$current_limit.' WHERE id = '.$id.' AND userID = '.get_app_info('userID');
	$r = mysqli_query($mysqli, $q);
	if ($r)
	{
		//update email, language and company name in login
		$q2 = 'UPDATE login SET username = "'.$login_email.'", language = "'.$language.'", company = "'.$app_name.'" WHERE app = '.$id;
		$r2 = mysqli_query($mysqli, $q2);
		if ($r2)	header("Location: ".get_app_info('path'));
	}
?>