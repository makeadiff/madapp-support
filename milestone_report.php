<?php
require_once('./common.php');
// Region
// Vertical
// Fellow
// Milestone
// Assinged date
// Completed date
// Status

$all_regions = $sql->getById("SELECT id,name FROM Region");
$all_cities = $sql->getById("SELECT id,name FROM City");
$all_verticals = $sql->getById("SELECT id,name FROM Vertical");
$all_verticals['0'] = 'None';

$main_query = "SELECT DISTINCT M.id,G.vertical_id,U.city_id,U.name,M.name AS milestone,M.due_on,M.done_on,M.status 
	FROM `Review_Milestone` M 
	INNER JOIN `User` U ON M.user_id=U.id
	INNER JOIN `UserGroup` UG ON UG.user_id=U.id
	INNER JOIN `Group` G ON G.id=UG.group_id";
$fields = array('city_id','vertical_id','name','milestone','due_on','done_on','status');

if(i($QUERY,'format') == 'csv') {

	$data = $sql->getAll($main_query);
	foreach ($data as $row) {
		$row_text = array();
		$row['due_on'] = ($row['due_on'] == '0000-00-00') ? '' : date('m/d/Y', strtotime($row['due_on']));
		$row['done_on'] = ($row['done_on'] == '0000-00-00 00:00:00') ? '' : date('m/d/Y', strtotime($row['done_on']));
		foreach ($row as $key => $value) {
			if($key == 'city_id') $value = $all_cities[$value];
			elseif($key == 'vertical_id') $value = $all_verticals[$value];
			$row_text[] = $value;
		}
		print '"' . implode('","',$row_text) . '"' . "\n";
	}
	exit;
}

$crud = new Crud('Review_Milestone');
$crud->title = 'Milestone Report';
$crud->code['top'] = '<br /><a href="?format=csv">CSV</a>';
$crud->addField('city_id', 'City', 'int', array(), $all_cities, 'select');
$crud->addField('vertical_id', 'Vertical', 'int', array(), $all_verticals, 'select');
$crud->addField('milestone', 'Milestone', 'text');
$crud->setListingFields($fields); //,'added_by_user_id');

$crud->setListingQuery($main_query);

$crud->setFormFields();

// Lock data when recruitment is happening. What is, is.
$lock = true;
if($lock) {
	$crud->allow['add'] = false;
	$crud->allow['edit'] = false;
	$crud->allow['delete'] = false;
}

render('crud.php');