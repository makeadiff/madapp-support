<?php
require_once('./common.php');

$html = new HTML;
$all_cities = $sql->getById("SELECT id,name FROM City ORDER BY name");
$all_cities[0] = 'Any';
$all_verticals_with_interns = array(6=>'Discover',7=>'PR',8=>'HR',10=>'Events',12=>'CFR', 13=>'CR');
$all_verticals_with_interns[0] = 'Any';

$all_months = array();
$this_year = $year;
for($i = 4; $i <= 15; $i++) {
	if($i > 12) {
		$month = str_pad($i-12, 2, '0', STR_PAD_LEFT);
		if($year == $this_year) $this_year++;
	}
	else $month = str_pad($i, 2, '0', STR_PAD_LEFT);

	$all_months[$this_year . '-' . $month] = date('F', strtotime($this_year . '-' . $month . '-01'));
} 


$city_id = i($QUERY, 'city_id', 1);
$vertical_id = i($QUERY, 'vertical_id', 0);

$template->title = 'Volunteer Credit Aggregate';

$data = array();
$all_users = array();

if(isset($QUERY['action'])) {
	if($vertical_id) $vertical_ids = array($vertical_id);
	else $vertical_ids = array_keys($all_verticals_with_interns);

	$raw_data = $sql->getAll("SELECT U.id,U.name,UC.credit AS admin_credit,UC.comment,DATE_FORMAT(UC.added_on, '%Y-%m') AS month FROM User U
		INNER JOIN UserGroup UG ON UG.user_id=U.id
		INNER JOIN `Group` G ON UG.group_id=G.id
		LEFT JOIN UserCredit UC ON UC.user_id=U.id AND UC.added_on > '$year-04-01 00:00:00'
		WHERE U.status='1' AND U.user_type='volunteer'
		AND U.city_id=$city_id
		AND G.vertical_id IN (".implode(',', $vertical_ids).")
		ORDER BY U.name");

	foreach ($raw_data as $row) {
		if(!isset($all_users[$row['id']])) $all_users[$row['id']] = $row['name'];

		if($row['month']) $data[$row['month']][$row['id']] = $row;
	}

}


render();
