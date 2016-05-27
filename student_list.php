<?php
require_once('./common.php');

/// Script will output a CSV file with the format 'Student ID,City,Center,Class Section,Student Name' for all the students in the given center.

header("Content-type: text/plain");
// header("Content-type:text/octect-stream");
// header('Content-Disposition: attachment; filename="Student.csv"');

$city_id = i($QUERY,'city_id', 0);
$center_id = i($QUERY,'center_id', 0);
$header = i($QUERY, 'header', '1');
$level_year = i($QUERY, 'year', $year);

$checks = array(
		'12' => "S.name NOT LIKE '%(12%)%'",
		'11' => "S.name NOT LIKE '%(11%)%'",
	);
if($city_id) $checks['city_id'] = "C.city_id=$city_id";
if($center_id) $checks['center_id'] = "C.id=$center_id";
if($level_year == $year) $checks['status'] = "S.status='1'";

$query_level = "SELECT S.id, Cty.name AS city, C.name AS center, S.name AS student, L.grade, L.name as class_section
		FROM Student S 
		INNER JOIN Center C ON S.center_id=C.id
		INNER JOIN City Cty ON C.city_id=Cty.id
		INNER JOIN StudentLevel SL ON SL.student_id=S.id
		INNER JOIN Level L ON L.id=SL.level_id
		WHERE C.status='1' AND Cty.type='actual' AND L.year='$year' AND L.grade <= 10 AND " . implode(" AND ", $checks);

$query_all = "SELECT S.id, Cty.name AS city, C.name AS center, S.name AS student 
		FROM Student S 
		INNER JOIN Center C ON S.center_id=C.id
		INNER JOIN City Cty ON C.city_id=Cty.id
		WHERE C.status='1' AND Cty.type='actual' AND " . implode(" AND ", $checks)
		. " ORDER BY Cty.name,C.name, S.name";

// print $query_all;

$students_with_levels = $sql->getById($query_level);
$students_all = $sql->getById($query_all);

$data = array();
foreach ($students_all as $student_id => $row) {
	if(isset($students_with_levels[$student_id])) $data[$student_id] = $students_with_levels[$student_id];
	else {
		$row['grade'] = '';
		$row['class_section'] = '';
		$data[$student_id] = $row;
	}
}


print array2csv($data, $header);
