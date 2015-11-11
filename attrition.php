<?php
require('../common.php');

$template->title = "Volunteer Attrition";
$user_type = 'let_go';
$html = new HTML;
if(isset($QUERY['action'])) $user_type = i($QUERY, 'user_type');

$total_vols = $sql->getById("SELECT C.id,C.name,COUNT(U.id) AS total_count
		FROM User U 
		INNER JOIN City C ON C.id=U.city_id 
		WHERE (U.user_type='volunteer')
		GROUP BY U.city_id ORDER BY C.name");

$left_vols = $sql->getById("SELECT C.id,C.name,COUNT(U.id) AS left_count
		FROM User U 
		INNER JOIN City C ON C.id=U.city_id 
		WHERE (U.user_type='$user_type') AND U.left_on > '$year-04-01 00:00:00'
		GROUP BY U.city_id ORDER BY C.name");

render();
