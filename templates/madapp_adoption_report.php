<link type="text/css" rel="stylesheet" href="../profile/css/jquery.percentageloader-0.1.css" />
<script type="text/javascript" src="../profile/js/jquery.min.js"></script>
<script type="text/javascript" src="../profile/js/jquery.percentageloader/src/jquery.percentageloader-0.1.js"></script>
<script type="text/javascript">
var centers = <?php echo json_encode($centers); ?>;
</script>

<form action="" method="post" class="form-area">
<?php 
$html->buildInput("city_id", 'City', 'select', $city_id, array('options' => $all_cities));
$html->buildInput("center_id", 'Center', 'select', $center_id, array('options' => $centers[$city_id]));
$html->buildInput("display_type", 'Report', 'select', '', array('options' => array(
		'volunteer_attendance' => 'Volunteer Attendance Report', 
		'student_attendance' => 'Student Attendance Report')
	));

$html->buildInput("action", '&nbsp;', 'submit', 'Show');
?>
</form><br /><br />

<?php
if($all_batches) {
	foreach ($all_batches as $batch_id => $batch) {
		$count = $batch[$display_type];
		$total = $batch['classes_total'];
		$percentage = 0;
		if($total) $percentage = $count / $total;
		?>
		<div id='loader<?php echo $batch_id ?>' class='batch_loader'>
			<h2 class='label'><?php echo $batch['name']; ?></h2>
			<script type='text/javascript'> 
				var loader<?php echo $batch_id ?> = $('#loader<?php echo $batch_id ?>').percentageLoader({width : 160, height : 160, progress : <?php echo $percentage ?>, value : '<?php echo $count ."/".$total ?>'});
			</script>
		</div>
		<?php
	}	
}
