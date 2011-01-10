function setup_search_input_filter() {
	$('input.filter').each(function(fi){
		var parent_table = $(this).closest('table');
		$(this).bind('keyup change click',function(i){
			var filter_table = parent_table;
			var filter_text = $(this).val();
			if(filter_text == '') {
				restore_all(filter_table);
			} else {
				hide_filtered(filter_table,filter_text);
			}
			return true;
		});
	});

	function hide_filtered(filter_table,filter_text) {
		var current_filter_text = filter_text; 
		var table_rows = filter_table.find('tbody tr').filter(function(i){return $(this).find('th[colspan]').length==0;});
		table_rows.each(function(i) {
			var current_text = $("td:first-child a",$(this)).text();
			var filter_text_test = new RegExp(current_filter_text,'i');
			if(filter_text_test.test(current_text)) {
				$(this).show();
			} else {
				$(this).hide();
			}
		});
	}

	function restore_all(filter_table) {
		var table_rows = filter_table.find('tbody tr').filter(function(i){return $(this).find('th[colspan]').length==0;}).show();
/*		table_rows.each(function(i) {
				$(this).show();
		});*/
	}

}