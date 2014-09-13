$(document).ready(function(){
	$('#poll_options').keyup(function(e) {
		var counter = $('#poll_options_count');
		if ($(this).val().length == 0){
			counter.text(0);
		} else {
			counter.text($(this).val().split("\n").filter(function(e){return e}).length);
		}
	});
	$('#poll_edit').submit(function(){
		$('.poll_submit_error').each(function(index){$(this).remove()});
		var error = false;
		var available = $('#poll_options').val().split("\n").filter(function(e){return e}).length;
		if (available != 0 && parseInt($('#poll_allowed').val()) > available) {
			$('#poll_allowed').after('<span class="poll_submit_error" style="color:red;"> Allowed choice amount cannot be more than avaliable choices.</span>');
			error = true;
		} 
		if (parseInt($('#poll_required').val()) > parseInt($('#poll_allowed').val())) {
			$('#poll_required').after('<span class="poll_submit_error" style="color:red;"> Required choice amount cannot be more than the allowed choice amount.</span>');
			error = true;
		} 
		if (error) return false;
	});
});