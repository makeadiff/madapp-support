<?php
if(isset($_REQUEST['format']) and $_REQUEST['format'] == 'csv') $single_user = 1;
require 'common.php';

$html = new HTML;

$all_cities = $sql->getById("SELECT id,name FROM City WHERE type='actual' ORDER BY name");
$all_cities[0] = 'Any';
$all_centers = $sql->getById("SELECT id,name,city_id FROM Center WHERE status='1'");

$centers = array();
foreach ($all_centers as $center_id => $center) {
	if(!isset($centers[$center['city_id']])) $centers[$center['city_id']] = array();

	$centers[$center['city_id']][$center_id] = $center['name'];
	if(!isset($centers[$center['city_id']][0])) $centers[$center['city_id']][0] = 'Any';
}
$centers[0] = array('None');

$city_id = i($QUERY, 'city_id', 0);
$center_id = i($QUERY, 'center_id', 0);
$display_type = i($QUERY, 'display_type', 'volunteer_attendance');

$all_batches = array();
$all_centers_data = array();
$all_cities_data = array();

if(isset($QUERY['action']) and $QUERY['action'] == 'Show') {
	require('../reports/includes/adoption.php');
	$returns = getAdoptionData($city_id, $center_id, $all_cities, $all_centers);
	extract($returns);
}

$format = i($QUERY,'format', '');
if($format == 'csv') {
	$config['single_user'] = 1;
	$format = 'csv.php';
	$template->options['insert_layout'] = false;
}
render($format);

function sortByPercentage ($a, $b) {
	global $display_type;

	if(!$a['classes_total']) return 1;
	if(!$b['classes_total']) return -1;
	return ($a[$display_type] / $a['classes_total']) < ($b[$display_type] / $b['classes_total']);
}

