<?php
/**
 * Test for Factory ContainerViewHelperFactory
 *
 * This file contains the test for the ContainerViewHelperFactory.
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

use Rcm\View\Helper\Container;
use Rcm\Factory\ContainerViewHelperFactory;
use RcmTest\Base\BaseTestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;

/**
 * Test for Factory ContainerViewHelperFactory
 *
 * Test for Factory ContainerViewHelperFactory
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
class ContainerViewHelperFactoryTest extends BaseTestCase
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
     * @covers \Rcm\Factory\ContainerViewHelperFactory
     */
    public function testCreateService()
    {
        $mockContainerManager = $this
            ->getMockBuilder('\Rcm\Service\ContainerManager')
            ->disableOriginalConstructor()
            ->getMock();

        $sm = new ServiceManager();
        $sm->setService('Rcm\Service\ContainerManager', $mockContainerManager);

        $helperManager = new HelperPluginManager();
        $helperManager->setServiceLocator($sm);

        $factory = new ContainerViewHelperFactory();
        $object = $factory->createService($helperManager);

        $this->assertTrue($object instanceof Container);
    }
}