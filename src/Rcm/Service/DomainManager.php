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
use Zend\Cache\Storage\StorageInterface;

/**
 * Domain Manager.
 *
 * The Domain Manager is used to manage Domains in the CMS.  Each site object is
 * related to a domain for the site.  This allows the CMS to manage multiple sites
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
class DomainManager
{
    /** @var \Rcm\Repository\Domain */
    protected $repository;

    /** @var \Zend\Cache\Storage\StorageInterface */
    protected $cache;

    /**
     * Constructor
     *
     * @param EntityRepository $repository Doctrine Domain Repo
     * @param StorageInterface $cache      Zend Cache Manager
     */
    public function __construct(
        EntityRepository $repository,
        StorageInterface $cache
    ) {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * Get the current list of domains and store these in cache for future look ups.
     *
     * @return array
     */
    public function getActiveDomainList()
    {
        $cacheKey = 'rcm_active_domain_list';

        //Check Cache for list of domains
        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $domainList = $this->repository->getActiveDomainList();

        $this->cache->setItem($cacheKey, $domainList);

        return $domainList;
    }
}