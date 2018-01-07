<?php

class Madhouse_Messenger_BlockedUser extends Madhouse_Entity
{
    /**
     * Informations from database.
     * @var Array<String,Any>
     */
    protected $_blockedUser;
    protected $user;
    protected $blockedUser;

    /**
     * Constructor.
     * @param $data        from DataBase
     * @param $user        The user who blocked
     * @param $blockedUser The user who IS blocked
     */
    function __construct($data, $user, $blockedUser)
    {
        $this->_blockedUser = $data;
        $this->setUser($user);
        $this->setBlockedUser($blockedUser);

        $this->setCreationDate($this->_blockedUser['dt_created']);
    }
    public function getCreationDate() {
        return $this->_blockedUser['dt_created'];
    }

    public function setCreationDate($val) {
        if ($val = "") {
            $val = null;
        } else {
            $val = new \DateTime($val);
        }
        $this->_blockedUser['dt_created'] = $val;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($val)
    {
        $this->user = $val;
        return $this;
    }

    public function getBlockedUser()
    {
        return $this->blockedUser;
    }

    public function setBlockedUser($val)
    {
        $this->blockedUser = $val;
        return $this;
    }
}
