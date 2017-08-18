<?php

namespace Rcm\Factory;

use AssetManager\Cache\ZendCacheAdapter;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Asset Manager Zend Cache Adapter
 *
 * Factory for the Asset Manager Zend Cache Adapter
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
class AssetManagerCacheFactory
{
    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return ZendCacheAdapter
     */
    public function __invoke($serviceLocator)
    {
        /** @var \Zend\Cache\Storage\StorageInterface $rcmCache */
        $rcmCache = $serviceLocator->get(\Rcm\Service\Cache::class);

        return new ZendCacheAdapter($rcmCache);
    }
}
