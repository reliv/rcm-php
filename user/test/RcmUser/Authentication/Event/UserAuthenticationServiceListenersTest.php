<?php
/**
 * UserAuthenticationServiceListenersTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Authentication\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Authentication\Event;

use RcmUser\Authentication\Event\UserAuthenticationServiceListeners;
use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use Zend\Authentication\Result;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserAuthenticationServiceListenersTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Authentication\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Authentication\Event\UserAuthenticationServiceListeners
 */
class UserAuthenticationServiceListenersTest extends Zf2TestCase
{
    /**
     * @var \RcmUser\Authentication\Event\UserAuthenticationServiceListeners $userAuthenticationServiceListeners
     */
    public $userAuthenticationServiceListeners;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->event = $this->getMockBuilder(
            '\Zend\EventManager\EventInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->event->expects($this->any())
            ->method('getParam')
            ->will($this->returnValue(new User('123')));

        //
        $this->eventManagerInterface = $this->getMockBuilder(
            '\Zend\EventManager\EventManagerInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventManagerInterface->expects($this->any())
            ->method('getSharedManager')
            ->will($this->returnValue($this->eventManagerInterface));
        $this->eventManagerInterface->expects($this->any())
            ->method('detach')
            ->will($this->returnValue(true));

        $this->adapter = $this->getMockBuilder(
            \RcmUser\Authentication\Adapter\UserAdapter::class
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->adapter->expects($this->any())
            ->method('authenticate')
            ->will(
                $this->returnValue(new Result(Result::SUCCESS, new User('123')))
            );

        $this->adapter->expects($this->any())
            ->method('withUser')
            ->will(
                $this->returnValue(clone $this->adapter)
            );
        //
        $this->authenticationService = $this->getMockBuilder(
            \RcmUser\Authentication\Service\AuthenticationService::class
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->authenticationService->expects($this->any())
            ->method('getAdapter')
            ->will($this->returnValue($this->adapter));
        $this->authenticationService->expects($this->any())
            ->method('authenticate')
            ->will(
                $this->returnValue(new Result(Result::SUCCESS, new User('123')))
            );
        $this->authenticationService->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(true));


        $this->userAuthenticationServiceListeners = new UserAuthenticationServiceListeners(
            $this->authenticationService
        );
    }

    public function buildFailAuthServ()
    {
        $this->authenticationService = $this->getMockBuilder(
            \RcmUser\Authentication\Service\AuthenticationService::class
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->authenticationService->expects($this->any())
            ->method('getAdapter')
            ->will($this->returnValue($this->adapter));
        $this->authenticationService->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue(new Result(Result::FAILURE, null)));
        $this->authenticationService->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(false));
    }

    public function testMethods()
    {
        $this->setUp();

        $result = $this->userAuthenticationServiceListeners->onValidateCredentials(
            $this->event
        );

        $this->assertTrue($result->isValid());

        $result = $this->userAuthenticationServiceListeners->onAuthenticate(
            $this->event
        );

        $this->assertTrue($result->isValid());

        $result = $this->userAuthenticationServiceListeners->onClearIdentity(
            $this->event
        );

        $result = $this->userAuthenticationServiceListeners->onHasIdentity(
            $this->event
        );

        $result = $this->userAuthenticationServiceListeners->onSetIdentity(
            $this->event
        );

        $result = $this->userAuthenticationServiceListeners->onGetIdentity(
            $this->event
        );

        $this->buildFailAuthServ();

        $this->userAuthenticationServiceListeners = new UserAuthenticationServiceListeners(
            $this->authenticationService
        );

        $result = $this->userAuthenticationServiceListeners->onGetIdentity(
            $this->event
        );
    }
}
