<?php 
require('./common.php');

$city_id = $_SESSION['city_id'];
$region_id = 1;
$user_type = 'fellow';

$crud = new Crud('PR_Requirement');
$all_types = array(
	'stories' => 'Stories',
	'videos' => 'Videos',
	'photographs' => 'Photograph',
	'media_articles' => "Media Articles",
	'pulse'		=> 'Pulse Plan',
	'blog'		=> "Blog Entry",
	);
$all_status = array(
	'pending' 	=> "Pending",
	'approved'	=> "Approved",
	'done'		=> "Done"
	);
$crud->addField('type','Type','varchar',array(),$all_types,'select');
$crud->addField('added_on','Added On','datetime',array(),date('Y-m-d H:i:s'));
$crud->addField('status','Status','varchar',array(),$all_status,'select');

// If Intern
if($user_type == 'fellow') {
	$crud->setListingQuery("SELECT * FROM PR_Requirement WHERE added_by_user_id=$user_id"); // Show only their own submission.
	$crud->addField("added_by_user_id", 'Intern', 'int', array(), $user_id, 'hidden');

} elseif($user_type == 'strat') {
	$crud->setListingQuery("SELECT * FROM PR_Requirement PR INNER JOIN User U ON U.id=PRC.user_id INNER JOIN City ON City.id=User.city_id WHERE City.region_id=$region_id"); // Show only their own region

	$cities_in_region = $sql->getCol("SELECT id FROM City WHERE region_id=$region_id");
	$crud->addListDataField('added_by_user_id', 'User', 'Intern', "city_id IN (".implode(',', $cities_in_region).") AND user_type='volunteer' AND status='1'");
}

render('crud.php');
