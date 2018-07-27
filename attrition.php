<?php
require('../common/common.php');

$template->title = "Volunteer Attrition";
$user_type = 'let_go';
$html = new HTML;
if(isset($QUERY['action'])) $user_type = i($QUERY, 'user_type');
$from_date = i($QUERY, 'from_date', "$year-04-01");
$to_date = i($QUERY, 'to_date', ($year+1)."-03-31");

$total_vols = $sql->getById("SELECT C.id,C.name,COUNT(U.id) AS total_count
		FROM User U 
		INNER JOIN City C ON C.id=U.city_id 
		WHERE (U.user_type='volunteer')
		GROUP BY U.city_id ORDER BY C.name");

$left_vols = $sql->getById("SELECT C.id,C.name,COUNT(U.id) AS left_count
		FROM User U 
		INNER JOIN City C ON C.id=U.city_id 
		WHERE U.user_type='$user_type' AND U.left_on > '$from_date 00:00:00' AND U.left_on < '$to_date 00:00:00'
		GROUP BY U.city_id ORDER BY C.name");

render();
