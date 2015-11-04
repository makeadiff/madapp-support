<h1><?php echo $template->title ?></h1>

<table class="table table-striped">
<tr><th>Month</th><?php foreach($all_verticals as $vid=>$v) { echo "<th>$v</th>"; } ?><th>Attrition Count</th></tr>

<?php foreach($attrition_data as $month => $d) {
$total = 0;
?>
<tr>
<td><?php echo date('Y F', strtotime($month.'-01')); ?></td>
<?php foreach($all_verticals as $vid=>$v) { ?><td><a href="attrition_listing.php?vertical_id=<?php echo $vid ?>&amp;month=<?php echo $month ?>&amp;city_id=<?php echo $city_id ?>"><?php echo i($d, $vid, 0); $total += i($d, $vid, 0); ?></a></td><?php } ?>
<td><a href="attrition_listing.php?&amp;month=<?php echo $month ?>&amp;city_id=<?php echo $city_id ?>"><?php echo $total; ?></a></td>
</tr>

<?php } ?>
</table>

<a href="attrition.php">Back to National View</a>