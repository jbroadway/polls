<?php

if (!User::require_admin()) {
	$this->redirect('/user/login');
}
if (!User::require_acl('polls')) {
	$this->redirect($_COOKIE['elefant_last_page']);
}
if (!User::require_acl('poll/votes') || !$this->params[0]) {
	$this->redirect('/polls/admin');
}

$page->layout = 'admin';

require_once ('apps/polls/lib/Functions.php');
$limit = 25;
$num = is_numeric($_GET['page']) ? $_GET['page'] : 0;
$offset = $num * $limit;

$items = polls\Votes::query()
	->where('poll_id',$this->params[0])
	->order('ts','desc')
	->fetch($limit, $offset);

$data = array (
	'limit' => $limit,
	'total' => polls\Votes::query()
		->where('poll_id',$this->params[0])
		->count (),
	'items' => $items,
	'count' => count ($items),
	'url' => '/polls/votes/'. $this->params[0] .'?page=%d'
);
// $page->add_style($page->wrap_script('/apps/poll/css/admin.css'));
// $page->add_style($page->wrap_script('/apps/poll/js/admin.js'));
// $page->add_style($page->wrap_script('/apps/poll/css/icons.css'));
echo View::render ('polls/votes', $data);

?>
