<?php
$year = 2015;

if(isset($config['single_user']) and $config['single_user']) {
	// Skip login
} else {
	if($config['server_host'] == 'cli') $_SESSION['user_id'] = 1;
	if(empty($_SESSION['user_id'])) {
		$url_parts = parse_url($config['site_url']);
		$domain = $url_parts['scheme'] . '://' . $url_parts['host'];
		$madapp_url = "http://makeadiff.in/madapp/";
		if(strpos($config['site_home'], 'localhost') !== false) $madapp_url = "http://localhost/Projects/Madapp/";

		header("Location: " . $madapp_url . "index.php/auth/login/" . base64_encode($domain . $config['PHP_SELF']));
		exit;
	}

	$user_id = $_SESSION['user_id'];
	$current_user = $sql->from('User')->find($user_id);
	$city_id = $current_user['city_id'];
	$current_user['groups'] = $sql->getById("SELECT G.id,G.name,G.type,G.vertical_id FROM `Group` G 
		INNER JOIN UserGroup UG ON G.id=UG.group_id
		WHERE UG.user_id=$user_id AND UG.year=$year");

	$fellow = 0;
	foreach($current_user['groups'] as $group) {
		if($group['vertical_id'] == 8 and $group['type'] = 'fellow') $fellow = 'hr';
		if($group['vertical_id'] == 7 and $group['type'] = 'fellow') $fellow = 'pr';
	}
	$current_user['fellow'] = $fellow;

	//$sql->from('UserGroup')->select('group_id')->where(array('user_id'=>$user_id, 'year'=>$year))->get('col');
}

function color() {
	static $index = 0;
	//$col = array('#EEA2AD', '#4876FF', '#1E90FF', '#00BFFF', '#00FA9A', '#76EE00','#CD950C', '#FFDEAD', '#EED5B7', '#FFA07A', '#FF6347', '#EE6363', '#71C671');
	$col = array('#f1632a','#ffe800','#282829','#22bbb8','#7e3f98','#54b847','#f1632a','#ffe800','#282829','#22bbb8','#7e3f98','#54b847','#e5002f');
	$index++;

	if($index >= count($col)) $index = 0;
	return $col[$index];
} 


function email($to, $subject, $body, $from = '') {
	//return true; //:DEBUG:
	//require("Mail.php");
	if(!$from) $from = "MADApp <madapp@makeadiff.in>";
	
	// SMTP info here!
	$host = "smtp.gmail.com";
	$username = "madapp@makeadiff.in";
	$password = "Th3C0ll3ct|v3";
	
	$headers = array ('From' => $from,
		'To' => $to,
		'Subject' => $subject);
	$smtp = Mail::factory('smtp',
		array ('host' => $host,
			'auth' => true,
			'username' => $username,
			'password' => $password));
	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
		echo("<p>" . $mail->getMessage() . "</p>");
		return false;
	}
	
	return true;
}


/// Get the starting date of the current MAD year...
function get_mad_year_starting_date() {
	$this_month = intval(date('m'));
	$months = array();
	$start_month = 4; // April
	$start_year = date('Y');
	if($this_month < $start_month) $start_year = date('Y')-1;
	return date('Y-m-d', mktime(0,0,0, $start_month, 1, $start_year));
}

/// Our Year starts on April - so get the list of months.
function get_month_list() {
	$starting_day = strtotime(get_mad_year_starting_date());
	for($i = 0; $i < 12; $i++) {
		$months[] = date('Y-m', mktime(0,0,0, date('m', $starting_day) + $i, 1, date('Y', $starting_day)));
	}
	return $months;
}

function get_all_cycles() {
	$year = date('Y', strtotime(get_mad_year_starting_date()));
	return array(
		array(), // There is no cycle 0. Keep this empty.
		array('start' => "$year-04-01", 'end' => "$year-09-14"),
		array('start' => "$year-09-15", 'end' => "$year-10-26"),
		array('start' => "$year-10-27", 'end' => "$year-12-07"),
		array('start' => "$year-12-08", 'end' => ($year+1) . "-01-18"),
		array('start' => ($year+1) . "-01-18", 'end' => ($year+1) . "-03-31")
	);
}

function get_cycle($date = false) {
	global $sql;
	
	// if(!$date) $date = date('Y-m-d');
	// else $date = date('Y-m-d', strtotime($date));

	// $all_cycles = get_all_cycles();
	// foreach($all_cycles as $cycle => $cycle_data) {
	// 	if(isset($cycle_data['start'])) {
	// 		if($date >= $cycle_data['start'] and $date <= $cycle_data['end']) return $cycle;
	// 	}
	// }

	// return 0;

	return $sql->getOne("SELECT MAX(id) FROM SS_Survey_Event");
}
