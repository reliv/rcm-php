<?php
/**
 * Test for Factory AssetManagerCacheFactory
 *
 * This file contains the test for the AssetManagerCacheFactory.
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

namespace RcmTest\Factory;

require_once __DIR__ . '/../../../autoload.php';

use AssetManager\Cache\ZendCacheAdapter;
use Rcm\Factory\AssetManagerCacheFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory AssetManagerCacheFactory
 *
 * Test for Factory AssetManagerCacheFactory
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
class AssetManagerCacheFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\AssetManagerCacheFactory
     */
    public function testCreateService()
    {
        $mockZendCache = $this->getMockBuilder(
            '\Zend\Cache\Storage\Adapter\Memory'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();
        $serviceManager->setService('Rcm\Service\Cache', $mockZendCache);

        $factory = new AssetManagerCacheFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof ZendCacheAdapter);
    }
}
