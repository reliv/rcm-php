<?php
/**
 * UserAuthenticationServiceTest.php
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Authentication\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Authentication\Service;

use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use Zend\Authentication\Result;
use Zend\EventManager\EventManager;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserAuthenticationServiceTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @covers \RcmUser\Authentication\Service\UserAuthenticationService
 */
class UserAuthenticationServiceTest extends Zf2TestCase
{

    public $userAuthenticationService;
    public $userAuthenticationServiceUserResult;
    public $responseCollection;
    public $eventManager;

    /**
     * getUserAuthenticationService
     *
     * @return UserAuthenticationService
     */
    public function getUserAuthenticationService()
    {
        if (!isset($this->userAuthenticationService)) {
            $this->buildUserAuthenticationService();
        }

        return $this->userAuthenticationService;
    }

    public function getUserAuthenticationServiceUserResult()
    {
        if (!isset($this->userAuthenticationServiceUserResult)) {
            $this->buildUserAuthenticationServiceUserResult();
        }

        return $this->userAuthenticationServiceUserResult;
    }

    public function buildUserAuthenticationService()
    {
        $user = new User();
        $user->setId('123');

        $result = new Result(Result::SUCCESS, $user);

        $this->responseCollection = $this->getMockBuilder(
            '\Zend\EventManager\ResponseCollection'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->responseCollection->expects($this->any())
            ->method('last')
            ->will($this->returnValue($result));
        $this->responseCollection->expects($this->any())
            ->method('stopped')
            ->will($this->returnValue(false));

        $this->eventManager = $this->getMockBuilder(
            '\Zend\EventManager\EventManagerInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventManager->expects($this->any())
            ->method('trigger')
            ->will($this->returnValue($this->responseCollection));

        $this->userAuthenticationService = new UserAuthenticationService($this->eventManager);
    }

    public function buildUserAuthenticationServiceUserResult()
    {
        $user = new User();
        $user->setId('123');

        $responseCollection = $this->getMockBuilder(
            '\Zend\EventManager\ResponseCollection'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $responseCollection->expects($this->any())
            ->method('last')
            ->will($this->returnValue($user));
        $responseCollection->expects($this->any())
            ->method('stopped')
            ->will($this->returnValue(false));

        $eventManager = $this->getMockBuilder(
            '\Zend\EventManager\EventManagerInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $eventManager->expects($this->any())
            ->method('trigger')
            ->will($this->returnValue($responseCollection));

        $this->userAuthenticationServiceUserResult
            = new UserAuthenticationService($eventManager);
    }

    public function testValidateCredentials()
    {
        $user = new User();

        $result = $this->getUserAuthenticationService()->validateCredentials(
            $user
        );

        $this->assertInstanceOf(
            '\Zend\Authentication\Result',
            $result,
            'Incorrect Result returned.'
        );
        $this->assertInstanceOf(
            \RcmUser\User\Entity\UserInterface::class,
            $result->getIdentity(),
            'Result did not contain User.'
        );
    }

    public function testAuthenticate()
    {
        $user = new User();

        $result = $this->getUserAuthenticationService()->authenticate($user);

        $this->assertInstanceOf(
            \Zend\Authentication\Result::class,
            $result,
            'Incorrect Result returned.'
        );
        $this->assertInstanceOf(
            \RcmUser\User\Entity\UserInterface::class,
            $result->getIdentity(),
            'Result did not contain User.'
        );
    }

    public function testClearIdentity()
    {
        $this->getUserAuthenticationService()->clearIdentity();
    }

    public function testSetIdentity()
    {
        $this->getUserAuthenticationServiceUserResult()->getIdentity(
        );
    }

    public function testHasIdentity()
    {
        $result = $this->getUserAuthenticationService()->hasIdentity();
    }

    public function testSetGetIdentity()
    {
        /**
         * @var Result $result
         */
        $user = $this->getUserAuthenticationServiceUserResult()->getIdentity();

        $this->assertInstanceOf(
            \RcmUser\User\Entity\UserInterface::class,
            $user,
            'Result is not User.'
        );

        $user->setUsername('NEW_USER_NAME!');

        $this->getUserAuthenticationServiceUserResult()->setIdentity($user);

        $newuser = $this->getUserAuthenticationServiceUserResult()->getIdentity(
        );

        $this->assertEquals('NEW_USER_NAME!', $newuser->getUsername());
    }
}
