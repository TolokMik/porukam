<?php

class Madhouse_Messenger_Label extends Madhouse_Entity
{
    /**
     * Inner informations from database.
     *
     * @var Array<String,Any>
     */
    protected $label;

    /**
     * Localized datas from database.
     *
     * @var Array<String,Array>
     */
    protected $locale;

    /**
     * User to which this label exists.
     *
     * @var Madhouse_User or null
     */
    protected $user;

    /**
     * Parent label.
     *
     * @var Madhouse_Messenger_Label or null
     */
    protected $parent;

    public function __construct($label = null, $user = null, $parent = null)
    {
        // Create locale array if necessary.
        if(is_array($label) && !isset($label["locale"])) {
            $label["locale"] = array();
        }

        // Set label informations.
        $this->label = $label;
    }

    public function setId($id)
    {
        $this->label["pk_i_id"] = $id;
        return $this;
    }

    public function getId()
    {
        return (string) osc_field($this->label, "pk_i_id", "");
    }

    public function setName($name)
    {
        $this->label["s_name"] = $name;
        return $this;
    }

    public function getName()
    {
        return (string) osc_field($this->label, "s_name", "");
    }

    public function setTitle($title, $locale=null)
    {
        if(is_null($locale)) {
            $locale = osc_current_user_locale();
        }

        // Create the sub-array for this locale, if needed.
        if(!isset($this->label["locale"][$locale])) {
            $this->label["locale"][$locale] = array();
        }

        // Set the title.
        $this->label["locale"][$locale]["s_title"] = $title;
        return $this;
    }

    public function getTitle($locale=null)
    {
        if(is_null($locale)) {
            $locale = osc_current_user_locale();
        }
        return osc_field($this->label, "s_title", $locale);
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setSystem($system)
    {
        $this->label["b_system"] = $system;
        return $this;
    }

    /**
     * Test if the locale exist
     * @param  Sting  $locale locale code
     * @return boolean
     */
    public function hasLocale($locale = null) {
        return isset($this->label["locale"][$locale]);
    }

    public function isSystem()
    {
        return (bool) osc_field($this->label, "b_system", "");
    }
}