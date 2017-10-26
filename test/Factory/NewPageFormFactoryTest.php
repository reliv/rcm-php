<?php
/**
 * Test for Factory NewPageFormFactory
 *
 * This file contains the test for the NewPageFormFactory.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2017 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmAdminTest\Factory;

require_once __DIR__ . '/../autoload.php';

use RcmAdmin\Factory\NewPageFormFactory;
use RcmAdmin\Form\NewPageForm;
use Zend\Form\FormElementManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory NewPageFormFactory
 *
 * Test for Factory NewPageFormFactory
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class NewPageFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \RcmAdmin\Factory\NewPageFormFactory
     */
    public function testCreateService()
    {
        $mockCurrentSite = $this
            ->getMockBuilder(\Rcm\Entity\Site::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPageRepo= $this
            ->getMockBuilder('\Rcm\Repository\Page')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockPageRepo));

        $mockLayoutManager = $this
            ->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockMainLayoutValidator = $this
            ->getMockBuilder('\Rcm\Validator\MainLayout')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPageValidator = $this
            ->getMockBuilder('\Rcm\Validator\Page')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPageTemplateValidator = $this
            ->getMockBuilder('\Rcm\Validator\PageTemplate')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();

        $serviceManager->setService(
            \Rcm\Validator\PageTemplate::class,
            $mockPageTemplateValidator
        );

        $serviceManager->setService(
            \Rcm\Validator\Page::class,
            $mockPageValidator
        );

        $serviceManager->setService(
            \Rcm\Validator\MainLayout::class,
            $mockMainLayoutValidator
        );

        $serviceManager->setService(
            \Rcm\Service\CurrentSite::class,
            $mockCurrentSite
        );

        $serviceManager->setService(
            'Doctrine\ORM\EntityManager',
            $mockEntityManager
        );

        $serviceManager->setService(
            \Rcm\Service\LayoutManager::class,
            $mockLayoutManager
        );

        $formManager = new FormElementManager();
        $formManager->setServiceLocator($serviceManager);

        $factory = new NewPageFormFactory();
        $object = $factory->__invoke($formManager);

        $this->assertTrue($object instanceof NewPageForm);
    }
}
