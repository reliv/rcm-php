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
 * @link      http://reliv.com
 */
namespace Rcm\Factory;

use Zend\Cache\StorageFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\Storage\StorageInterface;

/**
 * Service Factory for Rcm Cache
 *
 * Factory for Rcm Cache.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://reliv.com
 *
 */
class CacheFactory implements FactoryInterface
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
        $config = $serviceLocator->get('config');

        return StorageFactory::factory(
            array(
                'adapter' => array(
                    'name' => $config['rcmCache']['adapter'],
                    'options' => $config['rcmCache']['options'],
                ),
                'plugins' => $config['rcmCache']['plugins'],
            )
        );
    }
}
