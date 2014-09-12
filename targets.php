<?php
require_once('./common.php');
$cycle = 1;

$current_user_ka_groups = $sql->getById("SELECT Group.id, Group.name FROM `Group` INNER JOIN UserGroup ON Group.id=UserGroup.group_id WHERE UserGroup.user_id=$user_id");

$all_users = $sql->getById("SELECT U.id, U.name FROM User U
	INNER JOIN UserGroup UG ON UG.user_id=U.id
	INNER JOIN GroupHierarchy GH ON GH.group_id=UG.group_id
	WHERE GH.reports_to_group_id IN (".implode(',', array_keys($current_user_ka_groups)).")
	AND U.status='1' AND U.user_type='volunteer'");


if(i($QUERY, 'action') == 'Save') {
	$actual_amounts = $QUERY['actual'];
	$target_amounts = $QUERY['target'];
	foreach ($all_users as $user_id => $name) {
		$sql->insert("Target_Data", array(
			'user_id'		=> $user_id,
			'target_amount' => $target_amounts[$user_id],
			'actual_amount' => $actual_amounts[$user_id],
			'cycle'			=> $cycle,
			'added_on'		=> 'NOW()')
		);
	}
}

$data = $sql->getById("SELECT user_id, target_amount, actual_amount, cycle FROM Target_Data WHERE cycle=$cycle");
// $crud = new Crud('Target_Data');
// $crud->title = 'Target App';
// $crud->addField('user_id', 'Fellow', 'int', array(), $all_users, 'select');
// $crud->setFormFields();

render();
