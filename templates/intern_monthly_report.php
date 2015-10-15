<h1><?php echo $title ?></h1>

<table class="table table-striped">
<tr><th>Name</th><?php foreach($all_months as $key => $name) { echo "<th>" . $name . "</th>"; } ?></tr>
<?php foreach($user_data as $user_id => $user) { ?>
<tr>
<td><?php echo $user['name'] ?></td>
<?php foreach($all_months as $key => $name) {
	echo "<td>";
	if(isset($user[$key])) {
		if($user[$key] == '1') echo '<span class="positive">+</span>';
		else echo '<span class="negative">-</span>';
	}
	else echo '&nbsp;';
	echo "</td>";
} ?>
</tr>
<?php } ?>
</table>
