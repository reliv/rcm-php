<?php

namespace Rcm\Repository;

use Rcm\Tracking\Model\Tracking;

/**
 * @deprecated Repository should not be used directly, please use the /Rcm/Api/{model}/Repository functions
 * Container Repository Interface
 *
 * Container Repository Interface.  Used by container repositories.
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
interface ContainerInterface
{
    /**
     * Gets the DB result of the current Published Revision
     *
     * @param integer $siteId Site Id
     * @param string  $name   Name of the container
     *
     * @return mixed
     */
    public function getPublishedRevisionId($siteId, $name);

    /**
     * Get the Staged Revision Id
     *
     * @param integer $siteId Site Id
     * @param string  $name   Page Name
     *
     * @return null|integer
     */
    public function getStagedRevisionId($siteId, $name);

    /**
     * Get Revision DB Info
     *
     * @param integer $siteId     Site Id
     * @param string  $name       Page Name
     * @param string  $revisionId Revision Id
     *
     * @return null|array                           Database Result Set
     * @throws \Rcm\Exception\PageNotFoundException
     */
    public function getRevisionDbInfo($siteId, $name, $revisionId);

    /**
     * @param \Rcm\Entity\ContainerInterface $container
     * @param                                $containerData
     * @param                                $createdByUserId
     * @param string                         $createdReason
     * @param string                         $author
     * @param null                           $revisionNumber
     *
     * @return int revisionId
     */
    public function saveContainer(
        \Rcm\Entity\ContainerInterface $container,
        $containerData,
        $createdByUserId,
        $createdReason = Tracking::UNKNOWN_REASON,
        $author = Tracking::UNKNOWN_AUTHOR,
        $revisionNumber = null
    );
}
