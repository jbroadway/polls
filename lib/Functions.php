<?php

function polls_list_all () {
	$res = polls\Poll::query ('id, title')
		->order ('title asc')
		->fetch_assoc ('id', 'title');
	$out = array ();
	foreach ($res as $k => $v) {
		$out[] = (object) array (
			'key' => $k,
			'value' => $v
		);
	}
	if (!$out) {
		$out[] = (object) array (
			'key' => '',
			'value' => '- No Polls Available -'
		);
	}
	return $out;
}

function polls_yesno () {
	$res = array('No','Yes');
	$out = array ();
	foreach ($res as $k => $v) {
		$out[] = (object) array (
			'key' => $k,
			'value' => $v
		);
	}
	return $out;
}

function polls_votes_count ($id) {
	return polls\Votes::query ()
		->where ('poll_id', $id)
		->count ();
}
?>
