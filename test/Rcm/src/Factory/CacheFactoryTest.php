<?php
/**
 * Test for Factory CacheFactory
 *
 * This file contains the test for the CacheFactory.
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

use Rcm\Factory\CacheFactory;
use Zend\Cache\Storage\StorageInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory CacheFactory
 *
 * Test for Factory CacheFactory
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
class CacheFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\CacheFactory
     */
    public function testCreateService()
    {
        $config = [
            'rcmCache' => [
                'adapter' => 'Memory',
                'options' => [
                    'memory_limit' => 0,
                ],
                'plugins' => [
                    'exception_handler' => ['throw_exceptions' => false],
                ],
            ],
        ];

        $sm = new ServiceManager();
        $sm->setService('config', $config);

        $factory = new CacheFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof StorageInterface);
    }


}