<?php

namespace Rcm\Tracking\Model;

use Rcm\Tracking\Exception\TrackingException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class TrackingAbstract implements Tracking
{
    /**
     * @var string User ID of creator
     */
    protected $createdByUserId = null;

    /**
     * @var \DateTime Date page was first created
     */
    protected $createdDate = null;

    /**
     * @var string User ID of modifier
     */
    protected $modifiedByUserId = null;

    /**
     * @var \DateTime Date page was modified
     */
    protected $modifiedDate = null;

    /**
     * @var bool Tracking that this value has been updated
     */
    protected $modifiedByUserIdUpdated = false;

    /**
     * @var bool Tracking that this value has been updated
     */
    protected $modifiedDateUpdated = false;

    /**
     * @param $createdByUserId
     */
    public function __construct(
        string $createdByUserId
    ) {
        $this->setCreatedByUserId($createdByUserId);
        $this->setModifiedByUserId($createdByUserId);
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->createdByUserId = null;
        $this->createdDate = null;
        $this->modifiedByUserId = null;
        $this->modifiedDate = null;
    }

    /**
     * @return \DateTime
     * @throws TrackingException
     */
    public function getCreatedDate(): \DateTime
    {
        if (empty($this->createdDate)) {
            throw new TrackingException('Value not set for createdDate');
        }

        return $this->createdDate;
    }

    /**
     * @return string
     * @throws TrackingException
     */
    public function getCreatedByUserId(): string
    {
        // not set
        if (empty($this->createdByUserId)) {
            throw new TrackingException('Value not set for createdByUserId');
        }

        return $this->createdByUserId;
    }

    /**
     * @param string $createdByUserId
     *
     * @return void
     * @throws TrackingException
     */
    public function setCreatedByUserId(string $createdByUserId)
    {
        // invalid
        if (strlen($createdByUserId) < 1) {
            throw new TrackingException('Invalid createdByUserId');
        }

        // already set
        if (!empty($this->createdByUserId)) {
            throw new TrackingException('Can not change createdByUserId');
        }

        $this->createdByUserId = $createdByUserId;

        // already set
        if (!empty($this->createdDate)) {
            throw new TrackingException('Can not change createdDate');
        }

        $this->createdDate = new \DateTime();
    }

    /**
     * @return string
     * @throws TrackingException
     */
    public function getModifiedByUserId(): string
    {
        // not set
        if (empty($this->modifiedByUserId)) {
            throw new TrackingException('Value not set for modifiedByUserId');
        }

        return $this->modifiedByUserId;
    }

    /**
     * @param string $modifiedByUserId
     *
     * @return void
     * @throws TrackingException
     */
    public function setModifiedByUserId(string $modifiedByUserId)
    {
        // invalid
        if (strlen($modifiedByUserId) < 1) {
            throw new TrackingException('Invalid modifiedByUserId');
        }

        $this->modifiedByUserIdUpdated = true;
        $this->modifiedByUserId = $modifiedByUserId;

        $this->modifiedDate = new \DateTime();
    }

    /**
     * @return \DateTime
     * @throws TrackingException
     */
    public function getModifiedDate(): \DateTime
    {
        // not set
        if (empty($this->modifiedDate)) {
            throw new TrackingException('Value not set for modifiedDate');
        }

        return $this->modifiedDate;
    }
}
