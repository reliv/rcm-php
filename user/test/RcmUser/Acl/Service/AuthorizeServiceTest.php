<?php

namespace RcmUser\Test\Acl\Service;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Event\UserEventManager;
use RcmUser\Result;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class AuthorizeServiceTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @covers \RcmUser\Acl\Service\AuthorizeService
 */
class AuthorizeServiceTest extends Zf2TestCase
{

    public $authorizeService;

    /**
     * @return AuthorizeService
     */
    public function getAuthorizeService()
    {
        if (!isset($this->authorizeService)) {
            $this->buildAuthorizeService();
        }

        return $this->authorizeService;
    }

    public function buildAuthorizeService()
    {

        $aclResourceService = $this->getMockBuilder(
            \RcmUser\Acl\Service\AclResourceService::class
        )
            ->disableOriginalConstructor()
            ->getMock();


        $aclDataService = $this->getMockBuilder(
            \RcmUser\Acl\Service\AclDataService::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $userEventManager = $this->getMockBuilder(
            UserEventManager::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $aclDataService->expects($this->any())
            ->method('getNamespacedRoles')
            ->will(
                $this->returnValue(
                    new Result(
                        ['data' => 'here'],
                        Result::CODE_SUCCESS,
                        'Message'
                    )
                )
            );

        $aclDataService->expects($this->any())
            ->method('getSuperAdminRoleId')
            ->will($this->returnValue('admin'));

        $aclDataService->expects($this->any())
            ->method('getAllRules')
            ->will($this->returnValue([]));

        $aclDataService->expects($this->any())
            ->method('getRulesByResource')
            ->will($this->returnValue([]));

        $this->authorizeService = new AuthorizeService(
            $aclResourceService,
            $aclDataService,
            $userEventManager
        );
    }

    public function testGetSet()
    {
        $this->buildAuthorizeService();

        /** @var AuthorizeService $authServ */
        $authServ = $this->getAuthorizeService();

        $this->assertInstanceOf(
            \RcmUser\Acl\Service\AclResourceService::class,
            $authServ->getAclResourceService()
        );
        $this->assertInstanceOf(
            \RcmUser\Acl\Service\AclDataService::class,
            $authServ->getAclDataService()
        );
    }

    public function testGetRoles()
    {
    }

    public function testIsAllowed()
    {
        /* @todo Fix Me
         * $resource = "some.resource";
         *
         * $result = $this->getAuthorizeService()->isAllowed($resource);
         *
         * $this->assertTrue($result, 'True not returned.');
         * */
    }
}
