<?php

/**
 *
 *
 *
 * @since 1.10
 */
class Madhouse_Messenger_Models_Events extends Madhouse_DAO_BaseDAO
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
            $cache = new Madhouse_Cache_ArrayCache();
            $helper = new Madhouse_DAO_CacheHelper($cache);
            self::$instance = new self($helper);
        }
        return self::$instance;
    }

    public function __construct($helper)
    {
        // Setup the helper.
        $helper->setTableName('t_mmessenger_events');
        $helper->setFields(
            array(
                "id",
                "name"
            )
        );
        $helper->setPrimaryKey("id");

        // Init the DAO.
        parent::__construct($helper);
    }

    public function getDescriptionTableName()
    {
        return $this->helper->getTablePrefix() . 't_mmessenger_events_description';
    }

    /**
     * Finds an event by name.
     * @param $name name of the event that we're looking for.
     * @returns MadhouseMessengerEvent object.
     * @throws Exception if no event is found for $name name.
     * @see Madhouse_Utils_Models::findByName
     * @since 1.10
     */
    public function findByName($name, $raw = false)
    {
        return $this->helper->findOneBy(
            array(
                "name" => $name
            ),
            array($this, "buildObject")
        );
    }

    /**
     * Find all system labels
     * @return Array of events
     * @since 1.50
     */
    public function findAll()
    {
        $that = $this;
        return $this->helper->findBy(
            function($dao) {
                $dao->dao->select($dao->getFields());
                $dao->dao->from($dao->getTableName());
            },
            function($results, $helper) use ($that) {
                return  $that->buildObjects($results->result());
            }
        );
    }

    public function create($event)
    {
        $that = $this;
        $eventId = $this->helper->insert(
            array(
                "name" => $event->getName()
            )
        );

        // Insert description.
        $event->setId($eventId);
        $this->insertDescription($event);

        return $this->findByPrimaryKey($eventId);
    }

    public function insertDescription($event, $code = null)
    {
        if (!is_null($code)) {
            $this->_insertDescription($event, $code);
        } else {
            // Insert locales, for every available one.
            foreach (osc_listLanguageCodes() as $code) {
                $this->_insertDescription($event, $code);
            }
        }
    }

    public function _insertDescription($event, $code)
    {
        // Prepare data.
        $data = array(
            "fk_i_event_id" => $event->getId(),
            "fk_c_locale_code" => $code,
            "s_text" => $event->getText($code)
        );

        /*
         * Set excerpt for this event.
         *
         * - Event description has been added in 1.30
         * - s_except has been added in 1.40.0
         *
         * If it's not set, it means we're earlier than 1.40.0 so we don't set
         * it at all.
         */
        if ($event->getExcerpt($code)) {
            $data["s_excerpt"] = $event->getExcerpt($code);
        }

        // Insert data.
        $this->helper->dao->insert(
            $this->getDescriptionTableName(),
            $data
        );
    }

    /**
     * Update description for one locale
     * @param  Int $eventId
     * @param  String $code    Locale code
     * @return Bool Query result;
     */
    public function updateDescription($event, $code)
    {
        return $this->helper->dao->update(
            $this->getDescriptionTableName(),
            array(
                "s_excerpt"        => $event->getExcerpt($code),
                "s_text"           => $event->getText($code)
            ),
            array(
                "fk_i_event_id"    => $event->getId(),
                "fk_c_locale_code" => $code
            )
        );
    }

    /**
     * Build (constitute) the object that will be returned to controllers/views.
     * @param $e associative array that contains data to create the event.
     * @returns MadhouseMessengerEvent object.
     * @since 1.10
     */
    public function buildObject($row)
    {
        return new Madhouse_Messenger_Event(
            array_merge(
                $row,
                array(
                    "locale" => Madhouse_Utils_Models::extendData(
                        $this->helper,
                        $this->getDescriptionTableName(),
                        "fk_i_event_id",
                        $row
                    )
                )
            )
        );
    }
}

?>