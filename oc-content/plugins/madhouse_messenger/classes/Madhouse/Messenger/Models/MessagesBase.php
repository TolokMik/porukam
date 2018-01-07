<?php

abstract class Madhouse_Messenger_Models_MessagesBase extends DAO
{
    public function filterOnUser($user, $unread = false)
    {
        return ($unread === true) ?
            $this->filterUnread($user, "t.id")
        :
            sprintf(
                "WHERE (r.recipient_id = %d OR l.sender_id = %d)",
                $user->getId(),
                $user->getId()
            )
        ;
    }

    public function filterUnread($user, $threadId=null)
    {
        if(is_null($threadId)) {
            $threadId = "t.id";
        }

        return sprintf(
            "JOIN (
                SELECT COUNT(1) as count, um.root_id
                FROM %s ur
                JOIN %s um ON um.id = ur.message_id
                WHERE ur.readOn IS NULL AND ur.recipient_id = %s
                GROUP BY um.root_id
            ) unread ON unread.root_id = %s",
            Madhouse_Messenger_Models_Recipients::newInstance()->getTableName(),
            Madhouse_Messenger_Models_Messages::newInstance()->getTableName(),
            $user->getId(),
            $threadId
        );
    }

    public function filterOnLabels($user, $labels, $threadId=null)
    {
        if(is_null($threadId)) {
            $threadId = "t.id";
        }

        return (
            ($labels instanceof Madhouse_Messenger_Label) ?
                sprintf(
                    "JOIN %s ml
                        ON (
                            ml.fk_i_message_id = %s
                            AND ml.fk_i_label_id = %d
                            AND ml.fk_i_user_id = %d
                        )",
                    Madhouse_Messenger_Models_MessageLabels::newInstance()->getTableName(),
                    $threadId,
                    $labels->getId(),
                    $user->getId()
                )
            :
                ""
         );
    }

    /**
     * Save the message in database.
     *     (Don't send it, just save its content/sender/status/event/etc)
     * @param $datas array of data
     * @param $thread (optional) thread to attach the message to.
     * @return the id of the message.
     * @throws Madhouse_QueryFailedException, if something goes wrong inserting/updating.
     * @since 1.22
     */
    protected function save($datas, $thread=null)
    {
        // Insert the message.
    	$isInserted = parent::insert($datas);
    	if(!$isInserted) {
    		throw new Madhouse_QueryFailedException(__("Saving the message has failed.", mdh_current_plugin_name()));
    	}

    	// Gets the last inserted id (our message id).
    	$messageId = $this->dao->insertedId();

        if(is_null($thread)) {
            $rootId = $messageId;
        } else {
            $rootId = $thread->getId();
        }

    	// Update the message to set its root.
    	$isUpdated = $this->updateByPrimaryKey(
    		array(
    			"root_id" => $rootId
    		),
    		$messageId
    	);
    	if(!$isUpdated) {
    		throw new Madhouse_QueryFailedException(__("Adding the message to the requested thread has failed.", mdh_current_plugin_name()));
    	}

        return $messageId;
    }
}

?>