<?php
require_once('./common.php');

if(i($QUERY,'action') == 'change') {
	$new_credit = intval($QUERY['current']);

	$sql->execQuery("UPDATE User SET admin_credit=$new_credit WHERE id=$QUERY[user_id]");
}

$current_user_ka_groups = $sql->getById("SELECT Group.id, Group.name FROM `Group` INNER JOIN UserGroup ON Group.id=UserGroup.group_id WHERE UserGroup.user_id=$user_id");

$crud = new Crud('User');
$crud->setListingQuery("SELECT DISTINCT U.id,U.name,U.admin_credit,U.phone FROM User U
	INNER JOIN UserGroup UG ON UG.user_id=U.id
	INNER JOIN GroupHierarchy GH ON GH.group_id=UG.group_id
	WHERE GH.reports_to_group_id IN (".implode(',', array_keys($current_user_ka_groups)).")
	AND U.status='1' AND U.user_type='volunteer' AND U.city_id=$city_id");

$crud->title = 'Intern Credits';

$credit = '"<div class=\"btn-group\">
<a class=\"btn btn-success " . (($row["admin_credit"] >= 0) ? "active" : "") ."\" href=\"?action=change&amp;user_id=$row[id]&amp;current=1\">+ Positive</a>
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
