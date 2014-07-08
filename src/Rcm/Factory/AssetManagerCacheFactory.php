<?php
/**
 * Service Factory for the Rcm Cache
 *
 * This file contains the factory needed to generate an Rcm Cache.
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
namespace Rcm\Factory;

use AssetManager\Cache\ZendCacheAdapter;
use Zend\Cache\Storage\StorageInterface;
use Zend\ServiceManager\FactoryInterface;
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
class AssetManagerCacheFactory implements FactoryInterface
{

    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return StorageInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Cache\Storage\StorageInterface $rcmCache */
        $rcmCache = $serviceLocator->get('Rcm\Service\Cache');

        return new ZendCacheAdapter($rcmCache);
    }
}
