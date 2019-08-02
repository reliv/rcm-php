<?php

namespace RcmUser\Test\User\Event;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Event\AbstractUserDataServiceListeners;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class AbstractUserDataServiceListenersTest
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
 * @covers    \RcmUser\User\Event\AbstractUserDataServiceListeners
 */
class AbstractUserDataServiceListenersTest extends Zf2TestCase
{
    /**
     * @var AbstractUserDataServiceListeners $abstractUserDataServiceListeners
     */
    public $abstractUserDataServiceListeners;


    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->mockEvent = $this->getMockBuilder(
            '\Zend\EventManager\EventInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

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

        $this->abstractUserDataServiceListeners
            = new AbstractUserDataServiceListeners();
    }

    public function testAttachDetach()
    {
        $this->abstractUserDataServiceListeners->attach(
            $this->mockEventManagerInterface
        );

        $this->abstractUserDataServiceListeners->detach(
            $this->mockEventManagerInterface
        );
    }

    public function testMethods()
    {
        $result = $this->abstractUserDataServiceListeners->onBeforeGetAllUsers(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onGetAllUsers(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onGetAllUsersFail(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onGetAllUsersSuccess(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onBuildUser(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onBeforeCreateUser(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onCreateUser(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onCreateUserFail(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onCreateUserSuccess(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onBeforeReadUser(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onReadUser(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onReadUserFail(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onReadUserSuccess(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onBeforeUpdateUser(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onUpdateUser(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onUpdateUserFail(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onUpdateUserSuccess(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onBeforeDeleteUser(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onDeleteUser(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onDeleteUserFail(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());

        $result = $this->abstractUserDataServiceListeners->onDeleteUserSuccess(
            $this->mockEvent
        );
        $this->assertFalse($result->isSuccess());
    }
}
