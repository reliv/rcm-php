<?php
/**
 * Test for Factory MainLayoutValidatorFactory
 *
 * This file contains the test for the MainLayoutValidatorFactory.
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

use Rcm\Factory\MainLayoutValidatorFactory;
use Rcm\Validator\MainLayout;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory MainLayoutValidatorFactory
 *
 * Test for Factory MainLayoutValidatorFactory
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
class MainLayoutValidatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\MainLayoutValidatorFactory
     */
    public function testCreateService()
    {

        $mockCurrentSite = $this->getMockBuilder('\Rcm\Entity\Site')
            ->disableOriginalConstructor()
            ->getMock();

        $mockLayoutManager = $this->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocator = new ServiceManager();

        $serviceLocator->setService(
            'Rcm\Service\LayoutManager',
            $mockLayoutManager
        );

        $serviceLocator->setService(
            'Rcm\Service\CurrentSite',
            $mockCurrentSite
        );

        $factory = new MainLayoutValidatorFactory();
        $object = $factory->createService($serviceLocator);

        $this->assertTrue($object instanceof MainLayout);
    }
}
