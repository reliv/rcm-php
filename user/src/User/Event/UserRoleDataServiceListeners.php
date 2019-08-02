<?php

namespace RcmUser\User\Event;

use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\UserRoleProperty;
use RcmUser\User\Result;
use RcmUser\User\Service\UserDataService;
use RcmUser\User\Service\UserRoleService;
use Zend\EventManager\Event;

/**
 * Class UserRoleDataServiceListeners
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserRoleDataServiceListeners extends AbstractUserDataServiceListeners
{
    /**
     * @var int $priority
     */
    protected $priority = 1;
    /**
     * @var array $listenerMethods
     */
    protected $listenerMethods
        = [
            'onGetAllUsersSuccess' => UserDataService::EVENT_GET_ALL_USERS_SUCCESS, //'getAllUsersSuccess',
            'onBuildUser' => UserDataService::EVENT_BUILD_USER, //'buildUser',
            //'onBeforeCreateUser' => UserDataService::EVENT_BEFORE_CREATE_USER, //'beforeCreateUser',
            'onCreateUserSuccess' => UserDataService::EVENT_CREATE_USER_SUCCESS, //'createUserSuccess',
            'onReadUserSuccess' => UserDataService::EVENT_READ_USER_SUCCESS, //'readUserSuccess',
            //'onBeforeUpdateUser' => UserDataService::EVENT_BEFORE_UPDATE_USER, //'beforeUpdateUser',
            'onUpdateUserSuccess' => UserDataService::EVENT_UPDATE_USER_SUCCESS, //'updateUserSuccess',
            'onDeleteUserSuccess' => UserDataService::EVENT_DELETE_USER_SUCCESS, //'deleteUserSuccess',
        ];

    /**
     * @var UserRoleService $userRoleService
     */
    protected $userRoleService;

    /**
     * setUserRoleService
     *
     * @param UserRoleService $userRoleService userRoleService
     *
     * @return void
     */
    public function setUserRoleService(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }

    /**
     * getUserRoleService
     *
     * @return UserRoleService
     */
    public function getUserRoleService()
    {
        return $this->userRoleService;
    }

    /**
     * getUserPropertyKey
     *
     * @return string
     */
    public function getUserPropertyKey()
    {
        return UserRoleProperty::PROPERTY_KEY;
    }

    /**
     * onGetAllUsersSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onGetAllUsersSuccess($e)
    {
        $result = $e->getParam('result');

        if (!$result->isSuccess()) {
            return $result;
        }

        $users = $result->getData();

        foreach ($users as &$user) {
            $readResult = $this->getUserRoleService()->readRoles($user);

            if ($readResult->isSuccess()) {
                $roles = $readResult->getData();
            } else {
                $result->setMessage(
                    'Could not read user roles: ' . $readResult->getMessage()
                );

                $roles = [];
            }

            $userRoleProperty = $this->buildValidUserRoleProperty(
                $user,
                $roles
            );

            $user->setProperty(
                $this->getUserPropertyKey(),
                $userRoleProperty
            );
        }

        $result->setData($users);

        return $result;
    }

    /**
     * onBuildUser
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onBuildUser($e)
    {
        // $requestUser = $e->getParam('requestUser');
        /** @var UserInterface $responseUser */
        $responseUser = $e->getParam('responseUser');

        $userRoleProperty = $responseUser->getProperty(
            $this->getUserPropertyKey(),
            null
        );

        if (!$userRoleProperty instanceof UserRoleProperty) {
            $userRoleProperty = $this->buildValidUserRoleProperty(
                $responseUser,
                []
            );
        }

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        return new Result($responseUser);
    }

    /**
     * onBeforeCreateUser
     *
     * @param Event $e e
     *
     * @return Result
    public function onBeforeCreateUser($e)
     * {
     * $requestUser = $e->getParam('requestUser');
     * $responseUser = $e->getParam('responseUser');
     *
     * // VALIDATE //
     * $aclRoles = $responseUser->getProperty(
     * $this->getUserPropertyKey()
     * );
     *
     * if (!empty($aclRoles)) {
     *
     * // @todo Validation logic here
     * // make sure the role sent in is valid
     * }
     *
     * return new Result($responseUser);
     * }
     */

    /**
     * onCreateUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onCreateUserSuccess($e)
    {
        /** @var Result $result */
        $result = $e->getParam('result');

        if (!$result->isSuccess()) {
            return $result;
        }

        $responseUser = $result->getUser();

        /** @var $userRoleProperty \RcmUser\User\Entity\UserRoleProperty */
        $userRoleProperty = $responseUser->getProperty(
            $this->getUserPropertyKey(),
            new UserRoleProperty([])
        );

        $userRoleProperty = $this->buildValidUserRoleProperty(
            $responseUser,
            $userRoleProperty->getRoles()
        );

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        $roles = $userRoleProperty->getRoles();

        $saveRoles = $this->removeDefaultUserRoleIds($roles);

        $createResult = $this->getUserRoleService()->createRoles(
            $responseUser,
            $saveRoles
        );

        if (!$createResult->isSuccess()) {
            return new Result(
                $responseUser,
                Result::CODE_FAIL,
                $createResult->getMessages()
            );
        }

        return new Result($responseUser, Result::CODE_SUCCESS);
    }

    /**
     * onReadUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onReadUserSuccess($e)
    {
        /** @var Result $result */
        $result = $e->getParam('result');

        if (!$result->isSuccess()) {
            return $result;
        }

        $responseUser = $result->getUser();

        $readResult = $this->getUserRoleService()->readRoles($responseUser);

        if (!$readResult->isSuccess()) {
            return new Result(
                $responseUser,
                Result::CODE_FAIL,
                $readResult->getMessages()
            );
        }

        $roles = $readResult->getData();

        $userRoleProperty = $this->buildValidUserRoleProperty(
            $responseUser,
            $roles
        );

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        return new Result($responseUser, Result::CODE_SUCCESS);
    }

    /**
     * onBeforeUpdateUser
     *
     * @param Event $e e
     *
     * @return Result

    public function onBeforeUpdateUser($e)
     * {
     * $requestUser = $e->getParam('requestUser');
     * $responseUser = $e->getParam('responseUser');
     * $existingUser = $e->getParam('existingUser');
     *
     * // VALIDATE //
     * $userRoleProperty = $responseUser->getProperty(
     * $this->getUserPropertyKey(),
     * new UserRoleProperty(array())
     * );
     *
     * $aclRoles = $userRoleProperty->getRoles();
     *
     * if (!empty($aclRoles)) {
     *
     * // @todo Validation logic here
     * // make sure the role sent in is valid
     * }
     *
     * return new Result($responseUser);
     * }
     */

    /**
     * onUpdateUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onUpdateUserSuccess($e)
    {
        /** @var Result $result */
        $result = $e->getParam('result');
        /** @var UserInterface $requestUser */
        $requestUser = $e->getParam('requestUser');

        if (!$result->isSuccess()) {
            return $result;
        }

        $responseUser = $result->getUser();

        $requestRoles = $requestUser->getProperty(
            $this->getUserPropertyKey()
        );

        // No roles requested
        if ($requestRoles === null) {
            return $result;
        }

        /** @var $userRoleProperty \RcmUser\User\Entity\UserRoleProperty */
        $userRoleProperty = $requestUser->getProperty(
            $this->getUserPropertyKey(),
            new UserRoleProperty([])
        );

        $userRoleProperty = $this->buildValidUserRoleProperty(
            $responseUser,
            $userRoleProperty->getRoles()
        );

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        $roles = $userRoleProperty->getRoles();

        $saveRoles = $this->removeDefaultUserRoleIds($roles);

        // do update
        $updateResult = $this->getUserRoleService()->updateRoles(
            $responseUser,
            $saveRoles
        );

        if (!$updateResult->isSuccess()) {
            return new Result(
                $responseUser,
                Result::CODE_FAIL,
                $updateResult->getMessages()
            );
        }

        return new Result($responseUser, Result::CODE_SUCCESS);
    }

    /**
     * onDeleteUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onDeleteUserSuccess($e)
    {
        /** @var Result $result */
        $result = $e->getParam('result');

        if (!$result->isSuccess()) {
            return $result;
        }

        $responseUser = $result->getUser();

        /** @var $userRoleProperty \RcmUser\User\Entity\UserRoleProperty */
        $userRoleProperty = $responseUser->getProperty(
            $this->getUserPropertyKey(),
            $this->buildUserRoleProperty([])
        );

        $deleteResult = $this->getUserRoleService()->deleteRoles(
            $responseUser,
            $userRoleProperty->getRoles()
        );

        if (!$deleteResult->isSuccess()) {
            return new Result(
                $responseUser,
                Result::CODE_FAIL,
                $deleteResult->getMessages()
            );
        }

        $userRoleProperty->setRoles([]);

        $responseUser->setProperty(
            $this->getUserPropertyKey(),
            $userRoleProperty
        );

        return new Result($responseUser, Result::CODE_SUCCESS);
    }

    /**
     * removeDefaultUserRoleIds
     *
     * @param array $roles roles
     *
     * @return array
     */
    public function removeDefaultUserRoleIds($roles = [])
    {
        $defaultRolesResult = $this->getUserRoleService()->getDefaultUserRoleIds();

        $defaultRoles = $defaultRolesResult->getData();

        return array_diff(
            $roles,
            $defaultRoles
        );
    }

    /**
     * buildUserRoleProperty
     *
     * @param array $roles roles
     *
     * @return UserRoleProperty
     */
    public function buildUserRoleProperty($roles = [])
    {
        return $this->getUserRoleService()->buildUserRoleProperty($roles);
    }

    /**
     * buildValidUserRoleProperty
     *
     * @param UserInterface $user  user
     * @param array         $roles roles
     *
     * @return UserRoleProperty
     */
    public function buildValidUserRoleProperty(
        UserInterface $user,
        $roles = []
    ) {
        return $this->getUserRoleService()->buildValidUserRoleProperty(
            $user,
            $roles
        );
    }

    /**
     * buildValidRoles
     *
     * @param UserInterface $user  user
     * @param array         $roles roles
     *
     * @return array
     */
    public function buildValidRoles(
        UserInterface $user,
        $roles = []
    ) {
        return $this->getUserRoleService()->buildValidRoles(
            $user,
            $roles
        );
    }
}
