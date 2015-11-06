<?php
require('../common.php');

$template->title = "Volunteer Attrition Listing";

$city_id = i($QUERY, 'city_id', 0);
$vertical_id = i($QUERY, 'vertical_id', 0);
$month = i($QUERY, 'month');

$vertical_check = '';
if($vertical_id) $vertical_check = " AND G.vertical_id=$vertical_id";

$all_vols = $sql->getAll("SELECT DISTINCT U.id,U.name,U.email,U.phone,U.left_on,U.reason_for_leaving
		FROM User U 
		LEFT JOIN UserGroup UG ON U.id=UG.user_id AND UG.year='$year'
		INNER JOIN `Group` G ON UG.group_id=G.id
		WHERE (U.user_type='let_go' OR U.user_type='alumni') AND DATE_FORMAT(U.left_on, '%Y-%m')='$month' AND U.city_id=$city_id $vertical_check
		ORDER BY U.name");

render();
