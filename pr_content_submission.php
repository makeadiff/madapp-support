<?php 
require('./common.php');

//dump($_SESSION);

$user = get_user_info($user_id);
$city_id = $user['city_id'];
$region_id = $user['region_id'];
$user_type = $user['group_type'];
$all_cities = $sql->getById("SELECT id,name FROM City");

$crud = new Crud('PR_Content');
$all_types = array(
	'stories'		=> 'Stories',
	'videos'		=> 'Videos',
	'collateral'	=> 'Collateral',
	'photographs'	=> 'Photograph',
	'media_articles'=> "Media Articles",
	'pulse'			=> 'Pulse Plan',
	'blog'			=> "Blog Entry",
	);
$all_status = array(
	'pending' 		=> "Pending",
	'approved'		=> "Approved",
	'rejected'		=> "Rejected"
	);
$crud->addField('type','Type','varchar',array(),$all_types,'select');
$crud->addField('added_on','Added On','datetime',array(),date('Y-m-d H:i:s'));
$crud->addField('status','Status','varchar',array(),$all_status,'select');

//$QUERY['status_updated_on'] = date('Y-m-d H:i:s');
if($region_id == 0) $cities_in_region = array_keys($all_cities);
else $cities_in_region = $sql->getCol("SELECT id FROM City WHERE region_id=$region_id");

// If Intern
if($user_type == 'intern') {
	$crud->setListingQuery("SELECT * FROM PR_Content WHERE intern_user_id=$user_id"); // Show only their own submission.
	$crud->addField("intern_user_id", 'Intern', 'int', array(), $user_id, 'hidden');

} elseif($user_type == 'fellow') {
	$crud->setListingQuery("SELECT * FROM PR_Content PRC INNER JOIN User U ON U.id=PRC.intern_user_id WHERE U.city_id=$city_id"); // Show only their own city
	$crud->addField('intern_user_id', "Intern", 'enum', array(), $sql->getById("SELECT U.id,U.name 
			FROM User U INNER JOIN UserGroup UG ON UG.user_id=U.id INNER JOIN `Group` G ON G.id=UG.group_id 
			WHERE U.city_id=$city_id AND U.user_type='volunteer' AND U.status='1' AND G.vertical_id=7"));

} else {
	$crud->setListingQuery("SELECT * FROM PR_Content PRC INNER JOIN User U ON U.id=PRC.intern_user_id INNER JOIN City ON City.id=U.city_id WHERE City.region_id=$region_id"); // Show only their own region
	$crud->addField('intern_user_id', "Intern", 'enum', array(), $sql->getById("SELECT U.id,U.name 
			FROM User U INNER JOIN UserGroup UG ON UG.user_id=U.id INNER JOIN `Group` G ON G.id=UG.group_id
			WHERE U.city_id IN (".implode(',', $cities_in_region).") AND U.user_type='volunteer' AND U.status='1' AND G.vertical_id=7"));
}

render('crud.php');
