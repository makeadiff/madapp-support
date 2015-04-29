<?php
require('./common.php');

$data = $sql->getAll("SELECT U.id,U.name, U.email, D.data FROM `UserData` D INNER JOIN User U ON U.id=D.user_id WHERE D.name='bank_account_number' AND data != ''");
$ifsc = $sql->getById("SELECT U.id,D.data AS ifsc FROM `UserData` D INNER JOIN User U ON U.id=D.user_id WHERE D.name='bank_ifsc_code' AND data != ''");

foreach($data as $row) {
	$bank_ifsc_code = $ifsc[$row['id']];
	print "'$row[name]','$row[email]','$row[data]','$bank_ifsc_code'\n";
}
