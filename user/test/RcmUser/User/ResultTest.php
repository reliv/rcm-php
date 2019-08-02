<?php

namespace RcmUser\Test\User;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use RcmUser\User\Result;

require_once __DIR__ . '/../../Zf2TestCase.php';

/**
 * Class ResultTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Result
 */
class ResultTest extends Zf2TestCase
{
    /**
     * testSetGet
     *
     * @covers \RcmUser\User\Result
     *
     * @return void
     */
    public function testSetGet()
    {
        $result = new Result(null, Result::CODE_SUCCESS, 'DEFAULT_MESSAGE');
        $user = new User();

        $result->setCode(Result::CODE_SUCCESS);
        $result->setUser($user);
        $messages = ['message 1'];
        $result->setMessages($messages);
        $result->setMessage('message 2');

        $this->assertTrue(
            $result->getCode() === Result::CODE_SUCCESS,
            'Data not returned.'
        );
        $this->assertTrue($result->getUser() === $user, 'Data not returned.');

        $returnedMessages = $result->getMessages();
        $this->assertTrue(
            is_array($returnedMessages),
            'Messages should be array.'
        );

        $this->assertTrue(
            $returnedMessages[0] === $messages[0],
            'Message 1 not returned.'
        );

        $this->assertTrue(
            $result->getMessage(1) === 'message 2',
            'Message 2 not returned.'
        );

        $this->assertTrue(
            $result->getMessage('nope', 'not_found') === 'not_found',
            'Message unset default not returned.'
        );
    }

    /**
     * testIsSuccess
     *
     * @covers \RcmUser\User\Result::isSuccess
     *
     * @return void
     */
    public function testIsSuccess()
    {
        $result = new Result();
        $user = new User();

        $result->setCode(Result::CODE_SUCCESS);
        $result->setUser($user);

        $this->assertTrue($result->isSuccess(), 'Success not returned.');

        $result->setCode(Result::CODE_FAIL);

        $this->assertFalse($result->isSuccess(), 'Success returned.');
    }
}
