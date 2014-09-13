<?php

if (!User::require_admin()) {
	$this->redirect('/polls');
}

$page->layout = 'admin';

if (!$this->params[0]) $this->redirect('/polls/admin');

if (!$this->params[0]) {
	$this->add_notification("Error: Must provide id parameter.");
	(isset($_GET['vote'])) ? $this->redirect('/polls/votes/') : $this->redirect('/polls/admin');
}
	
if (isset($_GET['vote']) && User::require_acl('polls/votes')) {
	$item = polls\Votes::get($this->params[0]);
	$user = $item->user()->name;
	$poll = $item->poll()->title;
	if (!$item->remove()) {
		@error_log('Error: polls/delete/'. $this->params[0] .'?vote - '. $item->error);
		$this->add_notification('Error: Unable to delete user\'s vote(s).');
	} else {
		$this->add_notification("Success: Removed vote from '$poll' for '$user'.");
	}
} else if (User::require_acl('poll')) {
	$item = polls\Poll::get($this->params[0]);
	$old = $item->title;
	if (!$item->remove()) {
		@error_log('Error: polls/delete/'. $this->params[0] .' - '. $item->error);
		$this->add_notification('Error: Unable to delete poll.');
	} else {
		$this->add_notification("Success: Removed '$old'");
	}
}

$this->redirect('/polls/admin');

?>
