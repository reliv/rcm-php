<?php
/**
 * AbstractAuthServiceListenersTest.php
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

use RcmUser\Authentication\Event\AbstractAuthServiceListeners;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class AbstractAuthServiceListenersTest
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
 * @covers    \RcmUser\Authentication\Event\AbstractAuthServiceListeners
 */
class AbstractAuthServiceListenersTest extends Zf2TestCase
{
    /**
     * @var AbstractAuthServiceListeners $abstractAuthServiceListeners
     */
    public $abstractAuthServiceListeners;


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

        $this->authenticationService = $this->getMockBuilder(
            \RcmUser\Authentication\Service\AuthenticationService::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->abstractAuthServiceListeners
            = new AbstractAuthServiceListeners();
        $this->abstractAuthServiceListeners->setAuthService(
            $this->authenticationService
        );
    }

    public function testConstuct()
    {
        $this->abstractAuthServiceListeners
            = new AbstractAuthServiceListeners();
        $this->abstractAuthServiceListeners->setAuthService(
            $this->authenticationService
        );

        $this->assertInstanceOf(
            \RcmUser\Authentication\Service\AuthenticationService::class,
            $this->abstractAuthServiceListeners->getAuthService()
        );
    }

    public function testAttachDetach()
    {
        $this->abstractAuthServiceListeners->attach(
            $this->eventManagerInterface
        );

        $this->abstractAuthServiceListeners->detach(
            $this->eventManagerInterface
        );
    }

    public function testMethods()
    {
        $result = $this->abstractAuthServiceListeners->onValidateCredentials(
            $this->event
        );

        $this->assertFalse($result->isValid());

        $result
            = $this->abstractAuthServiceListeners->onValidateCredentialsSuccess(
                $this->event
            );

        $this->assertFalse($result->isValid());

        $result
            = $this->abstractAuthServiceListeners->onValidateCredentialsFail(
                $this->event
            );

        $this->assertFalse($result->isValid());

        $result = $this->abstractAuthServiceListeners->onAuthenticate(
            $this->event
        );

        $this->assertFalse($result->isValid());

        $result = $this->abstractAuthServiceListeners->onAuthenticateSuccess(
            $this->event
        );

        $this->assertFalse($result->isValid());

        $result = $this->abstractAuthServiceListeners->onAuthenticateFail(
            $this->event
        );

        $this->assertFalse($result->isValid());
    }

    /**
     * testOnClearIdentity
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testOnClearIdentity()
    {
        $result = $this->abstractAuthServiceListeners->onClearIdentity(
            $this->event
        );
    }

    /**
     * testOnHasIdentity
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testOnHasIdentity()
    {
        $result = $this->abstractAuthServiceListeners->onHasIdentity(
            $this->event
        );
    }

    /**
     * testOnSetIdentity
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testOnSetIdentity()
    {
        $result = $this->abstractAuthServiceListeners->onSetIdentity(
            $this->event
        );
    }

    /**
     * testOnGetIdentity
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testOnGetIdentity()
    {
        $result = $this->abstractAuthServiceListeners->onGetIdentity(
            $this->event
        );
    }
}
