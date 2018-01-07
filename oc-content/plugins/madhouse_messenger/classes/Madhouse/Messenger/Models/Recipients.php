<?php

/**
 *
 *
 *
 * @since 1.10
 */
class Madhouse_Messenger_Models_Recipients extends DAO
{
	/**
	 * Singleton.
	 */
	private static $instance;

	/**
	 * Singleton constructor.
	 * @return an MadhouseMessengerDAO object.
	 */
	public static function newInstance()
	{
		if(!self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}

    public function __construct()
    {
        parent::__construct();
        $this->setTableName('t_mmessenger_recipients');
        $this->setFields(
            array("message_id", "recipient_id", "sentOn", "hidden"));
        $this->setPrimaryKey("message_id"); // USELESS.
    }

    /**
     * Gets the overall state of a message.
     *
     * Gets the read and hidden state for each user receiving
     * a message.
     * @param TODO
     * @return an associative array, where key is user and value is
     * the read and hidden state for this user.
     * @since 1.10
     */
    public function getState($messageId) {
        return Madhouse_Utils_Models::get(
            $this,
            function($dao) use ($messageId) {
                $dao->dao->select();
                $dao->dao->from($dao->getTableName());
                $dao->dao->where("message_id", $messageId);
            },
            function($res, $dao) {
                $gstate = array();
                foreach ($res->result() as $r) {
                	$gstate[$r["recipient_id"]] = array(
                	    "readOn" => $r["readOn"],
                	    "hidden" => $r["hidden"]
                	);
                }

                return $gstate;
            }
        );
    }

    public function send($messageId, $recipients) {
        foreach($recipients as $r) {
            if(! $r->isFake()) {
            	$insert = $this->insert(array(
            		"message_id" => $messageId,
            		"recipient_id" => $r->getId()
            	));
            	if(!$insert) {
            	    throw new Madhouse_Messenger_NotSentException($this->dao->getErrorDesc());
            	}
            }
        }
    }

    /**
     * Mark that the messages of a particular thread have been read by a user.
     * @param $messages array of message (Madhouse_Messenger_Message object).
     * @param $userId if of the user that read the messages.
     * @return void.
     * @throw Madhouse_QueryFailedException if the SQL UPDATE failed.
     */
    public function read($messages, $user)
    {
    	// Update the messages to mark them as read by this user.
    	$this->dao->from($this->getTableName());
    	$this->dao->set(
    	    array(
    		    "readOn" => date("Y-m-d H:i:s")
    	    )
    	);
    	$this->dao->where("recipient_id", $user->getId());
    	$this->dao->whereIn(
    	    "message_id",
    	    Madhouse_Utils_Collections::getIdsFromList($messages)
    	);

    	$isUpdated = $this->dao->update();
    	if(!$isUpdated) {
    		throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
    	}
    }

    /**
     * Hides the message $messageId for user $userId.
     * @param $messageId the id of the message to hide.
     * @param $userId the id of the user that received the message.
     * @return void.
     * @throw Madhouse_QueryFailedException if the SQL UPDATE failed.
     */
    public function hide($message, $user)
    {
    	// Update the messages to mark them as read by this user.
       	$isUpdated = $this->update(
    	    array(
    	        "hidden" => true
    	    ),
    	    array(
    	        "message_id" => $message->getId(),
    	        "recipient_id" => $user->getId()
    	    )
    	);
    	if(!$isUpdated) {
    		throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
    	}
    }

    /**
     * Mark that the messages of a particular thread has unread.
     * @param $messages array of message (Madhouse_Messenger_Message object).
     * @param $userId if of the user that read the messages.
     * @return void.
     * @throw Madhouse_QueryFailedException if the SQL UPDATE failed.
     */
    public function unread($messages, $user)
    {
        // Update the messages to mark them as read by this user.
        $this->dao->from($this->getTableName());
        $this->dao->set(
            array(
                "readOn" => null
            )
        );
        $this->dao->where("recipient_id", $user->getId());
        $this->dao->whereIn(
            "message_id",
            Madhouse_Utils_Collections::getIdsFromList($messages)
        );


        $isUpdated = $this->dao->update();
        if(!$isUpdated) {
            throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
        }
    }
}

?>