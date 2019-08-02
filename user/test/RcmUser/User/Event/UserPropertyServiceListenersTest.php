<?php

namespace RcmUser\Test\User\Event;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use RcmUser\User\Entity\UserRoleProperty;
use RcmUser\User\Event\UserPropertyServiceListeners;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserPropertyServiceListenersTest
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
 * @covers    \RcmUser\User\Event\UserPropertyServiceListeners
 */
class UserPropertyServiceListenersTest extends Zf2TestCase
{
    /**
     * @var \RcmUser\User\Event\UserPropertyServiceListeners $userPropertyServiceListeners
     */
    public $userPropertyServiceListeners;

    public $mockEvent;


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

    public function buildSuccessCase()
    {
        $this->mockEventReturn = [
            ['propertyNameSpace', null, UserRoleProperty::PROPERTY_KEY],
            ['data', null, new UserRoleProperty(['SOME', 'ROLES'])],
            ['user', null, new User('123')]
        ];

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
        $this->buildEventManager();

        //
        $this->userPropertyServiceListeners
            = new UserPropertyServiceListeners();
    }

    public function buildFailCase1()
    {
        $this->mockEventReturn = [
            ['propertyNameSpace', null, 'NOPE'],
            ['data', null, new UserRoleProperty(['SOME', 'ROLES'])],
            ['user', null, new User('123')]
        ];

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
        $this->buildEventManager();

        //
        $this->userPropertyServiceListeners
            = new UserPropertyServiceListeners();
    }

    public function buildFailCase2()
    {
        $this->mockEventReturn = [
            ['propertyNameSpace', null, UserRoleProperty::PROPERTY_KEY],
            ['data', null, 'NOPE'],
            ['user', null, new User('123')]
        ];

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
        $this->buildEventManager();

        //
        $this->userPropertyServiceListeners
            = new UserPropertyServiceListeners();
    }

    public function test()
    {
        $this->buildSuccessCase();

        $pk = $this->userPropertyServiceListeners->getUserPropertyKey();

        $this->assertEquals(
            UserRoleProperty::PROPERTY_KEY,
            $pk
        );

        $result = $this->userPropertyServiceListeners->onPopulateUserProperty(
            $this->mockEvent
        );

        $this->assertInstanceOf(
            \RcmUser\Result::class,
            $result
        );

        $this->buildFailCase1();

        $result = $this->userPropertyServiceListeners->onPopulateUserProperty(
            $this->mockEvent
        );

        $this->assertFalse(
            $result
        );

        $this->buildFailCase2();


        $result = $this->userPropertyServiceListeners->onPopulateUserProperty(
            $this->mockEvent
        );
        $this->assertInstanceOf(
            \RcmUser\Result::class,
            $result
        );
        $this->assertFalse(
            $result->isSuccess()
        );
    }
}
