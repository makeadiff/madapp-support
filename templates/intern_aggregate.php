
<h1><?php echo $template->title ?></h1>

<form action="" method="post" class="form-area">
<?php 
$html->buildInput("city_id", 'City', 'select', $city_id, array('options' => $all_cities));
$html->buildInput("vertical_id", 'Vertical', 'select', $vertical_id, array('options' => $all_verticals_with_interns));
$html->buildInput("month", 'month', 'select', $month, array('options' => $all_months));

$html->buildInput("action", '&nbsp;', 'submit', 'Show');
?>
</form><br /><br />

<table class="table table-striped">
<tr><th>Name</th><th>Credit Status</th><th>Comment</th></tr>
<?php foreach($data as $row) { ?>
<tr><td><?php echo $row['name']; ?></td><td><?php 
if($row['admin_credit'] == 1) print "<span class='positive'>Positive</span>";
elseif($row['admin_credit'] == -1) print "<span class='negative'>Negative</span>";
else print "<span class='nodata'>No Data</span>";
?></td><td><?php echo $row['comment']; ?></td></tr>
<?php } ?>
</table>