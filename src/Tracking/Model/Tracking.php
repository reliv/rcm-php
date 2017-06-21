<?php

namespace Rcm\Tracking\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Tracking
{
    /**
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime;

    /**
     * @return string
     */
    public function getCreatedByUserId(): string;

    /**
     * @param string $createdByUserId
     */
    public function setCreatedByUserId(string $createdByUserId);

    /**
     * @return \DateTime
     */
    public function getModifiedDate(): \DateTime;

    /**
     * @return string
     */
    public function getModifiedByUserId(): string;

    /**
     * @param string $modifiedByUserId
     */
    public function setModifiedByUserId(string $modifiedByUserId);

}
