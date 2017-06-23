<?php

namespace Rcm\Tracking\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class TrackingAbstract implements Tracking
{
    use TrackingTrait;

    /**
     * @param string $createdByUserId <tracking>
     * @param string $createdReason   <tracking>
     */
    public function __construct(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        $this->setCreatedByUserId($createdByUserId, $createdReason);
    }
}
