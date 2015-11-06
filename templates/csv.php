<?php
header("Content-type: text/plain");

if(!$center_id) $all_batches = $all_centers_data;
if(!$city_id) $all_batches = $all_cities_data;

if($all_batches) {
	foreach ($all_batches as $batch_id => $batch) {
		$count = $batch[$display_type];
		$total = $batch['classes_total'];
		$percentage = 0;
		if($total) $percentage = round($count / $total * 100, 2); 

		echo "'$batch[name]', $count, $total, $percentage\n";
	}
}
