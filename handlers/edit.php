<?php

/**
 * The poll builder.
 */

$page->layout = 'admin';

$this->require_acl ('admin', 'polls');

$f = new Form('post',$this);
$p = new polls\Poll ($this->params[0]);

if ($p->error) {
	$page->title = __ ('An Error Occurred');
	echo '<p>' . __ ('The requested poll could not be found.') . '</p>';
	echo '<p>' .$p->error . '</p>';
	return;
}

if (!$f->submit()) {
	$page->title = __ ('Poll Builder');
	$o = (object) $p->data;
	$o->options = $p->options;
	$page->add_script('/apps/polls/js/poll.edit.js','head');
	echo View::render ('polls/edit', $o);
} else {
	$p->title = $_POST['title'];
	$p->question = $_POST['question'];
	$p->edited = gmdate ('Y-m-d H:i:s');
	$p->editor = User::$user->id;
	$p->votable = isset($_POST['votable']) ? 1 : 0;
	$p->visible = isset($_POST['visible']) ? 1 : 0;
	$p->fallback = isset($_POST['fallback']) ? 1 : 0;
	$p->options = isset($_POST['options']) ? array_filter(explode("\n",str_replace("\r",'',$_POST['options']))) : $p->options;
	$p->allowed = ($_POST['allowed']) ? $_POST['allowed'] : 1;
	$p->required = ($_POST['required']) ? $_POST['required'] : 1;
	if (!$p->put()) {
		$_POST['options'] = $p->options;
		$this->add_notification('Error: Unable to save poll data.');
		@error_log('Error: '. $p->error);
		$page->add_script('/apps/polls/js/poll.edit.js','head');
		echo View::render('polls/edit', $_POST);
		return;
	}
	
	if(isset($_POST['fallback'])) {
		$polls = polls\Poll::query()
			->where('fallback',true)
			->fetch();
		foreach($polls as $poll) {
			if ($poll->id != $this->params[0]) {
				$poll->fallback = 0;
				$poll->put();
			}
		}
	}
	
	if(\Versions::recent ($p) != $p) {
		\Versions::add ($p);
	}
	$this->add_notification('Success.');
	$this->redirect('/polls/admin');
}

?>
