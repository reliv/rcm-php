<?php
/**
 * InstanceConfigApiControllerTest.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmTest\Controller
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2018 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmTest\Controller;

require_once __DIR__ . '/../../../autoload.php';

use phpDocumentor\Reflection\Types\Resource;
use Rcm\Acl\ResourceName;
use Rcm\Acl\ResourceNameRcm;
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
 * @copyright 2018 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class InstanceConfigApiControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testUnauthorized()
    {
        $siteMgr = $this
            ->getMockBuilder(\Rcm\Entity\Site::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userSvc = $this
            ->getMockBuilder(\RcmUser\Service\RcmUserService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userSvc->expects($this->any())
            ->method('isAllowed')
            ->will($this->returnValue(false));

        $serviceMgr = new ServiceManager();
        $serviceMgr->setService(\Rcm\Service\CurrentSite::class, $siteMgr);
        $serviceMgr->setService(\RcmUser\Service\RcmUserService::class, $userSvc);
        $serviceMgr->setService(ResourceName::class, new ResourceNameRcm());

        $unit = new InstanceConfigApiController($serviceMgr);

        $response = $unit->get('778');
        $this->assertEquals(
            401,
            $response->getStatusCode()
        );
    }
}
