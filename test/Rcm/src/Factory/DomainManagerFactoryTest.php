<?php
/**
 * Test for Factory DomainManagerFactory
 *
 * This file contains the test for the DomainManagerFactory.
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

use Rcm\Service\DomainManager;
use Rcm\Factory\DomainManagerFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory DomainManagerFactory
 *
 * Test for Factory DomainManagerFactory
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
class DomainManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\DomainManagerFactory
     */
    public function testCreateService()
    {
        $mockDomainRepo = $this->getMockBuilder('\Rcm\Repository\Domain')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockDomainRepo));

        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocator = new ServiceManager();
        $serviceLocator->setService(
            'Doctrine\ORM\EntityManager',
            $mockEntityManager
        );
        $serviceLocator->setService('Rcm\Service\Cache', $mockCache);

        $factory = new DomainManagerFactory();
        $object = $factory->createService($serviceLocator);

        $this->assertTrue($object instanceof DomainManager);
    }
}