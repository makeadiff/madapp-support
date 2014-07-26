<?php
// if(empty($_SESSION['user_id'])) {
// 	$url_parts = parse_url($config['site_url']);
// 	$domain = $url_parts['scheme'] . '://' . $url_parts['host'];
// 	header("Location: http://makeadiff.in/madapp/index.php/auth/login/".base64_encode($domain . $config['PHP_SELF']));
// 	exit;
// }

$year = 2013;
$user_id = 14662; //$_SESSION['user_id'];
$current_user = $sql->from('User')->find($user_id);
$city_id = $current_user['city_id'];
$current_user['groups'] = $sql->getById("SELECT G.id,G.name,G.type,G.vertical_id FROM `Group` G 
	INNER JOIN UserGroup UG ON G.id=UG.group_id
	WHERE UG.user_id=$user_id AND UG.year=$year");

foreach($current_user['groups'] as $group) {
	if($group['vertical_id'] == 8 and $group['type'] = 'fellow') $fellow = 'hr';
	if($group['vertical_id'] == 7 and $group['type'] = 'fellow') $fellow = 'pr';
}
$current_user['fellow'] = $fellow;

//$sql->from('UserGroup')->select('group_id')->where(array('user_id'=>$user_id, 'year'=>$year))->get('col');

function color() {
	static $index = 0;
	//$col = array('#EEA2AD', '#4876FF', '#1E90FF', '#00BFFF', '#00FA9A', '#76EE00','#CD950C', '#FFDEAD', '#EED5B7', '#FFA07A', '#FF6347', '#EE6363', '#71C671');
	$col = array('#f1632a','#ffe800','#282829','#22bbb8','#7e3f98','#54b847','#f1632a','#ffe800','#282829','#22bbb8','#7e3f98','#54b847','#e5002f');
	$index++;

	if($index >= count($col)) $index = 0;
	return $col[$index];
} 

