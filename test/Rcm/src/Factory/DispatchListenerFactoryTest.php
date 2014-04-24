<?php
/**
 * Test for Factory DispatchListenerFactory
 *
 * This file contains the test for the DispatchListenerFactory.
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

use Rcm\EventListener\DispatchListener;
use Rcm\Factory\DispatchListenerFactory;
use RcmTest\Base\BaseTestCase;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory DispatchListenerFactory
 *
 * Test for Factory DispatchListenerFactory
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
class DispatchListenerFactoryTest extends BaseTestCase
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
     * @covers \Rcm\Factory\DispatchListenerFactory
     */
    public function testCreateService()
    {
        $mockLayoutManager = $this->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockHelperManager = $this->getMockBuilder('\Zend\View\HelperPluginManager')
            ->disableOriginalConstructor()
            ->getMock();

        $sm = new ServiceManager();
        $sm->setService('Rcm\Service\LayoutManager', $mockLayoutManager);
        $sm->setService('Rcm\Service\SiteManager', $mockSiteManager);
        $sm->setService('viewHelperManager', $mockHelperManager);

        $factory = new DispatchListenerFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof DispatchListener);
    }
}