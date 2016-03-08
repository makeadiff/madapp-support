<?php

$fields = array(
		'city'	=> 'City',
		'center'=> 'Center',
		'4'		=> '4',
		'5'		=> '5',
		'6'		=> '6',
		'7'		=> '7',
		'8'		=> '8',
		'9'		=> '9',
		'10'	=> '10',
		'11'	=> '11',
		'12'	=> '12',
	);
showTable($data, $fields, "Center/Children Distribution");


function showTable($data, $fields, $title='', $options=array()) { 
	?>
	<?php if($title) { ?><h1 class="title"><?php echo $title ?></h1><?php } ?>

	<table class="table table-striped">
	<tr>
	<?php foreach ($fields as $key => $name) { print "<th>$name</th>"; } ?>
	</tr>

	<?php 
	foreach($data as $row) {
		print "<tr>";
		foreach ($fields as $key => $name) { 
			print "<td>" . i($row, $key, '&nbsp;') . "</td>"; 

		} 
		print "</tr>\n";
	}
	?>
	</table>
	<?php
	if(isset($options['pager'])) $options['pager']->printPager();
}
