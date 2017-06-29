<?php

namespace Rcm\Entity;

use Rcm\Core\Model\ApiModelAbstract;
use Rcm\Tracking\Model\Tracking;
use Rcm\Tracking\Model\TrackingTrait;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class ApiModelTrackingAbstract extends ApiModelAbstract implements ApiModelInterface, Tracking
{
    /**
     * <tracking>
     */
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

    /**
     * @param array $data
     * @param array $ignore
     *
     * @return void
     */
    public function populate(
        array $data,
        array $ignore = ['createdByUserId', 'createdDate', 'createdReason']
    ) {
        parent::populate($data, $ignore);
    }
}
