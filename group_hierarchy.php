<?php
require("common.php");

$crud = new Crud('GroupHierarchy');
$crud->setListingQuery("SELECT GH.* FROM GroupHierarchy GH 
		INNER JOIN `Group` G ON G.id=GH.group_id 
		WHERE G.status='1' AND group_type='normal' 
		ORDER BY type");

$crud->addListDataField("reports_to_group_id", "`Group`");
$crud->render();
