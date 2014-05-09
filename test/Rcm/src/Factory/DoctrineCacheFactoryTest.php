<?php
/**
 * Test for Factory DoctrineCacheFactory
 *
 * This file contains the test for the DoctrineCacheFactory.
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

use DoctrineModule\Cache\ZendStorageCache;
use Rcm\Factory\DoctrineCacheFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory DoctrineCacheFactory
 *
 * Test for Factory DoctrineCacheFactory
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
class DoctrineCacheFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\DoctrineCacheFactory
     */
    public function testCreateService()
    {
        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $sm = new ServiceManager();
        $sm->setService('Rcm\Service\Cache', $mockCache);

        $factory = new DoctrineCacheFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof ZendStorageCache);
    }
}