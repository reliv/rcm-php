<?php
/**
 * Test for Factory LayoutManagerFactory
 *
 * This file contains the test for the LayoutManagerFactory.
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

use Rcm\Service\LayoutManager;
use Rcm\Factory\LayoutManagerFactory;
use RcmTest\Base\BaseTestCase;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory LayoutManagerFactory
 *
 * Test for Factory LayoutManagerFactory
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
class LayoutManagerFactoryTest extends BaseTestCase
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
     * @covers \Rcm\Factory\LayoutManagerFactory
     */
    public function testCreateService()
    {
        $mockSiteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();


        $sm = new ServiceManager();
        $sm->setService('Rcm\Service\SiteManager', $mockSiteManager);
        $sm->setService('config', array());

        $factory = new LayoutManagerFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof LayoutManager);
    }
}