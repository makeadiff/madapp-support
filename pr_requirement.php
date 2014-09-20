<?php 
require('./common.php');

// $user_id = 	72918; // Strat
// $user_id = 	53407; // Fellow

$user = get_user_info($user_id);
$city_id = $user['city_id'];
$region_id = $user['region_id'];
$user_type = $user['group_type'];

$all_cities = $sql->getById("SELECT id,name FROM City");

$crud = new Crud('PR_Requirement');
$all_types = array(
	'stories'		=> 'Stories',
	'videos'		=> 'Videos',
	'photographs'	=> 'Photograph',
	'media_articles'=> "Media Articles",
	'pulse'			=> 'Pulse Plan',
	'blog'			=> "Blog Entry",
	);
$all_status = array(
	'pending' 		=> "Pending",
	'approved'		=> "Approved",
	'done'			=> "Done"
	);
$crud->addField('type','Type','varchar',array(),$all_types,'select');
$crud->addField('added_on','Added On','datetime',array(),date('Y-m-d H:i:s'));
$crud->addField('status','Status','varchar',array(),$all_status,'select');

if($user_type == 'fellow') {
	$crud->setListingQuery("SELECT * FROM PR_Requirement PRR INNER JOIN User U ON U.id=PRR.added_by_user_id WHERE U.city_id=$city_id"); // Show submission from their city

} elseif($user_type == 'strat' or $user_type == 'national' or $user_type == 'executive') {
	$crud->setListingQuery("SELECT * FROM PR_Requirement PRR INNER JOIN User U ON U.id=PRR.added_by_user_id INNER JOIN City ON City.id=U.city_id WHERE City.region_id=$region_id"); // Show only their own region

	if($region_id == 0) $cities_in_region = array_keys($all_cities);
	else $cities_in_region = $sql->getCol("SELECT id FROM City WHERE region_id=$region_id");

	$crud->addField('added_by_user_id', "Added By", 'enum', array(), $sql->getById("SELECT U.id,U.name 
			FROM User U INNER JOIN UserGroup UG ON UG.user_id=U.id INNER JOIN `Group` G ON G.id=UG.group_id
			WHERE U.city_id IN (".implode(',', $cities_in_region).") AND U.user_type='volunteer' AND U.status='1' AND 
				(G.type != 'volunteer')"));

}


// When adding, hide unnessary fields
if(i($QUERY, 'action') == 'add') {
	$crud->addField("added_by_user_id", 'Added By', 'int', array(), $user_id, 'hidden');
	$crud->addField("promised_on", 'Requried', 'date', array(), '0000-00-00', 'hidden');
	$crud->addField("delivered_on", 'Requried', 'date', array(), '0000-00-00', 'hidden');

// Send email when things are added.
} elseif(i($QUERY, 'action') == 'add_save') {
	$pr_fellow = $sql->getAssoc("SELECT U.* FROM User U INNER JOIN UserGroup UG ON U.id=UG.user_id INNER JOIN `Group` G ON G.id=UG.group_id WHERE G.vertical_id=7 AND G.type='fellow' AND U.status='1' AND U.user_type='volunteer'");
	$pr_strat = $sql->getAssoc("SELECT U.* FROM User U INNER JOIN UserGroup UG ON U.id=UG.user_id INNER JOIN `Group` G ON G.id=UG.group_id WHERE G.vertical_id=7 AND G.type='strat' AND U.status='1' AND U.user_type='volunteer'");
	$all_verticals = $sql->getById("SELECT id,name FROM Vertical");
	

	// $pr_fellow['email'] = 'binnyva@makeadiff.in';
	// $pr_strat['email'] = 'cto@makeadiff.in';

	$message = <<<END
Hey %NAME%,

A %VERTICAL% %TYPE% of %CITY% has send a PR Requirement...
Name: %TITLE%
Description: %DESCRIPTION%
Requried On: %REQDATE%

For approving or rejecting the requirement, go to...
http://makeadiff.in/apps/support/pr_requirement.php

--
MAD Tech
END;

	$replaces = array(
		'%VERTICAL%'	=> $all_verticals[$user['vertical_id']],
		'%TYPE%'	=> $user['group_type'],
		'%CITY%'	=> $all_cities[$user['city_id']],
		'%TITLE%'	=> $QUERY['name'],
		'%DESCRIPTION%'	=> $QUERY['description'],
		'%REQDATE%'	=> $QUERY['required_on'],
	);

	foreach (array($pr_fellow, $pr_strat) as $person) {
		$more_replaces = array(
			'%NAME%'	=> $person['name'],
			'%EMAIL%'	=> $person['email'],
		);

		$replaces = $replaces + $more_replaces;

		$message = str_replace(array_keys($replaces), array_values($replaces), $message);

		$success = @email($person['email'], "PR Requirement", $message);
	}
}

render('crud.php');
