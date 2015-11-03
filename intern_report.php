<?php
require_once('./common.php');

// $all_cities = $sql->getById("SELECT id,name FROM City");
$all_verticals_with_interns = array(6=>'Discover',7=>'HR',8=>'PR',10=>'Events',13=>'CR');

/// Enable to show only interns of the vertical of the current users
// $current_user_ka_vertical = $sql->getCol("SELECT Group.vertical_id FROM `Group` INNER JOIN UserGroup ON Group.id=UserGroup.group_id WHERE UserGroup.user_id=$user_id");
// $where = " AND G.vertical_id IN (".implode(',', array_keys($current_user_ka_groups)).")";

$crud = new Crud('City');
$crud->title = 'Intern Credit Report';

$total_sql = "SELECT COUNT(U.id) FROM User U
	INNER JOIN UserGroup UG ON U.id=UG.user_id 
	INNER JOIN `Group` G ON G.id=UG.group_id
	WHERE U.status='1' AND U.user_type='volunteer' AND G.vertical_id IN (".implode(',',array_keys($all_verticals_with_interns)).") 
	AND U.city_id=%id% AND G.type='volunteer'";

$positive_sql = $total_sql . " AND U.admin_credit >= 0";

$crud->addField('positive_interns', 'Positive Interns', 'virtual', array(), array('sql'=> $positive_sql));
$crud->addField('total_interns', 'Total Interns', 'virtual', array(), array('sql'=> $total_sql));
$crud->setListingFields(array('name','positive_interns', 'total_interns'));
$crud->setFormFields();

$lock = true;
if($lock) {
	$crud->allow['add'] = false;
	$crud->allow['edit'] = false;
	$crud->allow['delete'] = false;
	$crud->allow['status_change'] = false;
}

render('crud.php');
