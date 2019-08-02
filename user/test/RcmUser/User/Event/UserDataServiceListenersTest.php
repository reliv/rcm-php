<?php
/**
 * UserDataServiceListenersTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\User\Event;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use RcmUser\User\Event\UserDataServiceListeners;
use RcmUser\User\Result;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserDataServiceListenersTest
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
 * @covers    \RcmUser\User\Event\UserDataServiceListeners
 */
class UserDataServiceListenersTest extends Zf2TestCase
{
    /**
     * @var \RcmUser\User\Event\UserDataServiceListeners $userDataServiceListeners
     */
    public $userDataServiceListeners;

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
        $this->mockEvent->expects($this->any())
            ->method('getParam')
            ->will(
                $this->returnValue(
                    new User('1231')
                )
            );

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
     * buildSuccessCase
     *
     * @return void
     */
    public function buildSuccessCase()
    {
        //
        $this->userResult = new Result(
            new User('123'),
            Result::CODE_SUCCESS
        );

        $this->buildService();
    }

    /**
     * buildFailCase
     *
     * @return void
     */
    public function buildFailCase()
    {
        //
        $this->userResult = new Result(
            null,
            Result::CODE_FAIL,
            "FAIL"
        );

        $this->buildService();
    }

    /**
     * buildService
     *
     * @return void
     */
    public function buildService()
    {
        //
        $this->userDataMapper = $this->getMockBuilder(
            \RcmUser\User\Db\UserDataMapperInterface::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->userDataMapper->expects($this->any())
            ->method('fetchAll')
            ->will($this->returnValue($this->userResult));

        $this->userDataMapper->expects($this->any())
            ->method('create')
            ->will($this->returnValue($this->userResult));

        $this->userDataMapper->expects($this->any())
            ->method('read')
            ->will($this->returnValue($this->userResult));

        $this->userDataMapper->expects($this->any())
            ->method('update')
            ->will($this->returnValue($this->userResult));

        $this->userDataMapper->expects($this->any())
            ->method('delete')
            ->will($this->returnValue($this->userResult));

        $this->userDataServiceListeners = new UserDataServiceListeners();

        $this->userDataServiceListeners->setUserDataMapper(
            $this->userDataMapper
        );
    }

    /**
     * testSetGet
     *
     * @return void
     */
    public function testSetGet()
    {
        $this->buildSuccessCase();

        $this->userDataServiceListeners->setUserDataMapper(
            $this->userDataMapper
        );

        $this->assertInstanceOf(
            \RcmUser\User\Db\UserDataMapperInterface::class,
            $this->userDataServiceListeners->getUserDataMapper()
        );
    }

    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $this->buildSuccessCase();

        $result = $this->userDataServiceListeners->onGetAllUsers(
            $this->mockEvent
        );

        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userDataServiceListeners->onCreateUser(
            $this->mockEvent
        );

        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userDataServiceListeners->onReadUser($this->mockEvent);

        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userDataServiceListeners->onUpdateUser(
            $this->mockEvent
        );

        $this->assertTrue(
            $result->isSuccess()
        );

        $result = $this->userDataServiceListeners->onDeleteUser(
            $this->mockEvent
        );

        $this->assertTrue(
            $result->isSuccess()
        );
    }
}
