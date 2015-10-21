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


/// Enable to show only interns of the vertical of the current users
$current_user_ka_groups = $sql->getById("SELECT Group.id, Group.name FROM `Group` 
	INNER JOIN UserGroup ON Group.id=UserGroup.group_id 
	WHERE UserGroup.user_id=$user_id");
$subordinate_groups = $sql->getCol("SELECT G.id FROM `Group` G
	INNER JOIN GroupHierarchy GH ON GH.group_id=G.id 
	WHERE GH.reports_to_group_id IN (" . implode(",", array_keys($current_user_ka_groups)) . ")");
$hc_group = 5;
if(in_array($hc_group, array_keys($current_user_ka_groups))) { // If the current guy is an HC multiplier, 
	$intern_groups = $sql->getCol("SELECT id FROM `Group` WHERE status='1' AND type='volunteer' AND group_type='normal' AND vertical_id!='3' AND vertical_id!='5'"); // All interns except Ed support and Propel
	$subordinate_groups = array_merge($subordinate_groups, $intern_groups);
}

$all_interns = $sql->getAll("SELECT DISTINCT U.id, U.name FROM User U
	INNER JOIN UserGroup UG ON U.id=UG.user_id 
	WHERE U.status='1' AND U.user_type='volunteer'
	AND U.city_id=$city_id
	AND UG.group_id IN (" . implode(",", $subordinate_groups) . ")
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
