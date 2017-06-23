<?php

namespace Rcm\Tracking\Model;

use Rcm\Tracking\Exception\TrackingException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Tracking
{
    const UNKNOWN_USER_ID = 'unknown';
    const UNKNOWN_REASON = 'unknown';

    /**
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
    );

    /**
     * @return \DateTime
     * @throws TrackingException
     */
    public function getCreatedDate(): \DateTime;

    /**
     * @return string
     * @throws TrackingException
     */
    public function getCreatedByUserId(): string;

    /**
     * @return string
     */
    public function getCreatedReason(): string;

    /**
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return void
     */
    public function setCreatedByUserId(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    );

    /**
     * @return \DateTime
     * @throws TrackingException
     */
    public function getModifiedDate(): \DateTime;

    /**
     * @return string
     * @throws TrackingException
     */
    public function getModifiedByUserId(): string;

    /**
     * @return string
     */
    public function getModifiedReason(): string;

    /**
     * @param string $modifiedByUserId
     * @param string $modifiedReason
     *
     * @return void
     * @throws TrackingException
     */
    public function setModifiedByUserId(
        string $modifiedByUserId,
        string $modifiedReason = Tracking::UNKNOWN_REASON
    );

    /**
     * @return void
     * @throws TrackingException
     */
    public function assertHasTrackingData();

    /**
     * @return void
     * @throws TrackingException
     */
    public function assertHasNewModifiedData();
}
