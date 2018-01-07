<?php

class Madhouse_Messenger_Status extends Madhouse_Entity
{
    /**
     * Informations from database.
     * @var Array<String,Any>
     */
    protected $status;

    /**
     * Constructor.
     * @param $id UID of this status.
     * @param $name name of this status.
     */
	function __construct($status = null)
    {
        // Create locale sub-array, if needed.
        if(is_array($status) && !isset($status["locale"])) {
            $status["locale"] = array();
        }
        // Set status.
		$this->status = $status;
	}

    public function setId($id)
    {
        $this->status["id"] = $id;
        return $this;
    }

    public function getId()
    {
        return (int) osc_field($this->status, "id", "");
    }

    public function setName($name)
    {
        $this->status["name"] = $name;
        return $this;
    }

    /**
     * Get the name (internal name) of this status.
     * @return
     * @since 1.40 - moved from Madhouse_NamedEntity
     */
    public function getName()
    {
        return (string) osc_field($this->status, "name", "");
    }

    public function setTitle($title, $locale=null)
    {
        if(is_null($locale)) {
            $locale = osc_current_user_locale();
        }

        // Create the sub-array for this locale, if needed.
        if(!isset($this->status["locale"][$locale])) {
            $this->status["locale"][$locale] = array();
        }

        // Set the title.
        $this->status["locale"][$locale]["s_title"] = $title;
        return $this;
    }

    /**
     * Get the title of this status.
     * @param  String $locale en_US, fr_FR, etc.
     * @return string
     */
	public function getTitle($locale=null) {
		if(is_null($locale)) {
			$locale = osc_current_user_locale();
		}
		return osc_field($this->status, "s_title", $locale);
	}

    public function setText($text, $locale=null)
    {
        if(is_null($locale)) {
            $locale = osc_current_user_locale();
        }

        // Create the sub-array for this locale, if needed.
        if(!isset($this->status["locale"][$locale])) {
            $this->status["locale"][$locale] = array();
        }

        // Set the text.
        $this->status["locale"][$locale]["s_text"] = $text;
        return $this;
    }

    /**
     * Returns the translated text for this status.
     *
     * @return String
     * @since 1.00
     *        1.30 - text is now in the database.
     */
	public function getText($locale=null) {
		if(is_null($locale)) {
			$locale = osc_current_user_locale();
		}
		return osc_field($this->status, "s_text", $locale);
	}

    /**
     * Test if the locale exist
     * @param  Sting  $locale locale code
     * @return boolean
     */
    public function hasLocale($locale = null) {
        return isset($this->status["locale"][$locale]);
    }

	public function toArray()
	{
		return array_merge(
			parent::toArray(),
			array(
				"s_title" => $this->getTitle(),
				"s_text" => $this->getText()
			)
		);
	}
}

?>