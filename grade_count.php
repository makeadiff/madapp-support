<?php
require 'common.php';

$raw_data = $sql->getAll("SELECT C.id AS center_id, Cty.name AS city, C.name AS center, L.grade, COUNT(S.id) AS student_count FROM Student S 
		INNER JOIN StudentLevel SL ON SL.student_id=S.id
		INNER JOIN Level L ON L.id=SL.level_id
		INNER JOIN Center C ON C.id=L.center_id 
		INNER JOIN City Cty ON Cty.id=C.city_id 
		WHERE L.year=$year AND C.status='1' AND Cty.type='actual'
		GROUP BY C.id, L.grade
		ORDER BY Cty.name, C.name, L.grade");

$data = array();

foreach ($raw_data as $g) {
	$key = $g['center_id'];

	if(!isset($data[$key])) $data[$key] = $g;

	$data[$key][$g['grade']] = $g['student_count'];
}

render();
