<?php
require_once('./common.php');

$html = new HTML;
$all_cities = $sql->getById("SELECT id,name FROM City");
$all_cities[0] = 'Any';
$all_verticals_with_interns = array(6=>'Discover',7=>'PR',8=>'HR',10=>'Events',13=>'CR');
$all_verticals_with_interns[0] = 'Any';
$all_months = array();
for($i=4; $i<=15; $i++) {
	$month_number = $i;
	$year_number = $year;
	if($i > 12) {
		$month_number -= 12;
		$year_number++;
	}

	$month_key = "$year_number-" . str_pad($month_number,2,'0', STR_PAD_LEFT). '-01';
	if($month_key > date("Y-m-d")) break;

	$all_months[$month_key] = $year_number . ' ' . date('F', strtotime($month_key));
}

$city_id = i($QUERY, 'city_id', 1);
$vertical_id = i($QUERY, 'vertical_id', 0);
$month = i($QUERY, 'month', date('Y-m-d'));

$template->title = 'Volunteer Credit Aggregate for ' .  date('F Y', strtotime($month));

$data = array();
if(isset($QUERY['action'])) {
	if($vertical_id) $vertical_ids = array($vertical_id);
	else $vertical_ids = array_keys($all_verticals_with_interns);

	$data = $sql->getAll("SELECT DISTINCT U.id,U.name,UC.credit AS admin_credit,UC.comment FROM User U
		INNER JOIN UserGroup UG ON UG.user_id=U.id
		INNER JOIN `Group` G ON UG.group_id=G.id
		LEFT JOIN UserCredit UC ON UC.user_id=U.id AND DATE_FORMAT(UC.added_on, '%Y-%m') = DATE_FORMAT('$month', '%Y-%m')
		WHERE U.status='1' AND U.user_type='volunteer'
		AND U.city_id=$city_id
		AND G.vertical_id IN (".implode(',', $vertical_ids).")
		ORDER BY U.name");
}


render();
