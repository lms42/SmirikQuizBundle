function assign(quiz_id, route, success_text)
{
	checked = [];
	$.each($('.admin_item_checkbox'), function(index, value){
		if (value.checked)
		{
			checked.push(value.value);
		}
	});
	h = {};
	h['ids'] = checked;
  $.post(route, $.param(h), function(data) {
		$.each($('.admin_item_checkbox'), function(index, value){
			value.checked = false;
		});
		$('#alerts').append($('#alert_template').clone().show());
  });
  
}