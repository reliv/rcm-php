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

require_once __DIR__ . '/../../../Base/BaseTestCase.php';

use Zend\Cache\Storage\StorageInterface;
use Rcm\Factory\CacheFactory;
use RcmTest\Base\BaseTestCase;
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
class CacheFactoryTest extends BaseTestCase
{
    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $this->addModule('Rcm');
        parent::setUp();
    }

    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\CacheFactory
     */
    public function testCreateService()
    {
        $config = array(
            'rcmCache' => array(
                'adapter' => 'Memory',
                'options' => array(
                    'memory_limit' => 0,
                ),
                'plugins' => array(
                    'exception_handler' => array('throw_exceptions' => false),
                ),
            ),
        );

        $sm = new ServiceManager();
        $sm->setService('config', $config);

        $factory = new CacheFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof StorageInterface);
    }


}