<?php
require('./common.php');

header("Content-type: text/plain");
header('Content-Disposition: attachment; filename="User.csv"');
$year = '2014';

$data = $sql->getAll("SELECT DISTINCT U.id,U.name,U.email,U.phone,C.name as city_name,U.sex,U.job_status,Center.name AS center_name, GROUP_CONCAT(DISTINCT G.name SEPARATOR ',') AS groups
		FROM `User` U 
			INNER JOIN City C ON C.id=U.city_id 
			INNER JOIN UserBatch UB ON UB.user_id=U.id
			INNER JOIN Batch B ON B.id=UB.batch_id
			INNER JOIN Center ON Center.id=B.center_id
			INNER JOIN UserGroup UG ON UG.user_id=U.id
			INNER JOIN `Group` G ON UG.group_id=G.id
		WHERE U.status='1' AND U.user_type='volunteer' AND B.year='$year' AND UG.year='$year'
		GROUP BY UG.user_id
		ORDER BY U.id");

foreach($data as $row) {
	print '"' . implode('","', $row) . "\"\n";
}
