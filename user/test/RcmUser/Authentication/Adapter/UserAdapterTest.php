<?php
/**
 * UserAdapterTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Authentication\Adapter
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Authentication\Adapter;

use RcmUser\Authentication\Adapter\UserAdapter;
use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use RcmUser\User\Result;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserAdapterTest
 *
 * UserAdapterTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Authentication\Adapter
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Authentication\Adapter\UserAdapter
 */
class UserAdapterTest extends Zf2TestCase
{
    /**
     * userResultCallback
     *
     * @return Result
     */
    public function userResultCallback()
    {
        $args = func_get_args();

        $user = $args[0];

        $existingUser = new User('123');

        if ($user->getUsername() == 'badusername') {
            return new Result(
                null,
                Result::CODE_FAIL,
                'not found'
            );
        }

        $existingUser->setPassword('#hash#');

        return new Result($existingUser);
    }

    /**
     * testBuildUserAdapter
     *
     * @return UserAdapter
     */
    public function testBuildUserAdapter()
    {
        $this->userDataService = $this->getMockBuilder(
            \RcmUser\User\Service\UserDataService::class
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->userDataService->expects($this->any())
            ->method('readUser')
            ->will($this->returnCallback([$this, 'userResultCallback']));

        $encValueMap = [
            ['goodpass', '#hash#', true],
            ['badpass', '#hash#', false],
        ];

        $this->encryptor = $this->getMockBuilder(
            \Zend\Crypt\Password\PasswordInterface::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->encryptor->expects($this->any())
            ->method('create')
            ->will($this->returnValue('#hash#'));

        $this->encryptor->expects($this->any())
            ->method('verify')
            ->will($this->returnValueMap($encValueMap));

        $userAdapter = new UserAdapter(
            $this->userDataService,
            $this->encryptor
        );

        $this->assertEquals(
            $this->encryptor,
            $userAdapter->getEncryptor()
        );

        $this->assertEquals(
            $this->userDataService,
            $userAdapter->getUserDataService()
        );

        return $userAdapter;
    }

    /**
     * testAuth
     *
     * @return void
     */
    public function testAuth()
    {
        $userAdapter = $this->testBuildUserAdapter();

        $user = new User('123');
        $user->setPassword('badpass');

        $userAdapter->setUser($user);

        $result = $userAdapter->authenticate();

        $this->assertFalse(
            $result->isValid(),
            'Username not set should return false'
        );

        $user->setUsername('badusername');

        $userAdapter->setUser($user);

        $result = $userAdapter->authenticate();

        $this->assertFalse(
            $result->isValid(),
            'Bad username should return false'
        );

        $user->setUsername('testusername');

        $userAdapter->setUser($user);

        $result = $userAdapter->authenticate();

        $this->assertFalse(
            $result->isValid(),
            'Bad password should return false'
        );

        $user->setPassword('goodpass');

        $userAdapter->setUser($user);

        $result = $userAdapter->authenticate();

        $this->assertTrue(
            $result->isValid(),
            'Good password should return true'
        );
    }
}
