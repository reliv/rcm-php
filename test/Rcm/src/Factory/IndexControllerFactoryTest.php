<?php
/**
 * Test for Factory IndexControllerFactory
 *
 * This file contains the test for the IndexControllerFactory.
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

use Rcm\Controller\IndexController;
use Rcm\Factory\IndexControllerFactory;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory IndexControllerFactory
 *
 * Test for Factory IndexControllerFactory
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
class IndexControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\IndexControllerFactory
     */
    public function testCreateService()
    {
        $mockSiteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockLayoutManager = $this->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockCurrentSite = $this->getMockBuilder('\Rcm\Entity\Site')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Rcm\Service\SiteManager',
            $mockSiteManager
        );
        $serviceManager->setService(
            'Rcm\Service\LayoutManager',
            $mockLayoutManager
        );
        $serviceManager->setService(
            'Rcm\Service\CurrentSite',
            $mockCurrentSite
        );

        $cm = new ControllerManager();
        $cm->setServiceLocator($serviceManager);

        $factory = new IndexControllerFactory();
        $object = $factory->createService($cm);

        $this->assertTrue($object instanceof IndexController);
    }
}