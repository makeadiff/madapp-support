<?php
require_once('./common.php');

$all_citis = $sql->getById("SELECT id,name FROM City");

$verticals_with_interns = array(6,7,8,10,13); // Discover, HR, PR, Events, CR

$title = 'Intern Monthly Credit Report';

$all_months = array();
$this_year = $year;
for($i = 4; $i <= 15; $i++) {
	if($i > 12) {
		$month = str_pad($i-12, 2, '0', STR_PAD_LEFT);
		if($year == $this_year) $this_year++;
	}
	else $month = str_pad($i, 2, '0', STR_PAD_LEFT);

	$all_months[$this_year . '-' . $month] = date('F', strtotime($this_year . '-' . $month . '-01'));
} 


$city_check = " AND U.city_id=$city_id";
$vertical_check = "";
/// Enable to show only interns of the vertical of the current users
$current_user_ka_vertical = $sql->getCol("SELECT Group.vertical_id FROM `Group` INNER JOIN UserGroup ON Group.id=UserGroup.group_id WHERE UserGroup.user_id=$user_id");
if($current_user_ka_vertical) $vertical_check = " AND G.vertical_id IN (".implode(',', array_keys($current_user_ka_vertical)).")";

$all_interns = $sql->getAll("SELECT DISTINCT U.id, U.name FROM User U
	INNER JOIN UserGroup UG ON U.id=UG.user_id 
	INNER JOIN `Group` G ON G.id=UG.group_id
	WHERE U.status='1' AND U.user_type='volunteer'
	$city_check $vertical_check
	AND G.type='volunteer'
	ORDER BY U.name");

$user_data = array();
foreach ($all_interns as $u) {
	if(!isset($user_data[$u['id']])) $user_data[$u['id']] = array('name' => $u['name']);

	$all_credits = $sql->getById("SELECT DATE_FORMAT(added_on, '%Y-%m') AS id, credit FROM UserCredit WHERE user_id=$u[id]");
	foreach ($all_months as $key => $name) {
		if(isset($all_credits[$key]))
			$user_data[$u['id']][$key] = $all_credits[$key];
	}
}

render();