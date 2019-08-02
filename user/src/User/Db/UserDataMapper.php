<?php

namespace RcmUser\User\Db;

use RcmUser\Exception\RcmUserException;
use RcmUser\User\Data\UserDataPreparerInterface;
use RcmUser\User\Data\UserValidatorInterface;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Result;

/**
 * Class UserDataMapper
 *
 * UserDataMapper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserDataMapper implements UserDataMapperInterface
{
    const ID_FIELD = 'id';
    const USERNAME_FIELD = 'username';

    /**
     * @var UserDataPreparerInterface
     */
    protected $userDataPreparer;

    /**
     * @var UserValidatorInterface
     */
    protected $userValidator;

    /**
     * setUserDataPreparer
     *
     * @param UserDataPreparerInterface $userDataPreparer userDataPreparer
     *
     * @return void
     */
    public function setUserDataPreparer(
        UserDataPreparerInterface $userDataPreparer
    ) {
        $this->userDataPreparer = $userDataPreparer;
    }

    /**
     * getUserDataPreparer
     *
     * @return UserDataPreparerInterface
     */
    public function getUserDataPreparer()
    {
        return $this->userDataPreparer;
    }

    /**
     * setUserValidator
     *
     * @param UserValidatorInterface $userValidator userValidator
     *
     * @return void
     */
    public function setUserValidator(UserValidatorInterface $userValidator)
    {
        $this->userValidator = $userValidator;
    }

    /**
     * getUserValidator
     *
     * @return UserValidatorInterface
     */
    public function getUserValidator()
    {
        return $this->userValidator;
    }

    /**
     * fetchAll
     *
     * @param array $options options
     *
     * @return mixed
     * @throws RcmUserException
     */
    public function fetchAll(
        $options = []
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * fetchById
     *
     * @param mixed $id user id
     *
     * @return Result
     * @throws RcmUserException
     */
    public function fetchById($id)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * fetchByUsername
     *
     * @param string $username username
     *
     * @return Result
     * @throws RcmUserException
     */
    public function fetchByUsername($username)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * create
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     *
     * @return Result
     * @throws RcmUserException
     */
    public function create(
        UserInterface $requestUser,
        UserInterface $responseUser
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * read
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     *
     * @return Result
     * @throws RcmUserException
     */
    public function read(
        UserInterface $requestUser,
        UserInterface $responseUser
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * update
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     * @param UserInterface $existingUser existingUser
     *
     * @return mixed|Result
     * @throws RcmUserException
     */
    public function update(
        UserInterface $requestUser,
        UserInterface $responseUser,
        UserInterface $existingUser
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * delete
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     *
     * @return mixed|Result
     * @throws RcmUserException
     */
    public function delete(
        UserInterface $requestUser,
        UserInterface $responseUser
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * canUpdate
     *
     * @param UserInterface $user user
     *
     * @return bool
     */
    public function canUpdate(UserInterface $user)
    {
        $id = $user->getId();

        if (empty($id)) {
            return false;
        }

        return true;
    }


    /**
     * canDelete
     *
     * @param UserInterface $user user
     *
     * @return bool
     */
    public function canDelete(UserInterface $user)
    {
        return $this->canUpdate($user);
    }
}
