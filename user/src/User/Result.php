<?php

namespace RcmUser\User;

use RcmUser\User\Entity\UserInterface;

/**
 * Class Result
 *
 * Result
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Result extends \RcmUser\Result
{
    /**
     * @var int CODE_SUCCESS
     */
    const CODE_SUCCESS = 1;
    /**
     * @var int CODE_FAIL
     */
    const CODE_FAIL = 0;

    /**
     * @var int $code
     */
    protected $code = 1;

    /**
     * @var array $messages
     */
    protected $messages = [];

    /**
     * __construct
     *
     * @param UserInterface $user     user
     * @param int           $code     code
     * @param array         $messages messages
     */
    public function __construct(
        $user = null,
        $code = 1,
        $messages = []
    ) {
        $this->setUser($user);

        $this->setCode($code);

        if (!is_array($messages)) {
            $this->setMessage((string)$messages);
        } else {
            $this->setMessages($messages);
        }
    }

    /**
     * setUser
     *
     * @param UserInterface|null $user user
     *
     * @return void
     */
    public function setUser($user)
    {
        $this->setData($user);
    }

    /**
     * getUser
     *
     * @return UserInterface|null
     */
    public function getUser()
    {
        return $this->getData();
    }

    /**
     * isSuccess
     *
     * @return bool
     */
    public function isSuccess()
    {
        if ($this->getCode() >= self::CODE_SUCCESS
            && ($this->data instanceof UserInterface)
        ) {
            return true;
        }

        return false;
    }
}
