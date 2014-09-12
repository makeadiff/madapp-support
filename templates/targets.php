<h1>Targets</h1>

<form action="" method="post">
<table class="table table-striped table-bordered table-hover">
<tr><thead><th>Name</th><th>Target Amount</th><th>Actual Amount</th></thead></tr>
<?php foreach($all_users as $user_id => $name) { ?>
<tr><td><?php echo $name ?></td>
	<td><input type="text" name="target[<?php echo $user_id ?>]" value="<?php 
		echo (isset($data[$user_id]) ? $data[$user_id]['target_amount'] : 0) ?>" /></td>
	<td><input type="text" name="actual[<?php echo $user_id ?>]" value="<?php 
		echo (isset($data[$user_id]) ? $data[$user_id]['actual_amount'] : 0) ?>" /></td>
</tr>
<?php } ?>
</table>
<input class="btn btn-primary" name="action" type="submit" value="Save" />

</form>