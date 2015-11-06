<h1><?php echo $template->title ?></h1>

<table class="table table-striped">
<tr><th>City</th><th>Total Volunteers</th><th>Attrition</th><th>Attrition Percentage</th></tr>

<?php
$total_total = 0;
$total_attrition = 0;

foreach($total_vols as $city_id => $info) { ?>
<tr><td title="City ID : <?php echo $city_id ?>"><a href="city_attrition.php?city_id=<?php echo $city_id ?>"><?php echo $info['name'] ?></a></td><td><?php echo $info['total_count'] ?></td>
<td><?php echo $left_vols[$info['id']]['left_count'] ?></td><td><?php echo round(($left_vols[$info['id']]['left_count'] / $info['total_count']) * 100, 2) ?></td></tr>
<?php 
	$total_total += $info['total_count'];
	$total_attrition += $left_vols[$info['id']]['left_count'];
} ?>
<tr><td>National</td><td><?php echo $total_total ?></td><td><?php echo $total_attrition ?></td><td><?php echo round(($total_attrition / $total_total) * 100, 2) ?></td></tr>
</table>

