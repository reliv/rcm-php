<?php
/**
 * Test for Factory PageValidatorFactory
 *
 * This file contains the test for the PageValidatorFactory.
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

use Rcm\Factory\PageValidatorFactory;
use Rcm\Validator\Page;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory PageValidatorFactory
 *
 * Test for Factory PageValidatorFactory
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
class PageValidatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\PageValidatorFactory
     */
    public function testCreateService()
    {

        $mockEntityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPageRepo = $this->getMockBuilder('\Rcm\Repository\Page')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockPageRepo));

        $mockCurrentSite = $this->getMockBuilder('\Rcm\Entity\Site')
            ->disableOriginalConstructor()
            ->getMock();

        $mockCurrentSite->expects($this->any())
            ->method('getSiteId')
            ->will($this->returnValue(1));

        $serviceLocator = new ServiceManager();
        $serviceLocator->setService(
            'Doctrine\ORM\EntityManager',
            $mockEntityManager
        );
        $serviceLocator->setService(
            'Rcm\Service\CurrentSite',
            $mockCurrentSite
        );

        $factory = new PageValidatorFactory();
        $object = $factory->createService($serviceLocator);

        $this->assertTrue($object instanceof Page);
    }
}
