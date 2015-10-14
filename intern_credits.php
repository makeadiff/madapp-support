<?php
require_once('./common.php');

if(i($QUERY,'action') == 'change') {
	$new_credit = intval($QUERY['current']);

	// $sql->execQuery("UPDATE User SET admin_credit=$new_credit WHERE id=$QUERY[user_id]");

	$exists = $sql->getOne("SELECT id FROM UserCredit WHERE user_id=$QUERY[user_id] AND DATE_FORMAT(added_on, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')");

	if($exists) {
		$sql->update("UserCredit", array(
			'credit'	=> $new_credit,
			'credit_assigned_by_user_id'	=> $_SESSION['user_id'],
			'added_on'	=> 'NOW()',	
		), "id=$exists");
	} else {
		$sql->insert("UserCredit",array(
			'user_id' 	=> $QUERY['user_id'],
			'credit'	=> $new_credit,
			'credit_assigned_by_user_id'	=> $_SESSION['user_id'],
			'added_on'	=> 'NOW()',
			'year'		=> '2015',
		));
	}
}

$current_user_ka_groups = $sql->getById("SELECT Group.id, Group.name FROM `Group` INNER JOIN UserGroup ON Group.id=UserGroup.group_id WHERE UserGroup.user_id=$user_id");

$crud = new Crud('User');
$crud->setListingQuery("SELECT DISTINCT U.id,U.name,UC.credit AS admin_credit,U.phone FROM User U
	INNER JOIN UserGroup UG ON UG.user_id=U.id
	LEFT JOIN UserCredit UC ON UC.user_id=U.id AND DATE_FORMAT(UC.added_on, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
	INNER JOIN GroupHierarchy GH ON GH.group_id=UG.group_id
	WHERE GH.reports_to_group_id IN (".implode(',', array_keys($current_user_ka_groups)).")
	AND U.status='1' AND U.user_type='volunteer' AND U.city_id=$city_id
	ORDER BY U.name"); //  AND (DATE_FORMAT(UC.added_on, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m') OR ISNULL(UC.added_on))


$crud->title = 'Volunteer Credits';

$credit = '"<div class=\"btn-group\">
<span class=\"btn btn-default " . (($row["admin_credit"] == 0 or $row["admin_credit"] == "") ? "active" : "") ."\" href=\"#\"> No Data</span>
<a class=\"btn btn-success " . (($row["admin_credit"] > 0) ? "active" : "") ."\" href=\"?action=change&amp;user_id=$row[id]&amp;current=1\">+ Positive</a>
<a class=\"btn btn-danger " . (($row["admin_credit"] < 0) ? "active" : "") . "\" href=\"?action=change&amp;user_id=$row[id]&amp;current=-1\">- Negative</a>
</div>"';
$crud->addField('credit_status', 'Credit', 'virtual', array(), array('html'=> $credit));

$crud->setListingFields(array('name','credit_status'));

$crud->setFormFields();

$lock = true;
if($lock) {
	$crud->allow['add'] = false;
	$crud->allow['edit'] = false;
	$crud->allow['delete'] = false;
	$crud->allow['status_change'] = false;
}

render('crud.php');
