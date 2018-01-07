<?php

class Madhouse_Messenger_Event extends Madhouse_Entity
{
	/**
     * Informations from database.
     * @var Array<String,Any>
     */
    protected $event;

    /**
     * Constructor.
     * @param $id UID of this event.
     * @param $name name of this event.
     */
	function __construct($event = null)
    {
        // Create locale sub-array, if needed.
        if(is_array($event) && !isset($event["locale"])) {
            $event["locale"] = array();
        }
        // Set event.
		$this->event = $event;
	}

    public function setId($id)
    {
        $this->event["id"] = $id;
        return $this;
    }

    public function getId()
    {
        return (int) osc_field($this->event, "id", "");
    }

    public function setName($name)
    {
        $this->event["name"] = $name;
        return $this;
    }

    /**
     * Get the name (internal name) of this event.
     * @return
     * @since 1.40 - moved from Madhouse_NamedEntity
     */
    public function getName()
    {
        return (string) osc_field($this->event, "name", "");
    }

    public function setExcerpt($excerpt, $locale=null)
    {
        if(is_null($locale)) {
            $locale = osc_current_user_locale();
        }

        // Create the sub-array for this locale, if needed.
        if(!isset($this->event["locale"][$locale])) {
            $this->event["locale"][$locale] = array();
        }

        // Set the text.
        $this->event["locale"][$locale]["s_excerpt"] = $excerpt;
        return $this;
    }

    /**
     * Returns the translated excerpt for this event.
     *
     * @return String
     * @since 1.00
     *        1.30 - text is now in the database.
     */
	public function getExcerpt($locale=null) {
		if(is_null($locale)) {
			$locale = osc_current_user_locale();
		}
		return osc_field($this->event, "s_excerpt", $locale);
	}

    public function setText($text, $locale=null)
    {
        if(is_null($locale)) {
            $locale = osc_current_user_locale();
        }

        // Create the sub-array for this locale, if needed.
        if(!isset($this->event["locale"][$locale])) {
            $this->event["locale"][$locale] = array();
        }

        // Set the text.
        $this->event["locale"][$locale]["s_text"] = $text;
        return $this;
    }

    /**
     * Test if the locale exist
     * @param  Sting  $locale locale code
     * @return boolean
     */
    public function hasLocale($locale = null) {
        return isset($this->event["locale"][$locale]);
    }

    /**
     * Returns the translated text for this event.
     *
     * @return String
     * @since 1.00
     *        1.30 - text is now in the database.
     */
	public function getText($locale=null) {
		if(is_null($locale)) {
			$locale = osc_current_user_locale();
		}
		return osc_field($this->event, "s_text", $locale);
	}
}
