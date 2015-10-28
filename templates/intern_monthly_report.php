<h1><?php echo $title ?></h1>

<table class="table table-striped">
<tr><th>Name</th><?php foreach($all_months as $key => $name) { echo "<th>" . $name . "</th>"; } ?></tr>
<?php foreach($user_data as $user_id => $user) { ?>
<tr>
<td><?php echo $user['name'] ?></td>
<?php foreach($all_months as $key => $name) {
	echo "<td>";
	if(isset($user[$key])) {
		$credit = $user[$key];
		if($credit['credit'] == '1') echo '<span class="positive box" title="'.$credit['comment'].'">+</span>';
		else echo '<span class="negative box" title="'.$credit['comment'].'">-</span>';
	}
	else echo '&nbsp;';
	echo "</td>";
} ?>
</tr>
<?php } ?>
</table>
