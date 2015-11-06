
<h1><?php echo $template->title ?></h1>

<form action="" method="post" class="form-area">
<?php 
$html->buildInput("city_id", 'City', 'select', $city_id, array('options' => $all_cities));
$html->buildInput("vertical_id", 'Vertical', 'select', $vertical_id, array('options' => $all_verticals_with_interns));

$html->buildInput("action", '&nbsp;', 'submit', 'Show');
?>
</form><br /><br />

<?php if($all_users) { ?>
<table class="table table-striped">
<tr><th>Name</th><?php foreach($all_months as $key => $name) { echo "<th>" . $name . "</th>"; } ?></tr>
<?php foreach($all_users as $user_id => $user_name) { ?>
<tr>
<td><?php echo $user_name ?></td>
<?php foreach($all_months as $month_key => $month_name) {
	echo "<td align='center'>";
	if(isset($data[$month_key][$user_id])) {
		$credit = $data[$month_key][$user_id];
		if($credit['admin_credit'] == '1') echo '<span class="positive box" title="'.$credit['comment'].'">+</span>';
		else echo '<span class="negative box" title="'.$credit['comment'].'">-</span>';
	}
	else echo '&nbsp;';
	echo "</td>";
} ?>
</tr>
<?php } ?>
</table>
<?php } ?>
