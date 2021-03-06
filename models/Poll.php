<?php

namespace polls;

class Poll extends \ExtendedModel {
	public $table = '#prefix#polls';
	public $_extended_field = 'options';
	public $fields = array (
        'votes' => array (
            'has_many' => 'polls\Votes',
			'field_name' => 'poll_id'
        )
    );
	
	// Fields in DB: id, title, desc, created, creator, edited, editor, votable, allowed, required, options
	
	public function totals () {
		$list = $this->votes();
		$list = $list ? $list : array();
		$options = $this->options;
		$options = $options ? $options : array();
		$votes = array();
		$mine = array();
		$user = \User::is_valid() ? \User::$user->id : false;
		foreach($options as $option => $text) {
			$votes[$text] = 0;
			$mine[$text] = 0;
			foreach($list as $entry) {
				foreach($entry->votes as $choice => $state) {
					if ($text == $choice && $state == 'true') {
						if ($user === $entry->user_id) $mine[$text] = 1;
						$votes[$text] += 1;
					}
				}
			}
		}
		return array('total'=>array_sum($votes),'grouped'=>$votes,'mine'=>$mine);
	}
	public function option($index) {
		return $this->options[$index];
	}
	public static function get_default() {
		return self::query()
			->where('fallback', true)
			->single();
	}
}

?>
