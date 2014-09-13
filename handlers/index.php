<?php

if (!$this->internal) {
	$page->window_title = "Polls";
}

$id = (isset ($this->params[0])) ? (int) $this->params[0] : (isset ($data['id']) ? (int) $data['id'] : 0);
$async = (isset($data['async']) && $data['async']) ? true : false;

$page->add_script('/apps/polls/js/poll.js','head');

if (!$this->internal || $async) {
	echo View::render('polls/head', array(
		'polls' => polls\Poll::query()
			->where('visible',true)
			->order('id','desc')
			->fetch_assoc('id','title'),
		'active' => $id
	));
}
echo View::render('polls/index',array('current'=>$id));


?>
