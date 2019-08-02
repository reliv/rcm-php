<?php

namespace RcmUser\User\Event;

use RcmUser\User\Db\UserDataMapperInterface;
use RcmUser\User\Service\UserDataService;
use Zend\EventManager\Event;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Class UserDataServiceListeners
 *
 * UserDataServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserDataServiceListeners extends AbstractUserDataServiceListeners implements ListenerAggregateInterface
{
    /**
     * @var int
     */
    protected $priority = -1;
    /**
     * @var array
     */
    protected $listenerMethods
        = [
            'onGetAllUsers' => UserDataService::EVENT_GET_ALL_USERS, //'getAllUsers',
            'onCreateUser' => UserDataService::EVENT_CREATE_USER, //'createUser',
            'onReadUser' => UserDataService::EVENT_READ_USER, //'readUser',
            'onUpdateUser' => UserDataService::EVENT_UPDATE_USER, //'updateUser',
            'onDeleteUser' => UserDataService::EVENT_DELETE_USER, //'deleteUser',
        ];

    /**
     * @var UserDataMapperInterface
     */
    protected $userDataMapper;

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
     * onGetAllUsers
     *
     * @param Event $e e
     *
     * @return \RcmUser\User\Result
     */
    public function onGetAllUsers($e)
    {
        $options = $e->getParam('options');

        return $this->getUserDataMapper()->fetchAll($options);
    }

    /**
     * onCreateUser
     *
     * @param Event $e e
     *
     * @return \RcmUser\User\Result
     */
    public function onCreateUser($e)
    {
        $requestUser = $e->getParam('requestUser');
        $responseUser = $e->getParam('responseUser');

        $result = $this->getUserDataMapper()->create(
            $requestUser,
            $responseUser
        );

        if ($result->isSuccess()) {
            // @todo may not be required if can assign (by reference)
            $responseUser->populate($result->getUser());
        }

        return $result;
    }

    /**
     * onCreateUserFail
     *
     * @param Event $e e
     *
     * @return \RcmUser\User\Result|void

    public function onCreateUserFail($e)
     * {
     * //@todo do delete?
     * }
     */

    /**
     * onReadUser
     *
     * @param Event $e e
     *
     * @return \RcmUser\User\Result
     */
    public function onReadUser($e)
    {
        //$target = $e->getTarget();
        $requestUser = $e->getParam('requestUser');
        $responseUser = $e->getParam('responseUser');

        $result = $this->getUserDataMapper()->read(
            $requestUser,
            $responseUser
        );

        if ($result->isSuccess()) {
            // @todo may not be required if can assign (by reference)
            $responseUser->populate($result->getUser());
        }

        return $result;
    }

    /**
     * onUpdateUser
     *
     * @param Event $e e
     *
     * @return \RcmUser\User\Result
     */
    public function onUpdateUser($e)
    {
        //$target = $e->getTarget();
        $requestUser = $e->getParam('requestUser');
        $responseUser = $e->getParam('responseUser');
        $existingUser = $e->getParam('existingUser');

        $result = $this->getUserDataMapper()->update(
            $requestUser,
            $responseUser,
            $existingUser
        );

        if ($result->isSuccess()) {
            // @todo may not be required if can assign (by reference)
            $responseUser->populate($result->getUser());
        }

        return $result;
    }

    /**
     * onUpdateUserFail
     *
     * @param Event $e e
     *
     * @return \RcmUser\User\Result|void

    public function onUpdateUserFail($e)
     * {
     * // @todo revert?
     * }
     */

    /**
     * onDeleteUser
     *
     * @param Event $e e
     *
     * @return \RcmUser\User\Result
     */
    public function onDeleteUser($e)
    {
        //$target = $e->getTarget();
        $requestUser = $e->getParam('requestUser');
        $responseUser = $e->getParam('responseUser');

        $result = $this->getUserDataMapper()->delete(
            $requestUser,
            $responseUser
        );

        if ($result->isSuccess()) {
            // @todo may not be required if can assign (by reference)
            $responseUser->populate($result->getUser());
        }

        return $result;
    }

    /**
     * onDeleteUserFail
     *
     * @param Event $e e
     *
     * @return \RcmUser\User\Result|void

    public function onDeleteUserFail($e)
     * {
     * // @todo restore?
     * }
     */
}
