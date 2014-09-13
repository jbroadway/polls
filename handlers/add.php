<?php

/**
 * Creates a new untitled form and forwards to /poll/edit, the poll builder.
 */

$page->layout = 'admin';

if (!User::require_admin()||!User::require_acl('poll')) {
	$this->redirect('/');
}

$p = new polls\Poll (array (
	'title' => 'Untitled',
	'desc' => 'Please select an option.',
	'created' => gmdate ('Y-m-d H:i:s'),
	'creator' => User::$user->id,
	'edited' => gmdate ('Y-m-d H:i:s'),
	'editor' => User::$user->id
));
if (!$p->put()) {
	$this->add_notification(__('Unable to create a new poll.'));
	@error_log('Error: Poll - '. $p->error);
	$this->redirect('/polls/admin');
}

\Versions::add ($p);
$this->redirect('/polls/edit/'. $p->id);

?>