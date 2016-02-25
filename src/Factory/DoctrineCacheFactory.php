<?php

namespace Rcm\Factory;

use DoctrineModule\Cache\ZendStorageCache;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for Doctrine Cache used by the CMS
 *
 * Factory for for Doctrine Cache used by the CMS.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class DoctrineCacheFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return ZendStorageCache
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Cache\Storage\StorageInterface $zendCache */
        $zendCache = $serviceLocator->get('Rcm\Service\Cache');

        return new ZendStorageCache($zendCache);
    }
}
