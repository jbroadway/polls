Array.prototype.only_uniques = function () {
    for(var i = 0, n = this.length; i < n; i++) {
    	for(var x = 0, y = this.length; x < y; x++) {
			console.log(x + ' ' + this[x] + ' - ' + i + ' ' + this[i]);
    		if(i != x && this[x]==this[i]) return false;
    	}
    }
    return true;
};

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
		var options = $('#poll_options').val().split("\n").filter(function(e){return e});
		if (!options.only_uniques()) {
			$('#poll_options').before('<div class="poll_submit_error" style="color:red;"> Duplicate answers are not allowed.</div>');
			error = true;
		}
		if (options.length != 0 && parseInt($('#poll_allowed').val()) > options.length) {
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