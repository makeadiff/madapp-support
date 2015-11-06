<h1><?php echo $template->title ?></h1>

<table class="table table-striped">
<tr><th>Count</th><th>Name</th><th>Email</th><th>Phone</th><th>Left On</th><th>Reason for Leaving</th></tr>

<?php $count=1; foreach($all_vols as $row) { ?>
<tr>
<td><?php echo $count++; ?></td>
<td><?php echo $row['name'] ?></td>
<td><?php echo $row['email'] ?></td>
<td><?php echo $row['phone'] ?></td>
<td><?php echo date('d F, Y', strtotime($row['left_on'])); ?></td>
<td><?php echo $row['reason_for_leavin'] ?></td>
</tr>

<?php } ?>
</table>
<a href="city_attrition.php?city_id=<?php echo $city_id ?>">City View</a> | <a href="attrition.php">National View</a>