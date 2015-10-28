<h1>Volunteer Credits</h1>

<table class="table table-striped table-bordered table-hover"><!-- Use 'data-table' class for old layout. -->
<tr class="header-row">
<th>Name</th>
<th>Credit</th>
</tr>

<?php foreach($all_users as $u) { ?>
<tr class="item-row-even even"><td><?php echo $u['name'] ?></td>
<td>
<div class="credit-area"><form action="" method="post">
<textarea name="comment" rows="3" cols="30"><?php echo $u['comment'] ?></textarea>
<?php if(!$u["admin_credit"]) { ?><span class="no-data"> No Data </span><?php } ?>
<input type="submit" name="credit" value="+ Positive" class="btn <?php echo (($u["admin_credit"] < 0 or $u["admin_credit"] == 0) ? "btn-default" : "btn-success"); ?>" />
<input type="submit" name="credit" value="- Negative" class="btn <?php echo (($u["admin_credit"] > 0 or $u["admin_credit"] == 0) ? "btn-default" : "btn-danger"); ?>" />
<input type="hidden" name="user_id" value="<?php echo $u['id'] ?>" />
<input type="hidden" name="action" value="change" /></form>
</div>
</td>
</tr>
<?php } ?>

</table>
