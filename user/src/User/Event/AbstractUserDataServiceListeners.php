<?php

namespace RcmUser\User\Event;

use RcmUser\Event\UserEventManager;
use RcmUser\User\Result;
use RcmUser\User\Service\UserDataService;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Class AbstractUserDataServiceListeners
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AbstractUserDataServiceListeners implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = [];

    /**
     * @var string
     */
    protected $id = UserDataService::EVENT_IDENTIFIER;

    /**
     * @var int
     */
    protected $priority = -1;

    /**
     * @var array
     */
    protected $listenerMethods
        = [
            'onBeforeGetAllUsers' => UserDataService::EVENT_BEFORE_GET_ALL_USERS, //'beforeGetAllUsers',
            'onGetAllUsers' => UserDataService::EVENT_GET_ALL_USERS, //'getAllUsers',
            'onGetAllUsersFail' => UserDataService::EVENT_GET_ALL_USERS_FAIL, //'getAllUsersFail',
            'onGetAllUsersSuccess' => UserDataService::EVENT_GET_ALL_USERS_SUCCESS, //'getAllUsersSuccess',
            'onBuildUser' => UserDataService::EVENT_BUILD_USER, //'buildUser',
            'onBeforeCreateUser' => UserDataService::EVENT_BEFORE_CREATE_USER, //'beforeCreateUser',
            'onCreateUser' => UserDataService::EVENT_CREATE_USER, //'createUser',
            'onCreateUserFail' => UserDataService::EVENT_CREATE_USER_FAIL, //'createUserFail',
            'onCreateUserSuccess' => UserDataService::EVENT_CREATE_USER_SUCCESS, //'createUserSuccess',
            'onBeforeReadUser' => UserDataService::EVENT_BEFORE_READ_USER, //'beforeReadUser',
            'onReadUser' => UserDataService::EVENT_READ_USER, //'readUser',
            'onReadUserFail' => UserDataService::EVENT_READ_USER_FAIL, //'readUserFail',
            'onReadUserSuccess' => UserDataService::EVENT_READ_USER_SUCCESS, //'readUserSuccess',
            'onBeforeUpdateUser' => UserDataService::EVENT_BEFORE_UPDATE_USER, //'beforeUpdateUser',
            'onUpdateUser' => UserDataService::EVENT_UPDATE_USER, //'updateUser',
            'onUpdateUserFail' => UserDataService::EVENT_UPDATE_USER_FAIL, //'updateUserFail',
            'onUpdateUserSuccess' => UserDataService::EVENT_UPDATE_USER_SUCCESS, //'updateUserSuccess',
            'onBeforeDeleteUser' => UserDataService::EVENT_BEFORE_DELETE_USER, //'beforeDeleteUser',
            'onDeleteUser' => UserDataService::EVENT_DELETE_USER, //'deleteUser',
            'onDeleteUserFail' => UserDataService::EVENT_DELETE_USER_FAIL, //'deleteUserFail',
            'onDeleteUserSuccess' => UserDataService::EVENT_DELETE_USER_SUCCESS, //'deleteUserSuccess',
        ];

    /**
     * attach
     *
     * @param UserEventManager|EventManagerInterface $userEventManager events
     *
     * @return void
     */
    public function attach(EventManagerInterface $userEventManager)
    {
        // @todo Do we need shared events here?
        $sharedEvents = $userEventManager->getSharedManager();

        foreach ($this->listenerMethods as $method => $event) {
            $this->listeners[] = $sharedEvents->attach(
                $this->id,
                $event,
                [
                    $this,
                    $method
                ],
                $this->priority
            );
        }
    }

    /**
     * detach
     *
     * @param EventManagerInterface $userEventManager events
     *
     * @return void
     */
    public function detach(EventManagerInterface $userEventManager)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($userEventManager->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * onBeforeGetAllUsers
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onBeforeGetAllUsers($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onGetAllUsers
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onGetAllUsers($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onGetAllUsersFail
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onGetAllUsersFail($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
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
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
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
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onBeforeCreate
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onBeforeCreateUser($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onCreate
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onCreateUser($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onCreateUserFail
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onCreateUserFail($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onCreateUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onCreateUserSuccess($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onBeforeReadUser
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onBeforeReadUser($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onReadUser
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onReadUser($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onReadUserFail
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onReadUserFail($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
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
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onBeforeUpdateUser
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onBeforeUpdateUser($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onUpdateUser
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onUpdateUser($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onUpdateUserFail
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onUpdateUserFail($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onUpdateUserSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onUpdateUserSuccess($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onBeforeDeleteUser
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onBeforeDeleteUser($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onDeleteUser
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onDeleteUser($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }

    /**
     * onDeleteUserFail
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onDeleteUserFail($e)
    {
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
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
        return new Result(
            null,
            Result::CODE_FAIL,
            'Listener (' . __METHOD__ . ') not defined.'
        );
    }
}
