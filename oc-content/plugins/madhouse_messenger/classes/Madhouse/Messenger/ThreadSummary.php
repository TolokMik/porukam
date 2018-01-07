<?php

class Madhouse_Messenger_ThreadSummary extends Madhouse_Messenger_ThreadSkeleton
{
    /**
	 * (MadhouseMessengerMessage) Latest message of this thread.
	 * @var Madhouse_Messenger_Message
	 */
	protected $last_message;

	/**
	 * (Int) Total number of messages in the thread.
	 * 		Used for pagination purpose to guess if there are more messages in the thread than those in $_messages array.
	 */
	protected $total;

	/**
	 * Construct.
	 *		Use static method create() instead.
	 */
	function __construct($id, $last, $total) {
		parent::__construct($id);
		$this->last_message = $last;
		$this->total = $total;
	}

	/**
	 * Gets the last message of this thread.
	 * @returns a MadhouseMessengerMessage object.
	 */
	public function getLastMessage() {
		return $this->last_message;
	}

	/**
	 * Gets the members (users) of this thread.
	 * @returns an array of MadhouseMessengerMessage.
	 */
	public function getUsers() {
	    $users = $this->getLastMessage()->getRecipients();
	    array_push($users, $this->getLastMessage()->getSender());
		return $users;
	}

	/**
	 * Tells if the current thread has more (previous) messages.
	 * @return a boolean.
	 */
	public function hasMoreMessages($p, $n)
	{
	    $to = mdh_pagination_to($p, $n, $this->getNumberOfMessages());
		return ($to < $this->getNumberOfMessages()) ? true : false;
	}

	/**
	 * Gets the total number of message in this thread.
	 * @returns an int.
	 */
	public function getNumberOfMessages()
	{
	    return $this->total;
	}

	/**
     * Return true if there is more than 2 users on this thread
     * @return boolean
     * @since 1.50.0
     */
    public function isGroup() {
        $bool = (count($this->getUsers()) > 2);
        return (count($this->getUsers()) > 2);
    }

	public function toArray() {
		return array_merge(
		    parent::toArray(),
		    array(
    			"users" => array_map(function($v) { return $v->toArray(); }, $this->getUsers())
    		)
		);
	}
}

?>