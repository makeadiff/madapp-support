<?php
require_once('./common.php');

$city_id = $_SESSION['city_id'];

$current_cycle = get_cycle();
$_GET['cycle'] = $current_cycle;

$crud = new Crud('HR_Volunteer_Request');
$all_cycles = array('Select...', "Cycle 1", "Cycle 2", "Cycle 3", "Cycle 4", "Cycle 5");
$crud->title = 'Volunteer Requirement: ' . $sql->getOne("SELECT name FROM City WHERE id=$city_id");
$crud->addField('cycle', 'Cycle', 'int', array(), $all_cycles, 'select');
$crud->addField('city_id', 'City', 'int', array(), $city_id, 'hidden');

// Currently logged in user is the HR Fellow. Show them all the requirments
if($current_user['fellow'] == 'hr') {
	$crud->addListDataField('added_by_user_id', 'User', 'Added By', "city_id=$city_id AND user_type='volunteer' AND status='1'");
	$crud->setListingFields('vertical_id','requirement_count','cycle','remarks','added_on','added_by_user_id');
	$crud->setListingQuery("SELECT * FROM HR_Volunteer_Request WHERE city_id=$city_id");

// Some random fellow has logged in. Just show them their own requests.
} else {
	$crud->setListingQuery("SELECT * FROM HR_Volunteer_Request WHERE city_id=$city_id WHERE added_by_user_id=$user_id");
	$crud->addField('added_by_user_id', 'User', 'int', array(), $user_id, 'hidden');
	$crud->setListingFields('vertical_id','requirement_count','cycle','remarks','added_on');
}

$crud->setFormFields();

// Lock data when recruitment is happening. What is, is.
$lock = false;
if($lock) {
	$crud->allow['add'] = false;
	$crud->allow['edit'] = false;
	$crud->allow['delete'] = false;
}

render('crud.php');