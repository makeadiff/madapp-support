<?php
require('../common.php');

$city_id = i($QUERY, 'city_id');
$user_type = i($QUERY, 'user_type', 'let_go');

$city_name = $sql->getOne("SELECT name FROM City WHERE id=$city_id");
$template->title = "City Volunteer Attrition for " . $city_name;
$all_verticals = $sql->getById("SELECT id,name FROM Vertical WHERE id IN (3,5,6,7,10,12,13) ORDER BY id");

$data = $sql->getAll("SELECT DISTINCT U.id,U.name,U.city_id,U.user_type,U.left_on,C.name AS city_name,DATE_FORMAT(U.left_on,'%Y-%m') AS month, G.name AS group_name, G.vertical_id
		FROM User U 
		INNER JOIN City C ON C.id=U.city_id 
		LEFT JOIN UserGroup UG ON UG.user_id=U.id AND UG.year='$year'
		INNER JOIN `Group` G ON G.id=UG.group_id
		WHERE (U.user_type='$user_type') AND U.left_on > '$year-04-01 00:00:00' AND city_id=$city_id
		ORDER BY U.left_on");

$attrition_data = array();
foreach ($data as $row) {
	$month = $row['month'];
	$vertical_id = $row['vertical_id'];
	if(!isset($attrition_data[$month])) $attrition_data[$month] = array();
	if(!isset($attrition_data[$month][$vertical_id])) $attrition_data[$month][$vertical_id] = 0;

	$attrition_data[$month][$vertical_id]++;
}


render();
