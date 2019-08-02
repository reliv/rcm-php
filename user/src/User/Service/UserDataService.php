<?php

namespace RcmUser\User\Service;

use RcmUser\Event\EventProvider;
use RcmUser\User\Db\UserDataMapperInterface;
use RcmUser\User\Entity\ReadOnlyUser;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use RcmUser\User\Result;

/**
 * Class UserDataService
 *
 * CRUD Operations
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserDataService extends EventProvider
{
    const EVENT_IDENTIFIER = UserDataService::class;

    const EVENT_BEFORE_GET_ALL_USERS = 'beforeGetAllUsers';
    const EVENT_GET_ALL_USERS = 'getAllUsers';
    const EVENT_GET_ALL_USERS_FAIL = 'getAllUsersFail';
    const EVENT_GET_ALL_USERS_SUCCESS = 'getAllUsersSuccess';

    const EVENT_BUILD_USER = 'buildUser';
    const EVENT_BEFORE_CREATE_USER = 'beforeCreateUser';
    const EVENT_CREATE_USER = 'createUser';
    const EVENT_CREATE_USER_FAIL = 'createUserFail';
    const EVENT_CREATE_USER_SUCCESS = 'createUserSuccess';

    const EVENT_BEFORE_READ_USER = 'beforeReadUser';
    const EVENT_READ_USER = 'readUser';
    const EVENT_READ_USER_FAIL = 'readUserFail';
    const EVENT_READ_USER_SUCCESS = 'readUserSuccess';

    const EVENT_BEFORE_UPDATE_USER = 'beforeUpdateUser';
    const EVENT_UPDATE_USER = 'updateUser';
    const EVENT_UPDATE_USER_FAIL = 'updateUserFail';
    const EVENT_UPDATE_USER_SUCCESS = 'updateUserSuccess';

    const EVENT_BEFORE_DELETE_USER = 'beforeDeleteUser';
    const EVENT_DELETE_USER = 'deleteUser';
    const EVENT_DELETE_USER_FAIL = 'deleteUserFail';
    const EVENT_DELETE_USER_SUCCESS = 'deleteUserSuccess';

    /**
     * @var UserDataMapperInterface $userDataMapper
     */
    protected $userDataMapper;

    /**
     * @var array
     */
    protected $validUserStates = [];

    /**
     * @var string|null $defaultUserState
     */
    protected $defaultUserState = null;

    /**
     * setUserDataMapper
     *
     * @param UserDataMapperInterface $userDataMapper userDataMapper
     *
     * @return void
     */
    public function setUserDataMapper(UserDataMapperInterface $userDataMapper)
    {
        $this->userDataMapper = $userDataMapper;
    }

    /**
     * getUserDataMapper
     *
     * @return UserDataMapperInterface
     */
    public function getUserDataMapper()
    {
        return $this->userDataMapper;
    }

    /**
     * setValidUserStates
     *
     * @param array $validUserStates array of valid user states
     *
     * @return void
     */
    public function setValidUserStates($validUserStates)
    {
        if (!in_array(
            UserInterface::STATE_DISABLED,
            $validUserStates
        )
        ) {
            $validUserStates[] = UserInterface::STATE_DISABLED;
        }
        $this->validUserStates = $validUserStates;
    }

    /**
     * getValidUserStates
     *
     * @return array
     */
    public function getValidUserStates()
    {
        return $this->validUserStates;
    }

    /**
     * setDefaultUserState
     *
     * @param string|null $defaultUserState defaultUserState
     *
     * @return void
     */
    public function setDefaultUserState($defaultUserState)
    {
        $this->defaultUserState = $defaultUserState;
    }

    /**
     * getDefaultUserState
     *
     * @return null|string
     */
    public function getDefaultUserState()
    {
        return $this->defaultUserState;
    }

    /**
     * getAllUsers
     *
     * @param array $options options
     *
     * @return mixed
     */
    public function getAllUsers(
        $options = []
    ) {
        /* @event beforeGetAllUsers */
        $results = $this->getEventManager()->trigger(
            self::EVENT_BEFORE_GET_ALL_USERS,
            $this,
            [
                'options' => $options,
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        /* @event readUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_GET_ALL_USERS,
            $this,
            [
                'options' => $options,
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            $result = $results->last();
            $this->getEventManager()->trigger(
                self::EVENT_GET_ALL_USERS_FAIL,
                $this,
                [
                    'result' => $result,
                    'options' => $options
                ]
            );

            return $result;
        }

        // default result may be changed in success listener
        $result = $results->last();

        /* @event readUserSuccess */
        $this->getEventManager()->trigger(
            self::EVENT_GET_ALL_USERS_SUCCESS,
            $this,
            [
                'result' => $result,
                'options' => $options
            ]
        );

        return $result;
    }

    /**
     * buildUser - Allows events listeners to set default values for a new
     * user as needed.  Very helpful for creating guest or ambiguous users
     *
     * @param UserInterface $requestUser requestUser
     *
     * @return Result
     */
    public function buildUser(UserInterface $requestUser)
    {

        /* <LOW_LEVEL_PREP> */
        $responseUser = new User();
        $responseUser->populate($requestUser);

        $requestUser = new ReadOnlyUser($requestUser);
        /* </LOW_LEVEL_PREP> */

        /* @event buildUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_BUILD_USER,
            $this,
            [
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ]
        );

        return $results->last();
    }

    /**
     * createUser
     *
     * @param UserInterface $requestUser requestUser
     *
     * @return Result
     */
    public function createUser(UserInterface $requestUser)
    {
        $responseUser = new User();
        $responseUser->populate($requestUser);

        $requestUser = new ReadOnlyUser($requestUser);

        $state = $responseUser->getState();
        if (empty($state)) {
            $responseUser->setState($this->getDefaultUserState());
        }
        /* </LOW_LEVEL_PREP> */

        /* @event beforeCreateUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_BEFORE_CREATE_USER,
            $this,
            [
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        /* @event createUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_CREATE_USER,
            $this,
            [
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            $result = $results->last();

            $this->getEventManager()->trigger(
                self::EVENT_CREATE_USER_FAIL,
                $this,
                [
                    'result' => $result,
                    'requestUser' => $requestUser,
                    'responseUser' => $responseUser
                ]
            );

            return $result;
        }

        $result = new Result($responseUser);

        if (!$result->isSuccess()) {
            $this->getEventManager()->trigger(
                self::EVENT_CREATE_USER_FAIL,
                $this,
                [
                    'result' => $result,
                    'requestUser' => $requestUser,
                    'responseUser' => $responseUser
                ]
            );

            return $result;
        }

        /* @event createUserSuccess */
        $this->getEventManager()->trigger(
            self::EVENT_CREATE_USER_SUCCESS,
            $this,
            [
                'result' => $result,
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ]
        );

        return $result;
    }

    /**
     * readUser
     *
     * @param UserInterface $requestUser requestUser
     *
     * @return Result
     */
    public function readUser(UserInterface $requestUser)
    {
        $responseUser = new User();
        $responseUser->populate($requestUser);

        $requestUser = new ReadOnlyUser($requestUser);

        /* @event beforeReadUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_BEFORE_READ_USER,
            $this,
            [
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        /* @event readUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_READ_USER,
            $this,
            [
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            $result = $results->last();
            $this->getEventManager()->trigger(
                self::EVENT_READ_USER_FAIL,
                $this,
                [
                    'result' => $result,
                    'requestUser' => $requestUser,
                    'responseUser' => $responseUser
                ]
            );

            return $result;
        }

        /* <LOW_LEVEL_PREP> */
        $state = $responseUser->getState();
        if (empty($state)) {
            $responseUser->setState($this->getDefaultUserState());
        }
        /* </LOW_LEVEL_PREP> */

        $result = new Result($responseUser);

        /* @event readUserSuccess */
        $this->getEventManager()->trigger(
            self::EVENT_READ_USER_SUCCESS,
            $this,
            [
                'result' => $result,
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ]
        );

        return $result;
    }

    /**
     * updateUser
     *
     * @param UserInterface $requestUser requestUser
     *
     * @return Result
     */
    public function updateUser(UserInterface $requestUser)
    {
        /* <LOW_LEVEL_PREP> */
        // require id
        $id = $requestUser->getId();
        if (empty($id)) {
            return new Result(
                null,
                Result::CODE_FAIL,
                'User Id required for update.'
            );
        }

        // check if exists
        $existingUserResult = $this->readUser($requestUser);

        if (!$existingUserResult->isSuccess()) {
            // ERROR
            return $existingUserResult;
        }

        $existingUser = $existingUserResult->getUser();

        $existingUser = new ReadOnlyUser($existingUser);

        $requestUser->merge($existingUser);

        $responseUser = new User();

        $responseUser->populate($requestUser);

        $requestUser = new ReadOnlyUser($requestUser);

        $state = $responseUser->getState();
        if (empty($state)) {
            $responseUser->setState($this->getDefaultUserState());
        }
        /* </LOW_LEVEL_PREP> */

        /* @event beforeUpdateUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_BEFORE_UPDATE_USER,
            $this,
            [
                'requestUser' => $requestUser,
                'responseUser' => $responseUser,
                'existingUser' => $existingUser
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        /* @event updateUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_UPDATE_USER,
            $this,
            [
                'requestUser' => $requestUser,
                'responseUser' => $responseUser,
                'existingUser' => $existingUser
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            $result = $results->last();
            $this->getEventManager()->trigger(
                self::EVENT_UPDATE_USER_FAIL,
                $this,
                [
                    'result' => $result,
                    'requestUser' => $requestUser,
                    'responseUser' => $responseUser,
                    'existingUser' => $existingUser
                ]
            );

            return $result;
        }

        $result = new Result($responseUser);

        /* @event updateUser */
        $this->getEventManager()->trigger(
            self::EVENT_UPDATE_USER_SUCCESS,
            $this,
            [
                'result' => $result,
                'requestUser' => $requestUser,
                'responseUser' => $responseUser,
                'existingUser' => $existingUser
            ]
        );

        return $result;
    }

    /**
     * deleteUser
     *
     * @param UserInterface $requestUser requestUser
     *
     * @return mixed|Result
     */
    public function deleteUser(UserInterface $requestUser)
    {
        /* <LOW_LEVEL_PREP> */
        // require id
        $id = $requestUser->getId();
        if (empty($id)) {
            return new Result(null, Result::CODE_FAIL, 'User Id required for update.');
        }

        // check if exists
        $existingUserResult = $this->readUser($requestUser);

        if (!$existingUserResult->isSuccess()) {
            // ERROR
            return $existingUserResult;
        }

        $responseUser = new User();

        $responseUser->populate($existingUserResult->getUser());

        $requestUser = new ReadOnlyUser($requestUser);
        /* </LOW_LEVEL_PREP> */

        /* @event beforeDeleteUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_BEFORE_DELETE_USER,
            $this,
            [
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            return $results->last();
        }

        /* @event deleteUser */
        $results = $this->getEventManager()->trigger(
            self::EVENT_DELETE_USER,
            $this,
            [
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ],
            function ($result) {
                return !$result->isSuccess();
            }
        );

        if ($results->stopped()) {
            $result = $results->last();
            $this->getEventManager()->trigger(
                self::EVENT_DELETE_USER_FAIL,
                $this,
                [
                    'result' => $result,
                    'requestUser' => $requestUser,
                    'responseUser' => $responseUser
                ]
            );

            return $result;
        }

        $result = new Result($responseUser);

        /* @event deleteUserSuccess */
        $this->getEventManager()->trigger(
            self::EVENT_DELETE_USER_SUCCESS,
            $this,
            [
                'result' => $result,
                'requestUser' => $requestUser,
                'responseUser' => $responseUser
            ]
        );

        return $result;
    }
}
