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

        $mockPageManager = $this->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $pageValidator = $this->getMockBuilder('\Rcm\Validator\Page')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPageManager->expects($this->any())
            ->method('getPageValidator')
            ->will($this->returnValue($pageValidator));

        $serviceLocator = new ServiceManager();
        $serviceLocator->setService(
            'Rcm\Service\PageManager',
            $mockPageManager
        );

        $factory = new PageValidatorFactory();
        $object = $factory->createService($serviceLocator);

        $this->assertTrue($object instanceof Page);
    }
}
