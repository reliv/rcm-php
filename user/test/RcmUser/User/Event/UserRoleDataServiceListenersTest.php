<?php

namespace RcmUser\Test\User\Event;

use RcmUser\Result;
use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use RcmUser\User\Entity\UserRoleProperty;
use RcmUser\User\Event\UserRoleDataServiceListeners;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserRoleDataServiceListenersTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Event\UserRoleDataServiceListeners
 */
class UserRoleDataServiceListenersTest extends Zf2TestCase
{
    /**
     * @var \RcmUser\User\Event\UserRoleDataServiceListeners $userRoleDataServiceListeners
     */
    public $userRoleDataServiceListeners;

    /**
     * buildEventManager
     *
     * @return void
     */
    public function buildEventManager()
    {
        //
        $this->mockEventManagerInterface = $this->getMockBuilder(
            '\Zend\EventManager\EventManagerInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockEventManagerInterface->expects($this->any())
            ->method('getSharedManager')
            ->will($this->returnValue($this->mockEventManagerInterface));

        $this->mockEventManagerInterface->expects($this->any())
            ->method('detach')
            ->will($this->returnValue(true));
    }

    /**
     * buildUserRoleService
     *
     * @param string $case
     *
     * @return void
     */
    public function buildUserRoleService($case = 'success')
    {
        switch ($case) {
            case 'fail_service':
            case 'failAll_service':
                $this->mockRoles = ['SOME', 'ROLES'];
                $this->mockDefaultRoles = ['DEFAULT', 'ROLES'];
                $this->mockResult = new Result([], Result::CODE_FAIL);
                $this->mockDefaultResult = new Result($this->mockDefaultRoles);
                break;
            default:
                $this->mockRoles = ['SOME', 'ROLES'];
                $this->mockDefaultRoles = ['DEFAULT', 'ROLES'];
                $this->mockResult = new Result($this->mockRoles);
                $this->mockDefaultResult = new Result($this->mockDefaultRoles);
                break;
        }

        //
        $this->userRoleService = $this->getMockBuilder(
            \RcmUser\User\Service\UserRoleService::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->userRoleService->expects($this->any())
            ->method('readRoles')
            ->will(
                $this->returnValue(
                    $this->mockResult
                )
            );

        $this->userRoleService->expects($this->any())
            ->method('createRoles')
            ->will(
                $this->returnValue(
                    $this->mockResult
                )
            );

        $this->userRoleService->expects($this->any())
            ->method('updateRoles')
            ->will(
                $this->returnValue(
                    $this->mockResult
                )
            );

        $this->userRoleService->expects($this->any())
            ->method('deleteRoles')
            ->will(
                $this->returnValue(
                    $this->mockResult
                )
            );

        $this->userRoleService->expects($this->any())
            ->method('getDefaultUserRoleIds')
            ->will(
                $this->returnValue(
                    $this->mockDefaultResult
                )
            );

        $this->userRoleService->expects($this->any())
            ->method('buildUserRoleProperty')
            ->will(
                $this->returnValue(
                    new UserRoleProperty($this->mockRoles)
                )
            );

        $this->userRoleService->expects($this->any())
            ->method('buildValidUserRoleProperty')
            ->will(
                $this->returnValue(
                    new UserRoleProperty($this->mockRoles)
                )
            );

        $this->userRoleService->expects($this->any())
            ->method('buildValidRoles')
            ->will(
                $this->returnValue(
                    $this->mockRoles
                )
            );
    }

    public function buildEvent($case = 'success')
    {
        switch ($case) {
            case 'fail_event':
                $this->mockEventReturn = [
                    [
                        'result',
                        null,
                        new \RcmUser\User\Result(null, Result::CODE_FAIL)
                    ],
                    [
                        'data',
                        null,
                        new UserRoleProperty(['SOME', 'ROLES'])
                    ],
                    ['requestUser', null, new User('123')],
                    ['responseUser', null, new User('123')],
                    ['existingUser', null, new User('123')]
                ];
                break;

            case 'successAll':
            case 'failAll_service':
                $this->mockEventReturn = [
                    [
                        'result',
                        null,
                        new Result([new User('123')]),
                    ]
                ];
                break;

            default:
                $requestUser = new User('123');
                $requestUser->setProperty(UserRoleProperty::PROPERTY_KEY, new UserRoleProperty(['ROLES']));

                $this->mockEventReturn = [
                    [
                        'result',
                        null,
                        new \RcmUser\User\Result(new User('123'))
                    ],
                    [
                        'data',
                        null,
                        new UserRoleProperty(['ROLES'])
                    ],
                    ['requestUser', null, $requestUser],
                    ['responseUser', null, new User('123')],
                    ['existingUser', null, new User('123')]
                ];
                break;
        }

        $this->buildUserRoleService($case);

        $this->mockEvent = $this->getMockBuilder(
            '\Zend\EventManager\EventInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockEvent->expects($this->any())
            ->method('getParam')
            ->will(
                $this->returnValueMap(
                    $this->mockEventReturn
                )
            );

        //
        $this->userRoleDataServiceListeners
            = new UserRoleDataServiceListeners();

        $this->userRoleDataServiceListeners->setUserRoleService(
            $this->userRoleService
        );
    }

    public function testSetGet()
    {
        $this->buildEvent('success');

        $this->userRoleDataServiceListeners->setUserRoleService(
            $this->userRoleService
        );

        $this->assertInstanceOf(
            \RcmUser\User\Service\UserRoleService::class,
            $this->userRoleDataServiceListeners->getUserRoleService()
        );

        $this->assertEquals(
            UserRoleProperty::PROPERTY_KEY,
            $this->userRoleDataServiceListeners->getUserPropertyKey()
        );
    }

    public function testSuccess()
    {
        $this->buildEvent('successAll');

        $result = $this->userRoleDataServiceListeners->onGetAllUsersSuccess(
            $this->mockEvent
        );

        $this->assertInstanceOf(
            \RcmUser\Result::class,
            $result
        );
        $this->assertTrue(
            $result->isSuccess()
        );

        $this->buildEvent('success');

        $result = $this->userRoleDataServiceListeners->onBuildUser(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onCreateUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onReadUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onUpdateUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onDeleteUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertTrue(
            $result->isSuccess()
        );
    }

    /**
     * testFail1
     *
     * @return void
     */
    public function testFailEvent()
    {
        $this->buildEvent('fail_event');

        $result = $this->userRoleDataServiceListeners->onGetAllUsersSuccess(
            $this->mockEvent
        );

        $this->assertInstanceOf(
            \RcmUser\Result::class,
            $result
        );
        $this->assertFalse(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onBuildUser(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onCreateUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertFalse(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onReadUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertFalse(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onUpdateUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertFalse(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onDeleteUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertFalse(
            $result->isSuccess()
        );
    }

    public function testFail2()
    {
        $this->buildEvent('failAll_service');

        $result = $this->userRoleDataServiceListeners->onGetAllUsersSuccess(
            $this->mockEvent
        );

        $this->assertInstanceOf(
            \RcmUser\Result::class,
            $result
        );

        $this->buildEvent('fail_service');

        $result = $this->userRoleDataServiceListeners->onBuildUser(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onCreateUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertFalse(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onReadUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertFalse(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onUpdateUserSuccess(
            $this->mockEvent
        );

        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );

        $this->assertFalse(
            $result->isSuccess()
        );

        $result = $this->userRoleDataServiceListeners->onDeleteUserSuccess(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\User\Result::class,
            $result
        );
        $this->assertFalse(
            $result->isSuccess()
        );
    }

    public function testUtilities()
    {
        $this->buildEvent('success');

        $result = $this->userRoleDataServiceListeners->removeDefaultUserRoleIds(
            $this->mockRoles
        );

        $this->assertEquals(
            ['SOME'],
            $result
        );

        $result = $this->userRoleDataServiceListeners->buildUserRoleProperty(
            $this->mockRoles
        );

        $this->assertInstanceOf(
            \RcmUser\User\Entity\UserRoleProperty::class,
            $result
        );

        $result = $this->userRoleDataServiceListeners->buildValidRoles(
            new User('123'),
            $this->mockRoles
        );

        $this->assertTrue(
            is_array($result)
        );
    }
}
