<?php

namespace Rcm\Tracking\Model;

use Rcm\Tracking\Exception\TrackingException;

/**
 * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
 *
 * Trait TrackingTrait
 * @package Rcm\Tracking\Model
 */
trait TrackingTrait
{
    /**
     * <tracking>
     *
     * @var \DateTime Date object was first created
     */
    protected $createdDate;

    /**
     * <tracking>
     *
     * @var string User ID of creator
     */
    protected $createdByUserId;

    /**
     * <tracking>
     *
     * @var string Short description of create reason
     */
    protected $createdReason = Tracking::UNKNOWN_REASON;

    /**
     * <tracking>
     *
     * @var \DateTime Date object was modified
     */
    protected $modifiedDate;

    /**
     * <tracking>
     *
     * @var string User ID of modifier
     */
    protected $modifiedByUserId;

    /**
     * <tracking>
     *
     * @var string Short description of create reason
     */
    protected $modifiedReason = Tracking::UNKNOWN_REASON;

    /**
     * <tracking>
     *
     * @var bool Tracking that this value has been updated
     */
    protected $modifiedDateUpdated = false;

    /**
     * <tracking>
     *
     * @var bool Tracking that this value has been updated
     */
    protected $modifiedByUserIdUpdated = false;

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * Get a clone with special logic
     *
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return static
     */
    public function newInstance(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        $new = clone($this);

        $new->setCreatedByUserId(
            $createdByUserId,
            $createdReason
        );

        return $new;
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @return \DateTime
     * @throws TrackingException
     */
    public function getCreatedDate(): \DateTime
    {
        return new \DateTime();
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @return string
     * @throws TrackingException
     */
    public function getCreatedByUserId(): string
    {
        return '_DEPRECATED';
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @return string
     */
    public function getCreatedReason(): string
    {
        return '_DEPRECATED';
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @todo this should be protected
     * <tracking> WARNING: this should only be used on creation
     *
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return void
     * @throws TrackingException
     */
    public function setCreatedByUserId(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        $createdByUserId = '_DEPRECATED';
        $createdReason = '_DEPRECATED';
        $this->createdDate = new \DateTime();
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @return \DateTime
     * @throws TrackingException
     */
    public function getModifiedDate(): \DateTime
    {
        return new \DateTime();
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     * <tracking>
     *
     * @return string
     * @throws TrackingException
     */
    public function getModifiedByUserId(): string
    {
        return '_DEPRECATED';
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @return string
     */
    public function getModifiedReason(): string
    {
        return '_DEPRECATED';
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @param string $modifiedByUserId
     * @param string $modifiedReason
     *
     * @return void
     * @throws TrackingException
     */
    public function setModifiedByUserId(
        string $modifiedByUserId,
        string $modifiedReason = Tracking::UNKNOWN_REASON
    ) {
        $this->modifiedByUserIdUpdated = true;
        $this->modifiedByUserId = '_DEPRECATED';

        $this->modifiedReason = '_DEPRECATED';
        $this->modifiedDate = new \DateTime();
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @return void
     * @throws TrackingException
     */
    public function assertHasTrackingData()
    {
        //Throw no exceptions to signal that everythign is ok.
    }

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @return void
     * @throws TrackingException
     */
    public function assertHasNewModifiedData()
    {
        //Throw no exceptions to signal that everythign is ok.
    }
}
