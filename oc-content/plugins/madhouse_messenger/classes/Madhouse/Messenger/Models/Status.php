<?php

class Madhouse_Messenger_Models_Status extends Madhouse_DAO_BaseDAO
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
        $helper->setTableName('t_mmessenger_status');
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
        return $this->helper->getTablePrefix() . 't_mmessenger_status_description';
    }

    /**
     * Finds an status by name.
     * @param $name name of the status that we're looking for.
     * @returns MadhouseMessengerStatus object.
     * @throws Exception if no status is found for $name name.
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

    public function create($status)
    {
        $statusId = $this->helper->insert(
            array(
                "name" => $status->getName()
            )
        );

        // Insert descriptions.
        $status->setId($statusId);
        $this->insertDescription($status);

        return $this->findByPrimaryKey($statusId);
    }

    public function insertDescription($status, $code = null)
    {
        if (!is_null($code)) {
            $this->helper->dao->insert(
                $this->getDescriptionTableName(),
                array(
                    "fk_i_status_id" => $status->getId(),
                    "fk_c_locale_code" => $code,
                    "s_title" => $status->getTitle($code),
                    "s_text" => $status->getText($code)
                )
            );
        } else {
            // Insert locales, for every available one.
            foreach (osc_listLanguageCodes() as $code) {
                $this->helper->dao->insert(
                    $this->getDescriptionTableName(),
                    array(
                        "fk_i_status_id" => $status->getId(),
                        "fk_c_locale_code" => $code,
                        "s_title" => $status->getTitle($code),
                        "s_text" => $status->getText($code)
                    )
                );
            }
        }
    }

    /**
     * Update description for one locale
     * @param  Int $status
     * @param  String $code    Locale code
     * @return Bool Query result
     * @since 1.50
     */
    public function updateDescription($status, $code)
    {
        return $this->helper->dao->update(
            $this->getDescriptionTableName(),
            array(
                "s_title"        => $status->getTitle($code),
                "s_text"           => $status->getText($code)
            ),
            array(
                "fk_i_status_id"    => $status->getId(),
                "fk_c_locale_code" => $code
            )
        );
    }

    /**
     * Remove
     * @param  Madhouse_Label $label
     * @return Bool Query result
     * @since 1.50
     */
    public function remove($status) {
        return $this->helper->delete(
            array(
                "id" => $status->getId()
            )
        );
    }


    public function buildObject($row)
    {
        return new Madhouse_Messenger_Status(
            array_merge(
                $row,
                array(
                    "locale" => Madhouse_Utils_Models::extendData(
                        $this->helper,
                        $this->getDescriptionTableName(),
                        "fk_i_status_id",
                        $row
                    )
                )
            )
        );
    }
}

?>