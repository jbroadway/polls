<?php

namespace polls;

class Votes extends \ExtendedModel {
	public $table = '#prefix#polls_votes';
	public $_extended_field = 'votes';
	public $fields = array (
        'poll' => array (
            'belongs_to' => 'polls\Poll',
			'field_name' => 'id'
        )
    );
	
	// Fields in DB: id, poll_id, ts, user_id, votes
}

?>
