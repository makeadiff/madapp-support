<?php
require('common.php');

$crud = new Crud("User");

$crud->setListingQuery("SELECT id,name,email,phone,address,bio, joined_on 
			FROM User 
			WHERE user_type='applicant' AND name NOT LIKE '% %' AND address NOT LIKE '%,%' AND birthday='1970-01-01'
			ORDER BY joined_on DESC");

$crud->setListingFields("name", "email", "phone", "address", "bio", "joined_on");
$crud->render();