<?php

/**
 * Represents a Thread as minimal as possible.
 *
 * It only contains common informations about a Thread and serve
 * as a way to store informations about threads without having
 * to retrieve some or every messages (avoid overhead when it is
 * not necessary.
 *
 * Created mainly for administration pages that lists messages
 * not grouped by threads but display the Item linked to a each
 * message (which is an information stored in threads :-))
 *
 * @author Madhouse Design Co.
 * @package Madhouse
 * @subpackage Messenger
 * @since 1.10
 */
class Madhouse_Messenger_ThreadSkeleton extends Madhouse_Entity
{
    /**
     * Thread unique identifier.
     * @var int
     * @since 1.40
     */
    protected $id;

	/**
	 * Title of this thread.
	 * May be NULL (most of the time), but gives the ability to set one.
	 * @var string
	 * @since 1.00
	 */
	protected $title;

    /**
     * The item related to this thread.
     * @var int or Madhouse_Item
     * @since 1.00
     */
    protected $item;

    /**
     * Status of this thread.
     * @var Madhouse_Messenger_Status
     * @since 1.00
     */
    protected $status;

    /**
     * Labels of this thread.
     * @var Array<Madhouse_Messenger_Label>
     * @since 1.33
     */
    protected $labels;

    /**
     * Constructor of a ThreadSkeleton.
     * Sets the UID of this thread (makes it mandatory).
     */
    public function __construct($id)
    {
        $this->id = $id;

    }

    /**
     * Gets the unique id of this thread.
     * @return int
     * @since 1.40
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the URL to this thread.
     * @return a URL-string.
     */
    public function getURL() {
        return mdh_messenger_thread_url($this->getId());
    }

	/**
	 * Sets the title of this thread.
	 * @returns this.
	 */
	public function withTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * Sets the title of this item.
	 * @return $this thread.
	 * @since 1.00
	 */
	public function withItem($item)
	{
		$this->item = $item;
		return $this;
	}

	/**
	 * Sets the status of this thread.
	 * @return $this thread.
	 * @since 1.00
	 */
	public function withStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * Tells if this thread IS linked to an item.
	 * @return boolean
	 * @since 1.00
	 */
	public function hasItem()
	{
		if(
			!is_null($this->item)
				&&   $this->item instanceof Madhouse_Item
				// &&   $this->item->isEnabled()
				// && ! $this->item->isSpam()
				// &&   $this->item->isActive()
		) {
			return true;
		}
		return false;
	}

	/**
	 * Tells if this thread WAS linked to an item but is not anymore.
	 * @return boolean
	 * @since 1.00
	 */
	public function hadItem()
	{
		if(is_numeric($this->item) && $this->item == -1) {
			return true;
		}
		return false;
	}

    /**
     * Gets (or compute a default one if not set) the title for this thread.
     * @return a string.
     * @since 1.00
     */
    public function getTitle() {
        return $this->title;
    }

	/**
	 * Gets the related item for this thread.
	 * @return an Item object.
	 * @since 1.00
	 */
	public function getItem()
	{
		return $this->item;
	}

    public function hasStatus()
    {
        if(! is_null($this->status)) {
            return true;
        }
        return false;
    }

	/**
	 * Gets the status of this thread.
	 * @return instance of Madhouse_Messenger_Status
	 * @since 1.00
	 */
	public function getStatus()
	{
		return $this->status;
	}

    public function setLabels($labels)
    {
        $this->labels = $labels;

    }

    public function getLabels($user=null)
    {
        if (empty($this->labels)) {
            return array();
        }

        if(!is_null($user)) {
            if(isset($this->labels[$user->getId()])) {
                return $this->labels[$user->getId()];
            }
            return array();
        }
        return $this->labels;
    }

    public function getUserLabels($user)
    {
        return array_filter($this->getLabels($user), function($v) {
            return (!$v->isEverlasting());
        });
    }

    public function hasLabel($label, $user)
    {
        foreach($this->getLabels($user) as $userLabel) {
            if($userLabel->getId() === $label->getId()) {
                return true;
            }
        }
        return false;
    }

	public function toArray()
	{
        $array = array_merge(
            parent::toArray(),
            array(
                "has_item" => $this->hasItem(),
                "had_item" => $this->hadItem(),
                "has_status" => $this->hasStatus(),
                "url" => $this->getURL()
            )
        );
    	return $array;
	}
}

?>