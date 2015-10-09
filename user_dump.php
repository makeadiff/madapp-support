<?php
require('./common.php');
/// Create a CSV File needed to import all vols into Zoho CRM
/*
    Name
    Email
    Phone Number
    Vertical
    MAD City
    Fellow/Volunteer/Strat

The vertical should be only one and the one in which he is active(Ed Support/Propel/Discover/Events/VFR)
*/

header("Content-type: text/plain");
//header('Content-Disposition: attachment; filename="User.csv"');
$year = '2015';
$all_verticals = $sql->getById("SELECT id,name FROM Vertical");
$data = $sql->getAll("SELECT U.name,U.email,U.phone,C.name as city_name, GROUP_CONCAT(G.type SEPARATOR ',') AS groups, GROUP_CONCAT(G.vertical_id SEPARATOR ',') AS vertical_ids
		FROM `User` U 
			INNER JOIN City C ON C.id=U.city_id 
			INNER JOIN UserGroup UG ON UG.user_id=U.id
			INNER JOIN `Group` G ON UG.group_id=G.id
			INNER JOIN Vertical V ON G.vertical_id=V.id
		WHERE U.status='1' AND U.user_type='volunteer' AND UG.year='$year'
		GROUP BY UG.user_id");
		//LIMIT 0, 100");
		// ORDER BY G.type='executive' DESC, G.type='national' DESC, G.type='fellow' DESC, G.type='volunteer' DESC");

$all_type = array('executive' => 5, 'national' => 4, 'strat'=>3, 'fellow' => 2, 'volunteer' => 1);
foreach($data as $row) {
	//if($row['name'] != "Anjana Shekhar") continue;

	$groups = explode(",", $row['groups']);
	$vertical_ids = explode(",", $row['vertical_ids']);
	$higest_index = 0;
	$higest = 0;
	$higest_type = 'volunteer';
	foreach ($groups as $index => $type) {
		if($all_type[$type] > $higest) {
			$higest = $all_type[$type];
			$higest_type = $type;
			$higest_index = $index;
		}
	}
	$higest_vertical = $all_verticals[$vertical_ids[$higest_index]];
	unset($row['groups']);
	unset($row['vertical_ids']);
	$row['vertical'] = $higest_vertical;


	print '"' . implode('","', $row) . "\"\n";
}
