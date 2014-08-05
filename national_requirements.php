<?php
require_once('./common.php');

$crud = new Crud('HR_Volunteer_Request');
$all_cycles = array('Select...', "Cycle 1", "Cycle 2", "Cycle 3", "Cycle 4", "Cycle 5");
$crud->title = 'National Volunteer Requirement';

$crud->addField('cycle', 'Cycle', 'int', array(), $all_cycles, 'select');
$crud->addListDataField('city_id', 'City', 'City');
$crud->addListDataField('added_by_user_id', 'User', 'Added By', "city_id=$city_id AND user_type='volunteer' AND status='1'");
$crud->setListingFields('city_id','vertical_id','requirement_count','cycle','remarks','added_on','added_by_user_id');

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