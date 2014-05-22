<?php
/**
 * Rcm Page Manager
 *
 * This file contains the class definition for the Page Manager
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Service;

use Doctrine\ORM\Query;

/**
 * Rcm Page Manager
 *
 * Rcm Page Manager.  This class handles everything about a CMS page.  Pages also
 * include their own plugin containers and can contain multiple containers depending
 * on the page template defined by the CMS theme being used.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class PageManager extends ContainerAbstract
{
    /** @var \Rcm\Repository\Page  */
    protected $repository;

    /**
     * Get a page list by type.  Returns page Id and page name.
     *
     * @param string $type Page Type
     *
     * @return array
     */
    public function getPageListByType($type)
    {
        return $this->repository->getAllPageIdsAndNamesBySiteThenType(
            $this->siteId,
            $type
        );
    }
}