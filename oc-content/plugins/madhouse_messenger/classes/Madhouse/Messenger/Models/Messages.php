<?php

/**
 * DAO class for messages.
 *
 * Messages are at the core of the messenger plugin.
 * They are organise into threads.
 *
 * @since  1.10
 */
class Madhouse_Messenger_Models_Messages extends Madhouse_Messenger_Models_MessagesBase
{
	/**
	 * Singleton.
	 */
	private static $instance;

    public $recipientsDAO;

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
        $this->setTableName('t_mmessenger_message');
        $this->setPrimaryKey("id");
        $this->setFields(array("id", "content", "sentOn", "hidden", "reported", "root_id", "sender_id", "status_id", "event_id", "item_id"));

        $this->recipientsDAO = Madhouse_Messenger_Models_Recipients::newInstance();
    }

    private function getSelectedField()
    {
        return join(
            ",",
            array_merge(
                Madhouse_Utils_Models::getFieldsPrefixed($this, "m"),
                Madhouse_Utils_Models::getFieldsPrefixed(Madhouse_Messenger_Models_Threads::newInstance(), "t", "thread_")
            )
        );
    }

    /**
     * Finds a message by its UID.
     * @param $messageId the unique-identifier of the message.
     * @return instance of Madhouse_Messenger_Message.
     * @thows Exception if query fails.
     * @since 1.10
     */
    public function findByPrimaryKey($messageId)
    {
        return Madhouse_Utils_Models::get(
            $this,
            function($dao) use ($messageId) {
                $dao->dao->select("m.*, t.id AS thread_id, t.title AS thread_title, t.status_id AS thread_status_id, t.item_id AS thread_item_id");
                $dao->dao->from($dao->getTableName() . " AS m");
                $dao->dao->join(sprintf("%s AS t", $dao->getTableName()), "t.id = m.root_id", "INNER");
                $dao->dao->where("m.id", $messageId);
            },
            function($r, $dao) {
                return $dao->buildObject($r->row());
            }
        );
    }

    /**
     * Finds the $max messages starting from page $page.
     * @param $page page number.
     * @param $max number of element to query.
     * @return Array<Madhouse_Messenger_Message>
     * @throws Exception if query fails.
     * @since 1.10
     */
    public function findAll($page=1, $max=50) {
        return Madhouse_Utils_Models::get(
            $this,
            function($dao) use ($page, $max) {
                $dao->dao->select("m.*, t.id AS thread_id, t.title AS thread_title, t.status_id AS thread_status_id, t.item_id AS thread_item_id");
                $dao->dao->from(sprintf("%s AS m", $dao->getTableName()));
                $dao->dao->join(sprintf("%s AS t", $dao->getTableName()), "t.id = m.root_id", "INNER");
                $dao->dao->orderBy(sprintf("m.id DESC LIMIT %d, %d", (($page-1) * $max), $max));
            },
            function($r, $dao) {
                return array_map(
                    function($v) use ($dao) {
                        return $dao->buildObject($v);
                    },
                    $r->result()
                );
            },
            false,
            array()
        );
    }

    /**
     * Finds all the message sent by a user.
     * @param $userId the UID of the user.
     * @param $page pagination parameter.
     * @param $max number of messages per page.
     * @return an array of Madhouse_Messenger_Message.
     */
    public function findByUser($user, $filters=null, $page=null, $max=null)
    {
        return Madhouse_Utils_Models::get(
            $this,
            function($dao) use ($user, $page, $max) {
                $dao->dao->select($dao->getSelectedField());
                $dao->dao->from(sprintf("%s AS m", $dao->getTableName()));
                $dao->dao->join(sprintf("%s AS t", $dao->getTableName()), "m.root_id = t.id");
                $dao->dao->where(sprintf("m.sender_id = %d", $user->getId()));
                if(!is_null($page) && !is_null($max)) {
                    $dao->dao->limit(($page-1) * $max, $max);
                }
                $dao->dao->orderBy("m.id", "DESC");
            },
            function($r, $dao) {
                return array_map(
                    function($v) use ($dao) {
                        return $dao->buildObject($v);
                    },
                    $r->result()
                );
            },
            false,
            array()
        );
    }

    /**
     * Gets the (total) number of messages in a thread.
     * @param $threadId the id of the thread.
     * @returns an int.
     */
    public function countByUser($user, $filters=null)
    {
        if(is_null($filters)) {
            $filters = array();
        }

        $threadsDAO = Madhouse_Messenger_Models_Threads::newInstance();

        // More complex query to handle all filters combination.
        return Madhouse_Utils_Models::get(
            $this,
            sprintf("SELECT COUNT(r.message_id) AS count
                     FROM %s as r
                     JOIN %s as m
                        ON m.id = r.message_id
                     JOIN %s as t
                        ON t.id = m.root_id
                     %s
                     %s
                     %s
                ",
                $this->recipientsDAO->getTableName(),
                $this->getTableName(),
                $threadsDAO->getTableName(),
                (
                    (isset($filters["label"])) ?
                        $this->filterOnLabels($user, $filters["label"])
                    :
                        ""
                ),
                (
                    (isset($filters["unread"]) && $filters["unread"] === true) ?
                        sprintf("WHERE r.recipient_id = %d AND r.readOn IS NULL", $user->getId())
                    :
                        sprintf("WHERE m.sender_id = %d", $user->getId())
                ),
                (
                    (isset($filters["thread"])) ?
                        sprintf(
                            "AND (m.id = %d OR m.root_id = %d)",
                            $filters["thread"]->getId(),
                            $filters["thread"]->getId()
                        )
                    :
                        ""
                )
            ),
            function($r, $dao) {
                return $r->rowObject()->count;
            },
            false,
            0
        );
    }

    /**
     * Gets messages of a particular threadId.
     * @param $threadId the thread id
     * @param $page pagination parameter.
     * @param $max number of messages per page.
     * @return an array of Madhouse_Messenger_Message objects ordered from the latest to the oldest.
     */
    public function findByThread($thread, $page=1, $max=10)
    {
        return Madhouse_Utils_Models::get(
            $this,
            function($dao) use ($thread, $page, $max) {
                $dao->dao->select($dao->getSelectedField());
                $dao->dao->from(sprintf("%s AS m", $dao->getTableName()));
                $dao->dao->join(sprintf("%s AS t", $dao->getTableName()), "t.id = m.root_id", "INNER");
                $dao->dao->where(sprintf("m.id = %d OR m.root_id = %d", $thread->getId(), $thread->getId()));
                $dao->dao->orderBy(sprintf("m.id DESC LIMIT %d, %d", (($page-1) * $max), $max));
            },
            function($r, $dao) {
                return array_map(
                    function($v) use ($dao) {
                        return $dao->buildObject($v);
                    },
                    $r->result()
                );
            }
        );
    }

    /**
     * Gets messages of a particular threadId and user.
     * @param $thread the thread id
     * @param $user the user
     * @param $page pagination parameter.
     * @param $max number of messages per page.
     * @return an array of Madhouse_Messenger_Message objects ordered from the latest to the oldest.
     */
    public function findRecievedMessages($thread, $user, $page=1, $max=10)
    {
        return Madhouse_Utils_Models::get(
            $this,
            function($dao) use ($thread, $user, $page, $max) {
                $dao->dao->select($dao->getSelectedField());
                $dao->dao->from(sprintf("%s AS m", $dao->getTableName()));
                $dao->dao->join(sprintf("%s AS t", $dao->getTableName()), "t.id = m.root_id", "INNER");
                $dao->dao->where(sprintf("(m.id = %d OR m.root_id = %d)", $thread->getId(), $thread->getId()));
                $dao->dao->where(sprintf("m.sender_id <> %d", $user->getId()));
                $dao->dao->orderBy(sprintf("m.id DESC LIMIT %d, %d", (($page-1) * $max), $max));
            },
            function($r, $dao) {
                return array_map(
                    function($v) use ($dao) {
                        return $dao->buildObject($v);
                    },
                    $r->result()
                );
            }
        );
    }

    /**
     * Gets the list of the unread messages UID of a thread.
     * @param $threadId the id of the thread
     * @param $userId id of the user that look at the thread
     * @returns an array of message UID objects ordered from the latest to the oldest.
     */
    public function findUnreadByThread($thread, $user)
    {
    	return Madhouse_Utils_Models::get(
    	    $this,
    	    function($dao) use($thread, $user) {
    	        $dao->dao->select($dao->getSelectedField());
    	        $dao->dao->from(sprintf("%s AS m", $dao->getTableName()));
    	        $dao->dao->join(sprintf("%s AS r", $dao->recipientsDAO->getTableName()), 'm.id = r.message_id', 'INNER');
                $dao->dao->join(sprintf("%s AS t", $dao->getTableName()), "t.id = m.root_id", "INNER");
    	        $dao->dao->where(sprintf("r.recipient_id = %d AND r.readOn IS NULL AND (m.id = %d OR m.root_id = %d)", $user->getId(), $thread->getId(), $thread->getId()));
    	        $dao->dao->orderBy("m.id DESC");
    	    },
    	    function($r, $dao) {
    	        return array_map(
    	            function($v) use ($dao) {
    	                return $dao->buildObject($v);
    	            },
    	            $r->result()
    	        );
    	    },
    	    false,
    	    array()
    	);
    }


    /**
     * Gets the (total) number of messages in a thread.
     * @param $threadId the id of the thread.
     * @returns an int.
     */
    public function countByThread($threadId)
    {
        return Madhouse_Utils_Models::countByField($this, "root_id", $threadId);
    }

    /**
     * Gets messages of a particular item.
     * @param $itemId the item id
     * @param $page pagination parameter.
     * @param $max number of messages per page.
     * @return an array of Madhouse_Messenger_Message objects ordered from the latest to the oldest.
     */
    public function findByItem($itemId, $page=1, $max=10)
    {
        return Madhouse_Utils_Models::get(
            $this,
            function($dao) use ($itemId, $page, $max) {
                $dao->dao->select($dao->getSelectedField());
                $dao->dao->from(sprintf("%s AS m", $dao->getTableName()));
                $dao->dao->join(sprintf("%s AS t", $dao->getTableName()), "t.id = m.root_id", "INNER");
                $dao->dao->where(sprintf("t.item_id = %d", $itemId));
                $dao->dao->orderBy(sprintf("m.id DESC LIMIT %d, %d", (($page-1) * $max), $max));
            },
            function($r, $dao) {
                return array_map(
                    function($v) use ($dao) {
                        return $dao->buildObject($v);
                    },
                    $r->result()
                );
            },
            false,
            array()
        );
    }

    public function findTemplateByUser($userId)
    {
        return Madhouse_Utils_Models::get(
            $this,
            function($dao) use ($userId) {
                $dao->dao->select($dao->getSelectedField());
                $dao->dao->from(sprintf("%s AS m", $dao->getTableName()));
                $dao->dao->join(sprintf("%s AS t", $dao->getTableName()), "t.id = m.root_id", "INNER");
                $dao->dao->where("m.root_id = m.id");
                $dao->dao->where(sprintf("m.sender_id = %d", $userId));
                $dao->dao->orderBy("m.id DESC");
                $dao->dao->limit(1);
            },
            function($r, $dao) {
                return $this->buildObject($r->row());
            }
        );
    }

    /**
     * Gets the (total) number of messages linked to an item.
     * @param $itemId the id of the thread.
     * @returns an int.
     */
    public function countByItem($itemId)
    {
        return Madhouse_Utils_Models::get(
            $this,
            function($dao) use ($itemId) {
                $dao->dao->select("COUNT(1) as count");
                $dao->dao->from($dao->getTableName() . " AS t");
                $dao->dao->join($dao->getTableName() . " AS m", "t.id = m.root_id", "INNER");
                $dao->dao->where("t.item_id", $itemId);
            },
            function($r, $dao) {
                return $r->rowObject()->count;
            }
        );
    }

    /**
     * Count today's element.
     * @since 1.10
     */
    public function countToday()
    {
        return Madhouse_Utils_Models::countToday($this, "sentOn");
    }

    public function countYesterday()
    {
        return Madhouse_Utils_Models::countYesterday($this, "sentOn");
    }

    public function countThisWeek()
    {
        return Madhouse_Utils_Models::countThisWeek($this, "sentOn");
    }

    public function countLastWeek()
    {
        return Madhouse_Utils_Models::countLastWeek($this, "sentOn");
    }

    public function countThisMonth()
    {
        return Madhouse_Utils_Models::countThisMonth($this, "sentOn");
    }

    public function countLastMonth()
    {
        return Madhouse_Utils_Models::countLastMonth($this, "sentOn");
    }

    /**
     * Builds a daily report of users with unread messages.
     * @return Array   The report where each row represent a user, his unread
     *                 messages count and the date of his last unread message.
     */
    public function reportDaily()
    {
        return Madhouse_Utils_Models::get(
            $this,
            "SELECT report.recipient_id, report.unread_count, m.sentOn AS last_date
             FROM oc_t_user u
             INNER JOIN (
                 SELECT r.recipient_id, MAX(r.message_id) AS max, COUNT(1) AS unread_count
                 FROM oc_t_mmessenger_recipients r
                 WHERE r.readOn IS NULL
                 GROUP BY r.recipient_id
             ) report ON u.pk_i_id = report.recipient_id
             INNER JOIN oc_t_mmessenger_message m ON m.id = report.max",
             function($r, $dao) {
                return array_map(
                    function($v) {
                        return array(
                            "user" => Madhouse_Utils_Models::findUserByPrimaryKey($v["recipient_id"]),
                            "count" => $v["unread_count"],
                            "date" => new \DateTime($v["last_date"])
                        );
                    },
                    $r->result()
                );
             },
             false,
             array()
        );
    }

	/**
	 * Creates (sends) a message from $senderId to each recipient in thread $threadId.
	 * @param $message the message to insert.
	 * @param $thread the thread to add the message to.
	 * @return the id of the newly created message.
	 * @since 1.00
	 */
	public function create($message, $thread)
	{
		$datas = array(
			"content" => $message->getContent(),
			"sender_id" => $message->getSender()->getId(),
			"sentOn" => date("Y-m-d H:i:s")
		);

		if($message->hasEvent()) {
			$datas["event_id"] = $message->getEvent()->getId();
		}
		if($message->hasStatus()) {
			$datas["status_id"] = $message->getStatus()->getId();
		}

	    // Save the message.
        $messageId = $this->save($datas, $thread);

        // Sends the message to everyone in the thread.
        $this->recipientsDAO->send($messageId, $message->getRecipients());

        // Get an updated version of the message.
        return $message = $this->findByPrimaryKey($messageId);
	}

    /**
     * Hides a message.
     * @param  Madhouse_Messenger_Message $message the message to hide.
     * @return void
     * @since  1.10
     */
    public function hide($message)
    {
    	// Update the messages to mark them as hidden.
        $updated = $this->updateByPrimaryKey(array("hidden" => true), $message->getId());
        if(! $updated) {
            throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
        }
    }


    /**
     * Block a single message (or a bunch of it)
     * @param  Madhouse_Messenger_Message         $m    the message to block.
     *         Array<Madhouse_Messenger_Message>  $m    the list of messages to block.
     * @param  boolean $block                           Set the value to true/false. Default is true.
     * @return int                                      Number of messages that have been blocked.
     */
    public function block($m, $block=true)
    {
        if(! is_array($m)) {
            // Update the messages to mark them as hidden.
            $updated = $this->updateByPrimaryKey(array("reported" => $block), $m);
            if($updated === false) {
                throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
            }
        } else {
            $where = sprintf("id IN (%s)", implode(",", $m));

            $updated = $this->update(
                array(
                    "reported" => $block
                ),
                array(
                    $where // reported IN ([...])
                )
            );
            if($updated === false) {
                throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
            }
        }

        return $updated;
    }

    /**
     * Block or unblock all the messages of a given user.
     * @param  Madhouse_User $user  the user that needs his messages to be blocked/unblocked.
     * @param  boolean $block       true/false, the value, to block or unblock.
     * @return Int                  number of messages that have been blocked/unblocked.
     */
    public function setBlockByUser($user, $block)
    {
        $updated = $this->update(
            array(
                "reported" => $block
            ),
            array(
                "sender_id" => $user->getId()
            )
        );
        if(! $updated) {
            throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
        }

        // Number of messages that have been blocked.
        return $updated;
    }

    /**
     * Builds a Madhouse_Messenger_Message object from the database row.
     * @param  Array $row                                the row from the database (result of a query).
     * @param  Madhouse_Messenger_ThreadSkeleton $thread the thread (skeleton) for this message.
     *                                                   Given for performance reason.
     * @return Madhouse_Messenger_Message                a newly created Madhouse_Messenger_Message object.
     * @since 1.24
     */
    public function buildObject($row, $thread=null)
    {
        if(is_null($thread)) {
            $thread = Madhouse_Messenger_Models_Threads::buildSkeleton(
                array(
                    "id" => $row["thread_id"],
                    "title" => $row["thread_title"],
                    "status_id" => $row["thread_status_id"],
                    "item_id" => $row["thread_item_id"]
                )
            );
        }

        // Retrieve the global state of the message.
        $state = $this->recipientsDAO->getState($row["id"]);

        // Actually create the message object.
        $m = new Madhouse_Messenger_Message(
            $row["content"],
            Madhouse_Utils_Models::findUserByPrimaryKey($row["sender_id"]), // Find the author., $recipients);
            Madhouse_Utils_Models::findUsersByPrimaryKey(array_keys($state)) // Find recipients.
        );

        $m->withId($row["id"]);
        $m->withHidden($row["hidden"]);
        $m->withBlocked($row["reported"]);
        $m->withSentDate($row["sentOn"]);
        $m->withState($state);

        if(! is_null($thread)) {
            $m->withThread($thread);
        }

        if(isset($row["event_id"]) && !empty($row["event_id"])) {
        	$m->withEvent(Madhouse_Messenger_Models_Events::newInstance()->findByPrimaryKey($row["event_id"]));
        }

        if(isset($row["status_id"]) && !empty($row["status_id"])) {
        	$m->withStatus(Madhouse_Messenger_Models_Status::newInstance()->findByPrimaryKey($row["status_id"]));
        }

        return $m;
    }
}

?>