<?php

class Madhouse_Messenger_Views_Message extends Madhouse_Messenger_Message
{
    /**
     * The user that is looking at this message.
     * @var Madhouse_User
     * @since 1.10
     */
    protected $viewer;
    
	public function __construct($viewer, $message)
    {
        parent::__construct(
            $message->getContent(),
            $message->getSender(),
            $message->getRecipients()
        );
        $this->withId($message->getId());
        $this->withSentDate($message->getSentDate());
        $this->withState($message->getState());
        $this->withThread($message->getThread());
        $this->withEvent($message->getEvent());
        $this->withHidden($message->getHidden());
        $this->withBlocked($message->isBlocked());
        $this->withStatus($message->getStatus());
        
        $this->viewer = $viewer;
    }
    
    /**
     * Returns the content (body) of this message.
     * @return a string.
     * @override parent::computeText();
     * @since 1.20
     */
    public function computeText()
    {
        if($this->isBlocked()) {
            // Blocked by an admin.
            return __("Sorry. This message has been blocked by an administrator (reason: spam).", mdh_current_plugin_name());
        } else if($this->isHidden()) {
            // Hidden by the viewer.
            return __("This message has been deleted.", mdh_current_plugin_name());
        } else {
            // Default (parent) implementation.
            return parent::computeText();
        }
    }
    
    public function isHidden()
    {
        $userId = $this->viewer->getId();
        /*
         * A message is seen as hidden if :
         * - viewer is author of this message and has hid the message (getHidden == true)
         * - viewer is recipient of this message and has hid the message
         */
        return ($this->isHiddenFor($userId) || ($userId == $this->getSender()->getId() && $this->getHidden()));
    }
    
    public function isAuto()
    {
        return ($this->isHidden() || $this->isBlocked() || $this->hasEvent()) ? true : false;
    }
    
    public function isFromViewer()
    {
        return ($this->viewer->getId() == $this->getSender()->getId()) ? true : false;
    }
    
    public function isRead()
    {
        return ($this->getReadDate()) ? true : false;
    }
    
    public function getReadDate()
    {
        foreach ($this->getState() as $userId => $s) {
            if($s["readOn"]) {
                return $s["readOn"];
            }
        }
        return null;
    }
    
    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            array(
                "is_auto" => $this->isAuto(),
                "is_hidden" => $this->isHidden(),
                "is_from_viewer" => $this->isFromViewer(),
                "is_read" => $this->isRead(),
                "read_date" => array(
                    "fb_formatted" => (($this->isRead()) ? Madhouse_Utils_Dates::smartDate($this->getReadDate()) : null)
                )
            )
        );
    }
}

?>