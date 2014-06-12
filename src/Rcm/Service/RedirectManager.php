<?php
/**
 * Domain Manager
 *
 * This file contains the class used to manage domain names for the CMS.
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
 * @link      https://github.com/reliv
 */

namespace Rcm\Service;

use Doctrine\ORM\EntityRepository;
use Rcm\Exception\InvalidArgumentException;
use Zend\Cache\Storage\StorageInterface;

/**
 * Redirect Manager.
 *
 * The Redirect Manager is used to manage Redirects in the CMS.  Each site object is
 * related to a redirect for the site.  This allows the CMS to manage multiple sites
 * with one install of the CMS.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class RedirectManager
{
    /** @var \Rcm\Repository\Redirect  */
    protected $repository;

    /** @var \Zend\Cache\Storage\StorageInterface  */
    protected $cache;

    protected $siteManager;

    /**
     * Constructor
     *
     * @param EntityRepository $repository  Entity Repository for Redirects
     * @param StorageInterface $cache       Zend Cache Manager
     * @param SiteManager      $siteManager Rcm Site Manager
     */
    public function __construct(
        EntityRepository $repository,
        StorageInterface $cache,
        SiteManager      $siteManager
    ) {
        $this->repository  = $repository;
        $this->cache       = $cache;
        $this->siteManager = $siteManager;
    }

    /**
     * Get a list of redirects for the CMS.
     *
     * @param integer $siteId Site Id
     *
     * @return array|null
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getRedirectList($siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new InvalidArgumentException('Invalid Site ID');
        }

        $cacheKey = 'rcm_redirect_list_'.$siteId;

        //Check Cache for list of domains
        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $redirectList = $this->repository->getRedirectList($siteId);

        $this->cache->setItem($cacheKey, $redirectList);

        return $redirectList;
    }
}