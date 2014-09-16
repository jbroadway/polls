<?php

namespace polls;

/**
 * Provides JSON API.
 */
class API extends \Restful {

	public function get_poll($id) {
		if ($id > 0) {
			$p = new Poll ($id);
			if ($p && !$p->visible) {
				return $this->error('The requested poll could not be found.');
			}
		} else {
			$p = Poll::query()
				->where('fallback',true)
				->or_where('visible',true)
				->order('id','desc')
				->single();
			$id = $p->id;
		}
		if ($p->error || !$p) {
			// no polls avaliable
			if ($p->error) {
				@error_log("Error: polls\API::get_poll($id) - ". $p->error);
			}
			return $this->error('The requested poll could not be found.');
		}
		$o = (object) $p->data;
		$o->options = $p->options;
		$o->res = (object) $p->totals(); //array('total'=>INTEGER, 'grouped'=>ARRAY, 'mine'=>ARRAY)
		$o->allowed = $p->allowed;
		$dovote = \User::require_login() ? (array_sum($o->res->mine) > 0 || !isset($_GET['vote'])) ? false : true : false;
		
		
		if ($dovote) {
			return $this->get_vote($id);
		}
		$rv = array();
		$rv['id'] = $id;
		$rv['html'] = \View::render('polls/poll',$o);
		
		return $rv;
	
	}
	public function get_vote($id) {
		if (!\User::require_login()) {
			return $this->get_poll($id);
		}
		
		$this->verify($id, 'votable');
		
		$v = Votes::query()->where('poll_id',$id)->where('user_id',\User::$user->id)->single();
		$poll = Poll::get($id);
		$options = $poll->options;
		$allowed = $poll->allowed;
		$required = $poll->required;
		$votes = $v->votes ? $v->votes : array();
		
		$rv = array();
		$rv['id'] = $id;
		$rv['html'] = \View::render(
			'polls/vote', array(
				'id'=>$id,
				'allowed'=>$allowed,
				'required'=>$required,
				'votes'=>$votes,
				'options'=>$options
			)
		);
		return $rv;
		
	}
	public function post_vote($id) {
		if (!\User::require_login()) {
			return $this->get_poll($id);
		}
		
		$error = $this->verify($id, 'submit');
		if ($error) return $this->error($error);
		
		$v = Votes::query()
			->where('poll_id',$id)
			->where('user_id',\User::$user->id)
			->single();
		if (!$v || $v->error) {
			$v = new Votes (array(
				'poll_id' => $id,
				'ts' => gmdate ('Y-m-d H:i:s'),
				'ip' => $_SERVER['REMOTE_ADDR'], //forward compatibility for future anonymous voting feature
				'user_id' => \User::$user->id
			));
		}
		$v->votes = $_POST['data'];
		
		if (!$v->put()) {
			@error_log("Error: polls\API::post_vote($id) - ". $v->error);
			return $this->error('Error occured when submitting vote.');
		}
		
		return $this->get_poll($id);
		
	}
	public function verify($id, $what) {
		if ($what == 'submit') {
			$count = 0;
			$poll = Poll::get($id);
			foreach ($_POST['data'] as $text => $state) {
				$check = false;
				foreach ($poll->options as $index => $option) {
					if ($text == $option) {
						$check = true;
						if ($state == 'true') $count += 1;
					}
				}
				if (!$check) return "'$text' is not a valid option. Please refresh the poll.";
			}
			if ($count > $poll->allowed) return 'Too many options selected.';
			if ($count < $poll->required) return 'Too few options selected.';
			return;
		}
		elseif ($what == 'vote') {
			if (Poll::get($id)->votable) return;
			else return 'Voting is disabled for this poll.';
		
		}
	}
}


?>
