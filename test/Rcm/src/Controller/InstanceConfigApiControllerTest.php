<?php
/**
 * InstanceConfigApiControllerTest.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmTest\Controller
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmTest\Controller;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Controller\InstanceConfigApiController;
use Zend\ServiceManager\ServiceManager;


/**
 * InstanceConfigApiControllerTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmTest\Controller
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class InstanceConfigApiControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testUnauthorized()
    {
        $siteMgr = $this
            ->getMockBuilder('Rcm\Entity\Site')
            ->disableOriginalConstructor()
            ->getMock();

        $userSvc = $this
            ->getMockBuilder('RcmUser\Service\RcmUserService')
            ->disableOriginalConstructor()
            ->getMock();

        $userSvc->expects($this->any())
            ->method('isAllowed')
            ->will($this->returnValue(false));

        $serviceMgr = new ServiceManager();
        $serviceMgr->setService('Rcm\Service\CurrentSite', $siteMgr);
        $serviceMgr->setService('RcmUser\Service\RcmUserService', $userSvc);

        $unit = new InstanceConfigApiController();
        $unit->setServiceLocator($serviceMgr);

        $response = $unit->get('778');
        $this->assertEquals(
            401,
            $response->getStatusCode()
        );
    }
} 