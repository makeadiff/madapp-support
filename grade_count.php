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

// Students without level assignment
$orphan_students = $sql->getById("SELECT C.id, City.name AS city_name, C.name AS center_name, COUNT(S.id) AS student_count
	FROM Student S
	INNER JOIN Center C ON S.center_id=C.id
	INNER JOIN City ON City.id=C.city_id
	WHERE S.status='1' AND C.status='1'AND 
		S.id NOT IN (SELECT Stu.id FROM Student Stu
						INNER JOIN StudentLevel SL ON SL.student_id=Stu.id
						INNER JOIN Level L ON SL.level_id=L.id
						WHERE L.year='2015' AND L.status='1' AND Stu.status='1' AND L.center_id=C.id)
    GROUP BY S.center_id
    ORDER BY city_name,center_name");

$data = array();

foreach ($raw_data as $g) {
	$key = $g['center_id'];

	if(!isset($data[$key])) $data[$key] = $g;

	$data[$key][$g['grade']] = $g['student_count'];
	if(isset($orphan_students[$key])) $data[$key]['unassigned'] = $orphan_students[$key]['student_count'];
}

render();
