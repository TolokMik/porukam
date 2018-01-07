<?php

/**
 *
 *
 *
 * @since 1.10
 */
class Madhouse_Messenger_Models_Threads extends Madhouse_Messenger_Models_MessagesBase
{
	/**
	 * Singleton.
	 */
	private static $instance;

    public $messagesDAO;
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
        $this->setFields(array("id", "title", "content", "sentOn", "hidden", "reported", "root_id", "sender_id", "status_id", "event_id", "item_id"));

        $this->messagesDAO = Madhouse_Messenger_Models_Messages::newInstance();
        $this->recipientsDAO = Madhouse_Messenger_Models_Recipients::newInstance();
        $this->messageLabelsDAO = Madhouse_Messenger_Models_MessageLabels::newInstance();
        $this->labelsDAO = Madhouse_Messenger_Models_Labels::newInstance();
    }

    /**
     * Returns the list of selected fields used in find* methods.
     * @return a string;
     * @since 1.33
     */
    private function getSelectedFields()
    {
        return join(
            ",",
            array_merge(
                Madhouse_Utils_Models::getFieldsPrefixed($this, "t"),
                Madhouse_Utils_Models::getFieldsPrefixed($this, "l", "last_"),
                array(
                    "last.count AS t_count"
                )
            )
        );
    }

    /**
     * Gets a particular thread for an id, with paginated list of messages.
     * @param $threadId id of the requested thread.
     * @param $page (opt., default to 1) page requested.
     * @param $max  (opt., default to 10) number of messages per page.
     * @return array of threads (Madhouse_Messenger_Threads).
     * @throws Madhouse_NoResultsException, if the thread does not exists.
     * @since 1.00
     */
    public function findByPrimaryKey($threadId) {
    	return Madhouse_Utils_Models::get(
            $this,
    		sprintf("SELECT %s
                     FROM %s r
                     JOIN %s l ON l.id = r.message_id
                     JOIN (
                       SELECT MAX(id) AS max, COUNT(1) AS count
                       FROM %s im
                       WHERE root_id = %d
                       GROUP BY root_id
                     ) last ON r.message_id = last.max
                     JOIN %s t ON t.id = l.root_id
                     ORDER BY last.max DESC",
    				 $this->getSelectedFields(),
    				 $this->recipientsDAO->getTableName(),
    				 $this->messagesDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
    				 $threadId,
    				 $this->messagesDAO->getTableName()
    		),
            function($r, $dao) {
                return $dao->buildObject($r->row());
            }
    	);
    }

    /**
     * Returns the threads of a given user $user, paginated ($n-th, from page $page).
     * @param $user user that wants his list of threads.
     * @param $page (opt., default to 1) page requested.
     * @param $n    (opt., default to 10) number of threads per page.
     * @return an array of threads (Madhouse_Messenger_Threads),
     *         OR an empty array if none found (no exceptions thrown).
     * @since 1.00
     */
    public function findByUser($user, $filters=null, $page=null, $n=null)
    {
        return $this->findAll(
            array_merge(
                $filters,
                array("user" => $user)
            ),
            $page,
            $n
        );
    }

    public function findAll($filters = null, $page = null, $n = null)
    {
        if(is_null($filters)) {
            $filters = array();
        }

        //
        return Madhouse_Utils_Models::get(
            $this,
            sprintf("SELECT %s
                     FROM %s r
                     JOIN %s l ON l.id = r.message_id
                     JOIN (
                         SELECT MAX(im.id) AS max, COUNT(1) as count
                         FROM %s im
                         GROUP BY root_id
                     ) last ON r.message_id = last.max
                     JOIN %s t ON t.id = l.root_id
                     %s
                     %s
                     ORDER BY %s
                     %s",
                     (
                        (!isset($filters["unread"]) || !$filters["unread"]) ?
                            $this->getSelectedFields()
                        :
                            sprintf("%s, unread.count as u_count", $this->getSelectedFields())
                     ),
                     $this->recipientsDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
                     (
                        (isset($filters["user"]) && isset($filters["label"])) ?
                            $this->filterOnLabels($filters["user"], $filters["label"], "t.id")
                        :
                            ""
                     ),
                     (
                        (isset($filters["user"])) ?
                            $this->filterOnUser(
                                $filters["user"],
                                isset($filters["unread"]) ? $filters["unread"] : false
                            )
                        :
                            ""
                     ),
                     (
                        (isset($filters["user"])) ?
                            "last.max DESC"
                        :
                            "t.id DESC"
                     ),
                     (
                        (!is_null($page) && !is_null($n)) ?
                            sprintf(
                                "LIMIT %d, %d",
                                (($page-1) * $n),
                                $n
                            )
                        :
                            ""
                     )
            ),
            function ($r, $dao) {
                return array_map(
                    function($v) use ($dao) {
                        // Build an object from the array.
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
     * Counts the number (total) of threads for a user.
     * @param $userId id of the user.
     * @returns an int.
     */
    public function countByUser($user, $filters=null) {
        return Madhouse_Utils_Models::get(
            $this,
            sprintf("SELECT %s
                     FROM %s as m
                     JOIN %s as r
                        ON m.id = r.message_id
                     JOIN %s as t
                        ON t.id = m.root_id
                     %s
                     %s
                     %s",
                (
                    (isset($filters["unread"]) && $filters["unread"] === true) ?
                        "unread.count AS count"
                    :
                        "COUNT(1) AS count"
                ),
                $this->messagesDAO->getTableName(),
                $this->recipientsDAO->getTableName(),
                $this->getTableName(),
                $this->filterOnLabels($user, $filters["label"], "t.id"),
                (
                    (isset($filters["unread"]) && $filters["unread"] === true) ?
                        $this->filterUnread($user, "t.id")
                    :
                        ""
                ),
                sprintf("WHERE m.id = m.root_id AND (r.recipient_id = %d OR m.sender_id = %d)", $user->getId(), $user->getId())
            ),
            function($r, $dao) {
                return $r->rowObject()->count;
            },
            false,
            0
        );
    }

    public function findByItem($item, $page=1, $n=10)
    {
        $unread = false;
        return Madhouse_Utils_Models::get(
            $this,
            sprintf("SELECT %s
                     FROM %s r
                     JOIN %s l ON l.id = r.message_id
                     JOIN (
                        SELECT MAX(im.id) AS max, COUNT(1) as count
                        FROM %s im
                        GROUP BY root_id
                     ) last ON r.message_id = last.max
                     JOIN %s t ON t.id = l.root_id
                     WHERE t.item_id = %d
                     ORDER BY last.max DESC
                     LIMIT %d, %d",
                     (
                        (! $unread) ?
                            $this->getSelectedFields()
                        :
                            sprintf("%s, unread.count as u_count", $this->getSelectedFields())
                     ),
                     $this->recipientsDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
                     $item->getId(),
                     (($page-1) * $n),
                     $n
            ),
            function($r, $dao) {
                return array_map(
                    function($v) use ($dao) {
                        // Build an object from the array.
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
     * Finds the thread started by $user1 and $user2 (opt. about item $itemId).
     * @param $user1 the user that sent the first message.
     * @param $user2 the user that received the second message.
     * @param $itemId (default null) the id of the item linked to the thread.
     * @return the thread (Madhouse_Messenger_Thread).
     * @since 1.00
     */
    public function findByUsers($user1, $user2, $itemId=null)
    {
        if(is_null($itemId)) {
            $itemId = 0;
        }

    	return Madhouse_Utils_Models::get(
    	    $this,
    	    sprintf("SELECT %s
                     FROM %s r
                     JOIN %s l ON l.id = r.message_id
                     JOIN (
                        SELECT MAX(im.id) AS max, COUNT(1) as count
                        FROM %s im
                        GROUP BY root_id
                     ) last ON r.message_id = last.max
                     JOIN %s t ON t.id = l.root_id
                     JOIN %s rt ON rt.message_id = t.id
                     WHERE (
                            (rt.recipient_id = %d AND t.sender_id = %d)
                            OR (rt.recipient_id = %d AND t.sender_id = %d)
                        )
                        AND t.item_id = %d
                     ORDER BY last.max DESC",
    	    		 $this->getSelectedFields(),
                     $this->recipientsDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
                     $this->messagesDAO->getTableName(),
                     $this->recipientsDAO->getTableName(),
                     $user2,
                     $user1,
                     $user1,
                     $user2,
                     $itemId
    	    ),
    	    function($r, $dao) {
    	        return $dao->buildObject($r->row());
    	    },
    	    false
    	);
    }

    /**
     * Counts the total number of threads.
     * @return an int.
     * @since 1.20
     */
    public function count()
    {
        return Madhouse_Utils_Models::countWhere($this, "root_id = id");
    }

    /**
     * Counts the number of threads created today.
     * @return an int.
     * @since 1.20
     */
    public function countToday() {
        return Madhouse_Utils_Models::countToday($this, "sentOn", "root_id = id");
    }

    /**
     * Counts the number of threads created yesterday.
     * @return an int.
     * @since 1.20
     */
    public function countYesterday() {
        return Madhouse_Utils_Models::countYesterday($this, "sentOn", "root_id = id");
    }

    /**
     * Counts the number of threads created this week.
     * @return an int.
     * @since 1.20
     */
    public function countThisWeek()
    {
        return Madhouse_Utils_Models::countThisWeek($this, "sentOn", "root_id = id");
    }

    /**
     * Counts the number of threads created last week.
     * @return an int.
     * @since 1.20
     */
    public function countLastWeek()
    {
        return Madhouse_Utils_Models::countLastWeek($this, "sentOn", "root_id = id");
    }

    /**
     * Counts the number of threads created this month.
     * @return an int.
     * @since 1.20
     */
    public function countThisMonth()
    {
        return Madhouse_Utils_Models::countThisMonth($this, "sentOn", "root_id = id");
    }

    /**
     * Counts the number of threads created last month.
     * @return an int.
     * @since 1.20
     */
    public function countLastMonth()
    {
        return Madhouse_Utils_Models::countLastMonth($this, "sentOn", "root_id = id");
    }

	/**
	 * Creates (inserts) a new thread for users with a first message.
	 * TODO
	 * @returns the id of the newly created message.
	 */
	public function create($message, $itemId=null)
	{
	    $statusId = null;
	    if(mdh_messenger_status_enabled() && mdh_messenger_default_status() != 0) {
            $statusId = mdh_messenger_default_status();
	    }

		// Insert the message.
		$messageId = $this->save(array(
			"content" => $message->getContent(),
			"sender_id" => $message->getSender()->getId(),
			"sentOn" => date("Y-m-d H:i:s"),
			"item_id" => $itemId,
			"status_id" => $statusId
		));

        // Sends the message to everyone in the thread.
        $this->recipientsDAO->send($messageId, $message->getRecipients());

        $users = array_merge(array($message->getSender()), $message->getRecipients());
        $label = $this->labelsDAO->findByName("inbox");
        foreach($users as $user) {
            $this->messageLabelsDAO->add(
                $label,
                $messageId,
                $user
            );
        }

        // Get an updated version of the thread.
		return $this->findByPrimaryKey($messageId, 1, 1);
	}

    /**
     * Updates the status of a thread.
     * @param $thread (MadhouseMessengerThread) thread that needs his status changed.
     * @param $status (MadhouseMessengerStatus) the new status of the thread.
     * @return the number of updated rows.
     */
    public function updateStatus($thread, $status) {
    	if(! $thread->hasStatus() || ($thread->hasStatus() && $thread->getStatus()->getId() != $status->getId())) {
    	    // Perform the SQL query.
        	if($this->updateByPrimaryKey(array("status_id" => $status->getId()), $thread->getId()) == 0) {
        	    throw new Exception(sprintf("Setting status '%d' for thread '%d' has failed", $status->getId(), $thread->getId()));
        	}
        }
    }

	/**
	 * Returns the thread object for an SQL result set.
	 * @param $row associative array of all the infos about the thread (as returned by $this->dao->get().
	 * @returns MadhouseMessengerThread object.
	 */
	public function buildObject($row)
	{
	    // Build the last message of this thread.
    	$lastMessage = $this->messagesDAO->buildObject(
    	    array(
    	        "id" => $row["last_id"],
    	        "content" => $row["last_content"],
    	        "sentOn" => $row["last_sentOn"],
    	        "hidden" => $row["last_hidden"],
       	        "reported" => $row["last_reported"],
    	        "sender_id" => $row["last_sender_id"],
    	        "root_id" => $row["last_root_id"],
    	        "status_id" => $row["last_status_id"],
    	        "event_id" => $row["last_event_id"],
    	        "item_id" => $row["last_item_id"],
    	    ),
    	    self::buildSkeleton($row)
    	);

        // Build a ThreadSummary object.
        $thread = self::extendObject(
            new Madhouse_Messenger_ThreadSummary(
                $row["id"],
                $lastMessage,
                $row["t_count"]
            ),
            $row
        );

        // Extends informations and return.
		return $thread;
	}

	/**
	 * Builds a skeleton of threads.
	 * @param $row the associative array of data for the thread.
	 * @return Madhouse_Messenger_ThreadSkeleton object.
	 * @since 1.20
	 */
	public static function buildSkeleton($row)
	{
	    $thread = new Madhouse_Messenger_ThreadSkeleton($row["id"]);
		return self::extendObject($thread, $row);
	}

	/**
	 * Extends (completes) the thread with optional fields (title, item, status).
	 * @param $thread the thread to complete.
	 * @param $row the associative array of data for the thread.
	 * @return Madhouse_Messenger_ThreadSkeleton object.
	 * @since 1.20
	 */
    private static function extendObject($thread, $row)
    {
        // Sets the title of the thread if it has any.
		if(isset($row["title"]) && !empty($row["title"])) {
			$thread->withTitle($row["title"]);
		}

		if(isset($row["item_id"]) && $row["item_id"] != 0) {
			// An item is linked or has been linked and is not anymore.
			$item = Item::newInstance()->findByPrimaryKey($row["item_id"]);
			if(count($item) == 0) {
				// An item has been linked but is not anymore.
				$thread->withItem(-1);
			} else {
				// An item is linked and still exists.
				$thread->withItem(
					new Madhouse_Item(
						$item,
						ItemResource::newInstance()->getResource($row["item_id"])
					)
				);
			}
		} else if(isset($row["item_id"]) && $row["item_id"] == 0) {
			// No item is linked and never has been.
			$thread->withItem(NULL);
		}

		if(isset($row["status_id"]) && !empty($row["status_id"])) {
			$thread->withStatus(Madhouse_Messenger_Models_Status::newInstance()->findByPrimaryKey($row["status_id"]));
		}

        // Retrieve labels for this thread.
        $thread->setLabels(Madhouse_Messenger_Models_Labels::newInstance()->findByThread($thread));

		return $thread;
    }
}

?>