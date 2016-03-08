<?php
require_once('./common.php');

$server_host = 'localhost';
if(isset($_SERVER['HTTP_HOST'])) $server_host = $_SERVER['HTTP_HOST'];

if($server_host == 'localhost') {
	$sql->selectDb("Project_Donut");
} else {
	$sql->selectDb("cfrapp");
}

$from = i($QUERY, 'from', '2014-04-01');
$to = i($QUERY, 'to', '2014-09-31');

$main_query = "SELECT D.id,D.donation_amount,D.donation_status,CONCAT(DON.first_name,' ',DON.last_name) AS name,DON.email_id,DON.phone_no,D.created_at FROM `donations` D 
	INNER JOIN donours DON ON DON.id=D.donour_id 
	WHERE (D.donation_status='RECEIPT SENT' OR D.donation_status='DEPOSIT COMPLETE') AND D.created_at >= '$from' AND D.created_at <= '$to'
	ORDER BY D.created_at";

if(i($QUERY,'format') == 'csv') {
	$data = $sql->getAll($main_query);
	header("Content-type: text/csv");
	foreach ($data as $row) {
		$row_text = array();
		$row['created_at'] = ($row['created_at'] == '0000-00-00') ? '' : date('m/d/Y', strtotime($row['created_at']));
		foreach ($row as $key => $value) {
			$row_text[] = $value;
		}
		print '"' . implode('","',$row_text) . '"' . "\n";
	}
	exit;
}

$crud = new Crud('donations');
$crud->title = 'Donation Report';
$crud->code['top'] = '<br /><a href="?format=csv">CSV</a>';
$crud->setListingQuery($main_query);
$crud->setListingFields(array('id','donation_amount','donation_status','name','email_id','phone_no', 'created_at'));
$crud->setFormFields();

// Lock data when recruitment is happening. What is, is.
$lock = true;
if($lock) {
	$crud->allow['add'] = false;
	$crud->allow['edit'] = false;
	$crud->allow['delete'] = false;
}

render('crud.php');