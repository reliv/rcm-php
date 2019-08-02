<?php

namespace RcmUser\User\Db;

use Doctrine\ORM\EntityManager;
use RcmUser\Db\DoctrineMapperInterface;
use RcmUser\User\Entity\DoctrineUserInterface;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Result;

/**
 * Class DoctrineUserDataMapper
 *
 * DoctrineUserDataMapper
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
class DoctrineUserDataMapper extends UserDataMapper implements DoctrineMapperInterface
{
    /**
     * USER_DELETED_STATE
     */
    const USER_DELETED_STATE = 'deleted';

    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @var
     */
    protected $entityClass;

    /**
     * setEntityManager
     *
     * @param EntityManager $entityManager entityManager
     *
     * @return void
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * getEntityManager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * setEntityClass
     *
     * @param string $entityClass entityClass namespace
     *
     * @return void
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = (string)$entityClass;
    }

    /**
     * getEntityClass
     *
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * fetchAll
     *
     * @param array $options options
     *
     * @return \RcmUser\Result
     */
    public function fetchAll(
        $options = []
    ) {
        $query = $this->getEntityManager()->createQuery(
            'SELECT user FROM ' . $this->getEntityClass() . ' user '
            . 'WHERE user.state != ?2'
        );
        $query->setParameter(
            2,
            self::USER_DELETED_STATE
        );

        $users = $query->getResult();

        return new \RcmUser\Result($users);
    }

    /**
     * fetchById
     *
     * @param mixed $id id
     *
     * @return Result
     */
    public function fetchById($id)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT user FROM ' . $this->getEntityClass() . ' user '
            . 'WHERE user.id = ?1 ' . 'AND user.state != ?2'
        );
        $query->setParameter(
            1,
            $id
        );
        $query->setParameter(
            2,
            self::USER_DELETED_STATE
        );

        $users = $query->getResult();

        if (empty($users) || !isset($users[0])) {
            return new Result(null, Result::CODE_FAIL, 'User could not be found by id.');
        }

        $user = $users[0];

        // This is so we get a fresh user every time
        $this->getEntityManager()->refresh($user);

        return new Result($user);
    }

    /**
     * fetchByUsername
     *
     * @param string $username username
     *
     * @return Result
     */
    public function fetchByUsername($username)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT user FROM ' . $this->getEntityClass() . ' user '
            . 'WHERE user.username = ?1 ' . 'AND user.state != ?2'
        );
        $query->setParameter(
            1,
            $username
        );
        $query->setParameter(
            2,
            self::USER_DELETED_STATE
        );

        $users = $query->getResult();

        if (empty($users) || !isset($users[0])) {
            return new Result(null, Result::CODE_FAIL, 'User could not be found by username.');
        }

        $user = $users[0];

        // This is so we get a fresh user every time
        $this->getEntityManager()->refresh($user);

        return new Result($user);
    }

    /**
     * create
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     *
     * @return \RcmUser\User\Result
     */
    public function create(
        UserInterface $requestUser,
        UserInterface $responseUser
    ) {
        /* VALIDATE */
        $result = $this->getUserValidator()->validateCreateUser(
            $requestUser,
            $responseUser
        );

        if (!$result->isSuccess()) {
            $responseUser->setState(null);

            return $result;
        }

        /* PREPARE */
        // make sure no duplicates
        $dupUser = $this->fetchByUsername(
            $requestUser->getUsername()
        );

        if ($dupUser->isSuccess()) {
            $responseUser->setState(null);

            // ERROR - user exists
            return new Result(null, Result::CODE_FAIL, 'User could not be prepared, duplicate username.');
        }

        $result = $this->getUserDataPreparer()->prepareUserCreate(
            $requestUser,
            $responseUser
        );

        if (!$result->isSuccess()) {
            $responseUser->setState(null);

            return $result;
        }

        /* SAVE */
        $responseUser = $this->getValidInstance($responseUser);

        // @todo if error, fail with null and set user state back
        $this->getEntityManager()->persist($responseUser);
        $this->getEntityManager()->flush();

        // @todo verify with read
        // $readResult = $this->read($responseUser, $responseUser);
        return new Result($responseUser);
    }

    /**
     * read
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     *
     * @return mixed|Result
     */
    public function read(
        UserInterface $requestUser,
        UserInterface $responseUser
    ) {
        $id = $requestUser->getId();

        if (!empty($id)) {
            $result = $this->fetchById($id);

            if ($result->isSuccess()) {
                // we want to populate everything but properties.
                $responseUser->populate(
                    $result->getUser(),
                    ['properties']
                );
                $result->setUser($responseUser);

                return $result;
            }
        }

        $username = $requestUser->getUsername();

        if (!empty($username)) {
            $result = $this->fetchByUsername($username);

            if ($result->isSuccess()) {
                // we want to populate everything but properties.
                $responseUser->populate(
                    $result->getUser(),
                    ['properties']
                );
                $result->setUser($responseUser);
            }

            return $result;
        }

        return new Result(null, Result::CODE_FAIL, 'User can not be read.');
    }

    /**
     * update
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     * @param UserInterface $existingUser existingUser
     *
     * @return \RcmUser\User|Result
     */
    public function update(
        UserInterface $requestUser,
        UserInterface $responseUser,
        UserInterface $existingUser
    ) {
        /* VALIDATE */
        if (!$this->canUpdate($requestUser)) {
            // error, cannot update
            return new Result($responseUser, Result::CODE_FAIL, 'User cannot be updated, id required for update.');
        }

        // USERNAME CHECKS
        $requestUsername = $requestUser->getUsername();
        $existingUserName = $existingUser->getUsername();

        // if username changed:
        if ($existingUserName !== $requestUsername) {
            // make sure no duplicates
            $dupUser = $this->fetchByUsername($requestUsername);

            if ($dupUser->isSuccess()) {
                // ERROR - user exists
                return new Result($responseUser, Result::CODE_FAIL, 'User could not be prepared, duplicate username.');
            }

            $responseUser->setUsername($requestUsername);
        }

        $result = $this->getUserValidator()->validateUpdateUser(
            $requestUser,
            $responseUser,
            $existingUser
        );

        if (!$result->isSuccess()) {
            return $result;
        }

        /* PREPARE */
        $result = $this->getUserDataPreparer()->prepareUserUpdate(
            $requestUser,
            $responseUser,
            $existingUser
        );

        if (!$result->isSuccess()) {
            return $result;
        }

        /* SAVE */
        $responseUser = $this->getValidInstance($responseUser);

        // @todo if error, fail with null
        $this->getEntityManager()->merge($responseUser);
        $this->getEntityManager()->flush();

        return new Result($responseUser);
    }

    /**
     * delete
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     *
     * @return mixed|Result
     */
    public function delete(
        UserInterface $requestUser,
        UserInterface $responseUser
    ) {
        /* VALIDATE */
        if (!$this->canDelete($requestUser)) {
            // error, cannot update
            return new Result($responseUser, Result::CODE_FAIL, 'User cannot be deleted, id required for delete.');
        }

        /* PREPARE */
        $responseUser->setUsername($this->buildDeletedUsername($responseUser));
        $responseUser->setState(self::USER_DELETED_STATE);

        /* SAVE */
        $responseUser = $this->getValidInstance($responseUser);

        // @todo if error, fail with null
        $this->getEntityManager()->merge($responseUser);
        /* by default, we should not support true delete
        $this->getEntityManager()->remove($user);
        */
        $this->getEntityManager()->flush();

        return new Result($responseUser);
    }

    /**
     * getValidInstance
     *
     * @param UserInterface $user user
     *
     * @return UserInterface
     */
    public function getValidInstance(UserInterface $user)
    {
        if (!($user instanceof DoctrineUserInterface)) {
            $doctrineUser = new DoctrineUserInterface();
            $doctrineUser->populate($user);

            $user = $doctrineUser;
        }

        return $user;
    }

    /**
     * buildDeletedUsername
     *
     * @param UserInterface $user user
     *
     * @return string|JSON
     */
    public function buildDeletedUsername(UserInterface $user)
    {
        $usernameArr = [
            self::USER_DELETED_STATE,
            $user->getId(),
            $user->getUsername()
        ];

        return json_encode($usernameArr);
    }

    /**
     * parseDeletedUsername
     *
     * @param UserInterface $user user
     *
     * @return null|string
     */
    public function parseDeletedUsername(UserInterface $user)
    {
        try {
            $usernameArr = json_decode(
                $user->getUsername(),
                true
            );
        } catch (\Exception $e) {
            return null;
        }

        if (count($usernameArr) !== 3) {
            return null;
        }

        if ($usernameArr[1] !== $user->getId()) {
            return null;
        }

        return $usernameArr[2];
    }
}
