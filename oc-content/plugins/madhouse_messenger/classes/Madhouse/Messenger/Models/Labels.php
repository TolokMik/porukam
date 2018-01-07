<?php

/**
 * Labels are gmail-like tags.
 * @since 1.33
 */
class Madhouse_Messenger_Models_Labels extends Madhouse_DAO_BaseDAO
{
    /**
     * Translation/Description helper.
     *     Gives primitive to manipulate translations in database.
     * @var Madhouse_DAO_Helper
     */
    protected $translations;

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
            self::$instance = new self($helper, new Madhouse_DAO_Helper());
        }
        return self::$instance;
    }

    public function __construct($helper)
    {
        // Init base helper.
        $helper->setTableName('t_mmessenger_labels');
        $helper->setFields(
            array(
                "pk_i_id",
                "s_name",
                "b_system",
                "fk_i_user_id",
                "fk_i_parent_id"
            )
        );
        $helper->setPrimaryKey("pk_i_id");

        parent::__construct($helper);

        // Init translation helper.
        $this->translations = new Madhouse_DAO_Helper();
        $this->translations->setTableName('t_mmessenger_labels_description');
        $this->translations->setFields(
            array(
                "fk_i_label_id",
                "fk_c_locale_code",
                "s_title"
            )
        );
        $this->translations->setPrimaryKey("fk_i_label_id");

        // Init join table helper.
        $this->join = new Madhouse_DAO_Helper();
        $this->join->setTableName('t_mmessenger_message_labels');
        $this->join->setFields(
            array(
                "fk_i_label_id",
                "fk_i_user_id",
                "fk_i_message_id"
            )
        );
        $this->join->setPrimaryKey("fk_i_label_id");
    }

    /**
     * Find all labels available for all users
     * @return Array of labels
     * @since 1.50
     */
    public function findGlobal()
    {
        $that = $this;
        return $this->helper->findBy(
            function($dao) {
                $dao->dao->select($dao->getFields());
                $dao->dao->from($dao->getTableName());
                $dao->dao->where("fk_i_user_id IS NULL");
            },
            function($results, $helper) use ($that) {
                return $that->buildObjects($results->result());
            }
        );
    }

    /**
     *
     *
     */
    public function findByName($name, $user = null)
    {
        $filters = array("s_name" => $name);
        if(!is_null($user)) {
            $filters["fk_i_user_id"] = $user->getId();
        } else {
            $filters["fk_i_user_id IS NULL"] = null;
        }


        return $this->helper->findOneBy(
            $filters,
            array($this, "buildObject")
        );
    }

    public function findByUser($user)
    {
        $that = $this;
        return $this->helper->findBy(
            function($dao) use ($user) {
                $dao->dao->select($dao->getFields());
                $dao->dao->from($dao->getTableName());
                $dao->dao->where("fk_i_user_id", $user->getId());
                $dao->dao->orWhere("fk_i_user_id IS NULL");
            },
            function($results, $helper) use ($that) {
                return $that->buildObjects($results->result());
            }
        );
    }

    public function findByThread($thread, $ignoreCache = false)
    {
        $that = $this;
        return $this->helper->findBy(
            function($helper) use ($that, $thread) {
                $helper->dao->select(
                    array_merge(
                        Madhouse_Utils_Models::getFieldsPrefixed($helper, "l"),
                        Madhouse_Utils_Models::getFieldsPrefixed($that->join, "ml", "ml_")
                    )
                );
                $helper->dao->from($helper->getTableName() . " AS l");
                $helper->dao->join($that->join->getTableName() . " AS ml", "ml.fk_i_label_id = l.pk_i_id");
                $helper->dao->where("fk_i_message_id", $thread->getId());
            },
            function($results, $dao) use ($that) {
                $gstate = array();
                foreach ($results->result() as $row) {
                    // Build the label object.
                    $label = $this->buildObject($row);

                    // Push to the right user array.
                    if(!isset($gstate[$row["ml_fk_i_user_id"]])) {
                        $gstate[$row["ml_fk_i_user_id"]] = array();
                    }
                    array_push($gstate[$row["ml_fk_i_user_id"]], $label);
                }
                return $gstate;
            },
            false,
            array(),
            $ignoreCache
        );
    }

    public function create($label)
    {
        // Insert the label.
        $labelId = $this->helper->insert(
            array(
                "s_name" => $label->getName(),
                "b_system" => $label->isSystem(),
                "fk_i_user_id" => ($label->getUser()) ? $label->getUser()->getId() : null,
                "fk_i_parent_id" => ($label->getParent()) ? $label->getParent()->getId() : null,
            )
        );

        // Return the up-to-date object.
        $label->setId($labelId);

        // Insert locales, for every available one.
        foreach (osc_listLanguageCodes() as $code) {
            $this->insertDescription($label, $code);
        }
        return $label;
    }

    public function insertDescription($label, $code) {
        $this->translations->insert(
            array(
                "fk_i_label_id" => $label->getId(),
                "fk_c_locale_code" => $code,
                "s_title" => $label->getTitle($code)
            )
        );
    }

    public function remove($label) {
        return $this->helper->delete(
            array(
                "pk_i_id" => $label->getId()
            )
        );
    }

    public function updateDescription($label, $code)
    {
        $this->translations->update(
            array(
                "s_title" => $label->getTitle($code)
            ),
            array(
                "fk_i_label_id" => $label->getId(),
                "fk_c_locale_code" => $code
            )
        );
    }

    public function buildObject($row)
    {
        // Extend data with translations (descriptions).
        $row = $this->translations->extendData(
            $row,
            array(
                "fk_i_label_id" => $row[$this->helper->getPrimaryKey()]
            )
        );

        // Create the label object.
        $o = new Madhouse_Messenger_Label($row);

        if(isset($row["fk_i_user_id"]) && $row["fk_i_user_id"]) {
            $o->setUser(Madhouse_Utils_Models::findUserByPrimaryKey($row["fk_i_user_id"]));
        }

        if(isset($row["fk_i_parent_id"]) && $row["fk_i_user_id"]) {
            $o->setParent($this->findByPrimaryKey($row["fk_i_parent_id"]));
        }

        return $o;
    }
}
