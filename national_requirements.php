<?php
require_once('./common.php');

$crud = new Crud('HR_Volunteer_Request');
$all_cycles = array('Select...', "Cycle 1", "Cycle 2", "Cycle 3", "Cycle 4", "Cycle 5");

$crud->title = 'National Volunteer Requirement';

$crud->addField('cycle', 'Cycle', 'int', array(), $all_cycles, 'select');
$crud->addListDataField('city_id', 'City', 'City');
$crud->addField('recruited_count', 'People Recruited', 'virtual', array(), array(
	'function' => 'getRecCount'));

//$crud->addListDataField('added_by_user_id', 'User', 'Added By', "user_type='volunteer' AND status='1'");
$crud->setListingFields('city_id','vertical_id','requirement_count','recruited_count','cycle','remarks','added_on'); //,'added_by_user_id');

$crud->setListingQuery("SELECT * FROM HR_Volunteer_Request ORDER BY city_id");

$crud->setFormFields();

// Lock data when recruitment is happening. What is, is.
$lock = true;
if($lock) {
	$crud->allow['add'] = false;
	$crud->allow['edit'] = false;
	$crud->allow['delete'] = false;
}

render('crud.php');

function getRecCount($row) {
	global $sql;
	$all_cycles_dates = get_all_cycles();


	$num = $sql->getOne("SELECT COUNT(U.id) FROM User U
				INNER JOIN UserGroup UG ON UG.user_id=U.id 
				INNER JOIN `Group` G ON G.id=UG.group_id
				WHERE G.vertical_id='$row[vertical_id]' AND U.city_id='$row[city_id]' AND U.status='1' AND U.user_type='volunteer'
				AND U.joined_on >= '" . $all_cycles_dates[$row['cycle']]['start'] . " 00:00:00' 
				AND U.joined_on <= '" . $all_cycles_dates[$row['cycle']]['end'] . " 00:00:00'");
	return $num;
}