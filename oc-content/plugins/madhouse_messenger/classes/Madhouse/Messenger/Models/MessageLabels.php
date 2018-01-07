<?php

class Madhouse_Messenger_Models_MessageLabels extends DAO
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
        $this->setTableName('t_mmessenger_message_labels');
        $this->setFields(array("fk_i_label_id", "fk_i_user_id", "fk_i_message_id"));
        $this->setPrimaryKey("fk_i_label_id");
    }

    /**
     * Add $label to $thread for $user.
     * @since 1.33
     */
    public function add($label, $threadId, $user)
    {
        $isInserted = $this->insert(
            array(
                "fk_i_label_id" => $label->getId(),
                "fk_i_message_id" => $threadId,
                "fk_i_user_id" => $user->getId(),
            )
        );
        if($isInserted === false) {
            throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
        }
    }

    /**
     * Move $thread from label $from to label $to for $user.
     * @since 1.33
     */
    public function move($from, $to, $thread, $user)
    {
        $isUpdated = $this->update(
            array(
                "fk_i_label_id" => $to->getId()
            ),
            array(
                "fk_i_message_id" => $thread->getId(),
                "fk_i_user_id" => $user->getId(),
                "fk_i_label_id" => $from->getId()
            )
        );
        if($isUpdated === false) {
            throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
        }
    }

    public function remove($label, $thread, $user)
    {
        $isDeleted = $this->delete(
            array(
                "fk_i_message_id" => $thread->getId(),
                "fk_i_user_id" => $user->getId(),
                "fk_i_label_id" => $label->getId()
            )
        );

        if ($isDeleted === false) {
            throw new Madhouse_QueryFailedException($this->dao->getErrorDesc());
        }

        return $isDeleted;
    }
}