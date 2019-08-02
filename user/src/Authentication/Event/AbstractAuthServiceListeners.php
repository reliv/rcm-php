<?php

namespace RcmUser\Authentication\Event;

use RcmUser\Authentication\Service\AuthenticationService;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserInterface;
use Zend\Authentication\Result;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Class AbstractAuthServiceListeners
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AbstractAuthServiceListeners implements ListenerAggregateInterface
{
    /**
     * @var array $listeners
     */
    protected $listeners = [];
    /**
     * @var string $id
     */
    protected $id = UserAuthenticationService::EVENT_IDENTIFIER;

    /**
     * @var int $priority
     */
    protected $priority = 1;

    /**
     * @var AuthenticationService $authService
     */
    protected $authService;

    /**
     * @var array $listenerMethods
     */
    protected $listenerMethods
        = [
            //'validateCredentials',
            'onValidateCredentials' => UserAuthenticationService::EVENT_VALIDATE_CREDENTIALS,
            //'validateCredentialsSuccess',
            'onValidateCredentialsSuccess' => UserAuthenticationService::EVENT_VALIDATE_CREDENTIALS_SUCCESS,
            //'validateCredentialsFail',
            'onValidateCredentialsFail' => UserAuthenticationService::EVENT_VALIDATE_CREDENTIALS_SUCCESS,
            //'authenticate',
            'onAuthenticate' => UserAuthenticationService::EVENT_AUTHENTICATE,
            // 'authenticateSuccess',
            'onAuthenticateSuccess' => UserAuthenticationService::EVENT_AUTHENTICATE_SUCCESS,
            //'authenticateFail',
            'onAuthenticateFail' => UserAuthenticationService::EVENT_AUTHENTICATE_FAIL,
            //'clearIdentity',
            'onClearIdentity' => UserAuthenticationService::EVENT_CLEAR_IDENTITY,
            //'hasIdentity',
            'onHasIdentity' => UserAuthenticationService::EVENT_HAS_IDENTITY,
            //'setIdentity',
            'onSetIdentity' => UserAuthenticationService::EVENT_SET_IDENTITY,
            //'getIdentity',
            'onGetIdentity' => UserAuthenticationService::EVENT_GET_IDENTITY,
        ];

    /**
     * setAuthService
     *
     * @param AuthenticationService $authService authService
     *
     * @return void
     */
    public function setAuthService(
        AuthenticationService $authService
    ) {
        $this->authService = $authService;
    }

    /**
     * getAuthService
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * attach
     *
     * @param EventManagerInterface $events events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

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
     * @param EventManagerInterface $events events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * onValidateCredentials
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onValidateCredentials($e)
    {
        return new Result(
            null,
            Result::FAILURE_UNCATEGORIZED,
            [
                'Listener (' . __METHOD__ . ') not defined.'
            ]
        );
    }

    /**
     * onValidateCredentialsSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onValidateCredentialsSuccess($e)
    {
        return new Result(
            null,
            Result::FAILURE_UNCATEGORIZED,
            [
                'Listener (' . __METHOD__ . ') not defined.'
            ]
        );
    }

    /**
     * onValidateCredentialsFail
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onValidateCredentialsFail($e)
    {
        return new Result(
            null,
            Result::FAILURE_UNCATEGORIZED,
            [
                'Listener (' . __METHOD__ . ') not defined.'
            ]
        );
    }

    /**
     * onAuthenticate
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onAuthenticate($e)
    {
        return new Result(
            null,
            Result::FAILURE_UNCATEGORIZED,
            [
                'Listener (' . __METHOD__ . ') not defined.'
            ]
        );
    }

    /**
     * onAuthenticateSuccess
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onAuthenticateSuccess($e)
    {
        return new Result(
            null,
            Result::FAILURE_UNCATEGORIZED,
            [
                'Listener (' . __METHOD__ . ') not defined.'
            ]
        );
    }

    /**
     * onAuthenticateFail
     *
     * @param Event $e e
     *
     * @return Result
     */
    public function onAuthenticateFail($e)
    {
        return new Result(
            null,
            Result::FAILURE_UNCATEGORIZED,
            [
                'Listener (' . __METHOD__ . ') not defined.'
            ]
        );
    }

    /**
     * onClearIdentity
     *
     * @param Event $e e
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function onClearIdentity($e)
    {
        throw new RcmUserException('Listener (' . __METHOD__ . ') not defined.');
    }

    /**
     * onHasIdentity
     *
     * @param Event $e e
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function onHasIdentity($e)
    {
        throw new RcmUserException('Listener (' . __METHOD__ . ') not defined.');
    }

    /**
     * onSetIdentity
     *
     * @param Event $e e
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function onSetIdentity($e)
    {
        throw new RcmUserException('Listener (' . __METHOD__ . ') not defined.');
    }

    /**
     * onGetIdentity
     *
     * @param Event $e e
     *
     * @return UserInterface|null
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function onGetIdentity($e)
    {
        throw new RcmUserException('Listener (' . __METHOD__ . ') not defined.');
    }
}
