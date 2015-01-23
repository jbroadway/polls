<?php

$this->require_admin ();

$page->layout = 'admin';

$cur = $this->installed ('polls', $appconf['Admin']['version']);

if ($cur === true) {
	$page->title = 'Already installed';
	echo '<p><a href="/polls/admin">Continue</a></p>';
	return;
} elseif ($cur !== false) {
	header ('Location: /' . $appconf['Admin']['upgrade']);
	exit;
}

$page->title = 'Installing App: Polls';

$conn = conf ('Database', 'master');
$driver = $conn['driver'];
DB::beginTransaction ();

$error = false;
$sqldata = sql_split (file_get_contents ('apps/polls/conf/install_' . $driver . '.sql'));
foreach ($sqldata as $sql) {
	if (! DB::execute ($sql)) {
		$error = DB::error ();
		break;
	}
}

if ($error) {
	DB::rollback ();
	@error_log('Error: polls/install - ' . $error);
	echo '<p>Install failed.</p>';
	return;
}
DB::commit ();

echo '<p><a href="/polls/admin">Done.</a></p>';

$this->mark_installed ('polls', $appconf['Admin']['version']);

?>