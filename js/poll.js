var poll = (function($) {
	var self = {};
	self.node = false;
	self.info = false;
	self.submit = function(id) {
		if (self.info.attr('loading') !== true) {
			var out = {};
			var send = false;
			self.info.attr('loading',true);
			self.node.children('input.poll_option').each(function(index, node) {
				out[node.getAttribute('value')] = node.checked;
				if (node.checked) send = true;
			});
			if (send) {
				self.info.text('Submitting vote...');
				$.post('/polls/api/vote/'+ id, {data:out}, null, 'json'
				).done(function(res){
					if (res.success) {
						self.info.attr('current',res.data.id);
						self.info.text('Vote submitted successfully.');
						self.info.show();
						self.node.html(res.data.html);
					} else {
						self.info.text(res.error);
						self.info.show();
					}
				}).fail(function(){
					self.info.text('Vote submission failed.');
					self.info.show();
				}).always(function(){
					self.info.attr('loading',false);
					clearTimeout(self.timeout);
					self.timeout = setTimeout(function(){
						self.info.hide();
					},5000);
				});
			} else {
				self.info.text('Must select at least one choice.');
				self.info.attr('loading',false);
				self.info.show();
				clearTimeout(self.timeout);
				self.timeout = setTimeout(function(){
					self.info.hide();
				},5000);
			}
		}
	
	};
	self.get = function(id, get, force) {
		if (self.info.attr('loading') !== true) {
			self.info.attr('loading',true);
			self.info.text('Loading poll...');
			self.info.show();
			if (!force) force = '';
			$.get('/polls/api/'+ get +'/'+ id + force, {data:{}}, null, 'json'
			).done(function(res){
				if (res.success) {
					self.info.attr('data-current',res.data.id);
					self.info.text('Poll loaded successfully.');
					self.info.show();
					self.node.html(res.data.html);
				} else {
					self.info.text(res.error);
					self.info.show();
				}
			}).fail(function(){
				self.info.text('Failed to load requested poll.');
			}).always(function(){
				self.info.attr('data-loading',false);
				clearTimeout(self.timeout);
				self.timeout = setTimeout(function(){
					self.info.hide();
				},5000);
			});
		}
	};
	
	return self;
	
}(jQuery));
	
$(function() {
	poll.node = $('#poll');
	poll.info = $('#poll_info');
	poll.get(poll.info.attr('current'),'poll');
});

