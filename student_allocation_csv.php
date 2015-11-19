<?php
require_once('./common.php');

/// Script will output a CSV file with the format 'City,Center,Class Section, Student Name' for all the students in the given center.

// header("Content-type: text/plain");
header("Content-type:text/octect-stream");
header('Content-Disposition: attachment; filename="Student.csv"');

$city_id = 1;
$city_name = $sql->getOne("SELECT name FROM City WHERE id=$city_id");

$data = $sql->getAll("SELECT '$city_name' AS city, C.name AS center, CONCAT(L.grade, ' ', L.name) AS class_section, S.name AS student
		FROM Student S 
		INNER JOIN StudentLevel SL ON S.id=SL.student_id 
		INNER JOIN Level L ON L.id=SL.level_id
		INNER JOIN Center C ON L.center_id=C.id
		WHERE L.year=$year AND S.status='1' AND C.status='1' AND C.city_id=$city_id
		ORDER BY C.name,L.grade, L.name,S.name");

print array2csv($data);

function array2csv($data, $show_headers = true) {
	if(!$data) return "";

	$headers = array();
	$output = '';
	foreach($data as $row) {
		if(!$headers) $headers = array_keys($row);

		$output .= '"'. implode('","', array_values($row)) . '"' . "\n";
	}
	
	if($show_headers) {
		$headers = array_map("format", $headers);
		$output =  '"' . implode('","', $headers) . '"' . "\n" . $output;
	}
	return $output;
}