function init() {
	// Show selected city's centers. 
	$("#city_id").change(function() {
		var select = "<select id='center_id'>";
		var city_id = this.value;

		var centers_in_city = centers[city_id];
		for(var center_id in centers_in_city) {
			select += "<option value='"+center_id+"'>"+centers_in_city[center_id]+"</option>";
		}
		select += '</select>';

		$("#center_id").html(select);
	});
}