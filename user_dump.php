<?php
require('./common.php');

header("Content-type: text/plain");
header('Content-Disposition: attachment; filename="User.csv"');
$year = '2014';

$select = '';
$join = '';
$where = '';

$include_center = i($QUERY, 'center', 1);
if($include_center) {
	$select = ', Center.name AS center_name';
	$join = 'INNER JOIN UserBatch UB ON UB.user_id=U.id
			INNER JOIN Batch B ON B.id=UB.batch_id
			INNER JOIN Center ON Center.id=B.center_id';
	$where = " AND B.year='$year'";
}

$data = $sql->getAll("SELECT DISTINCT U.id,U.name,U.email,U.phone,C.name as city_name,U.sex,U.job_status, GROUP_CONCAT(DISTINCT G.name SEPARATOR ',') AS groups $select
		FROM `User` U 
			INNER JOIN City C ON C.id=U.city_id 
			INNER JOIN UserGroup UG ON UG.user_id=U.id
			INNER JOIN `Group` G ON UG.group_id=G.id
			$join
		WHERE U.status='1' AND U.user_type='volunteer' AND UG.year='$year' $where
		GROUP BY UG.user_id
		ORDER BY U.id");

foreach($data as $row) {
	print '"' . implode('","', $row) . "\"\n";
}
