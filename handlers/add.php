<?php

/**
 * Creates a new untitled form and forwards to /poll/edit, the poll builder.
 */

$page->layout = 'admin';

$this->require_acl ('admin', 'polls');

$default = !(bool)polls\Poll::query('id')->count();

$p = new polls\Poll (array (
	'title' => 'Untitled',
	'question' => 'Please select an option.',
	'created' => gmdate ('Y-m-d H:i:s'),
	'creator' => User::$user->id,
	'edited' => gmdate ('Y-m-d H:i:s'),
	'editor' => User::$user->id,
	'fallback' => $default
));
if (!$p->put()) {
	$this->add_notification(__('Unable to create a new poll.'));
	$this->add_notification(__('Error: '. $p->error));
	@error_log('Error: Poll - '. $p->error);
	$this->redirect('/polls/admin');
}

\Versions::add ($p);
$this->redirect('/polls/edit/'. $p->id);

?>
