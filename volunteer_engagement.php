<?php
require_once('./common.php');

$crud = new Crud('HR_Engagement');

$crud->addListDataField('added_by_user_id', 'User', 'Added By', "city_id=$city_id AND user_type='volunteer' AND status='1'");
$crud->addManyToManyField('selected_users', 'HR_UserSelect', 
	array(
		array(
			'table'		=> 'User',
			'forign_key'=> 'user_id',
			'select'	=> 'id,name',
			'where'		=> "city_id=$city_id AND user_type='volunteer' AND status='1'",
		),
		array(
			'table'		=> 'HR_Engagement',
			'forign_key'=> 'item_id',
		),
		'where' => array('item_type'=>"engagement"),
	), 'Selected Users');


if($current_user['fellow'] == 'hr') {
	$crud->setListingQuery("SELECT * FROM HR_Engagement WHERE city_id=$city_id");

} else {
	$crud->setListingQuery("SELECT * FROM HR_Engagement WHERE city_id=$city_id WHERE added_by_user_id=$user_id");
	$crud->addField('added_by_user_id', 'User', 'int', array(), $user_id, 'hidden');
}
$crud->setListingFields(array('month','selected_users','added_on','added_by_user_id'));

$crud->title = 'Engagement Request';

$crud->addField('city_id', 'City', 'int', array(), $city_id, 'hidden');
$crud->addField('month', 'Month', 'int', array(), 
	array('Select...', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'), 'select');

$crud->setFormFields();
render('crud.php');