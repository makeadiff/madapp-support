<?php
require_once('./common.php');

if(i($QUERY,'action') == 'change') {
	$new_credit = ($QUERY['credit'] == "+ Positive") ? "1" : "-1" ;

	// $sql->execQuery("UPDATE User SET admin_credit=$new_credit WHERE id=$QUERY[user_id]");

	$exists = $sql->getOne("SELECT id FROM UserCredit WHERE user_id=$QUERY[user_id] AND DATE_FORMAT(added_on, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')");

	if($exists) {
		$sql->update("UserCredit", array(
			'credit'	=> $new_credit,
			'comment'	=> $QUERY['comment'],
			'credit_assigned_by_user_id'	=> $_SESSION['user_id'],
			'added_on'	=> 'NOW()',	
		), "id=$exists");
	} else {
		$sql->insert("UserCredit",array(
			'user_id' 	=> $QUERY['user_id'],
			'credit'	=> $new_credit,
			'comment'	=> $QUERY['comment'],
			'credit_assigned_by_user_id'	=> $_SESSION['user_id'],
			'added_on'	=> 'NOW()',
			'year'		=> '2015',
		));
	}
}

/// Enable to show only interns of the vertical of the current users
$current_user_ka_groups = $sql->getById("SELECT Group.id, Group.name FROM `Group` 
	INNER JOIN UserGroup ON Group.id=UserGroup.group_id 
	WHERE UserGroup.user_id=$user_id");
$subordinate_groups = $sql->getCol("SELECT G.id FROM `Group` G
	INNER JOIN GroupHierarchy GH ON GH.group_id=G.id 
	WHERE GH.reports_to_group_id IN (" . implode(",", array_keys($current_user_ka_groups)) . ")");

$all_users = $sql->getAll("SELECT DISTINCT U.id,U.name,UC.credit AS admin_credit,U.phone,UC.comment FROM User U
	INNER JOIN UserGroup UG ON UG.user_id=U.id
	LEFT JOIN UserCredit UC ON UC.user_id=U.id AND DATE_FORMAT(UC.added_on, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
	WHERE U.status='1' AND U.user_type='volunteer'
	AND U.city_id=$city_id
	AND UG.group_id IN (".implode(',', $subordinate_groups).")
	ORDER BY U.name"); //  AND (DATE_FORMAT(UC.added_on, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m') OR ISNULL(UC.added_on))

render();
