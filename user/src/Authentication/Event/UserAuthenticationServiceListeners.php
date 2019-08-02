<?php

namespace RcmUser\Authentication\Event;

use RcmUser\Authentication\Adapter\Adapter;
use RcmUser\Authentication\Adapter\UserAdapter;
use RcmUser\Authentication\Exception\AuthenticationException;
use RcmUser\Authentication\Service\AuthenticationService;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\User\Entity\UserInterface;
use Zend\Authentication\Result;
use Zend\EventManager\Event;

/**
 * UserAuthenticationServiceListeners
 *
 * UserAuthenticationServiceListeners
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserAuthenticationServiceListeners extends AbstractAuthServiceListeners
{
    /**
     * @var string
     */
    protected $id = UserAuthenticationService::EVENT_IDENTIFIER;

    /**
     * @var int
     */
    protected $priority = 1;

    /**
     * @var array
     */
    protected $listenerMethods
        = [
            //'validateCredentials',
            'onValidateCredentials' => UserAuthenticationService::EVENT_VALIDATE_CREDENTIALS,
            //'validateCredentialsSuccess',
            //'onValidateCredentialsSuccess' => UserAuthenticationService::EVENT_VALIDATE_CREDENTIALS_SUCCESS,
            //'validateCredentialsFail',
            //'onValidateCredentialsFail' => UserAuthenticationService::EVENT_VALIDATE_CREDENTIALS_SUCCESS,
            //'authenticate',
            'onAuthenticate' => UserAuthenticationService::EVENT_AUTHENTICATE,
            // 'authenticateSuccess',
            //'onAuthenticateSuccess' => UserAuthenticationService::EVENT_AUTHENTICATE_SUCCESS,
            //'authenticateFail',
            //'onAuthenticateFail' => UserAuthenticationService::EVENT_AUTHENTICATE_FAIL,
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
     * Constructor.
     *
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        AuthenticationService $authenticationService
    ) {
        $this->setAuthService($authenticationService);
    }

    /**
     * assertValidAdapter
     *
     * @param $adapter
     *
     * @return void
     * @throws AuthenticationException
     */
    protected function assertValidAdapter($adapter)
    {
        if (empty($adapter)) {
            throw new AuthenticationException('No adapter set');
        }

        if (!is_object($adapter)) {
            throw new AuthenticationException('Adapter must object of type: ' . Adapter::class);
        }

        if (!$adapter instanceof Adapter) {
            throw new AuthenticationException(
                'Adapter must object of type: ' . Adapter::class . ' got ' . get_class($adapter)
            );
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
        $user = $e->getParam('user');

        /** @var Adapter|UserAdapter $adapter */
        $adapter = $this->getAuthService()->getAdapter();
        $this->assertValidAdapter($adapter);
        $adapter = $adapter->withUser($user);

        $result = $adapter->authenticate();

        return $result;
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
        $user = $e->getParam('user');

        /** @var Adapter $adapter */
        $adapter = $this->getAuthService()->getAdapter();
        $this->assertValidAdapter($adapter);
        $adapter = $adapter->withUser($user);
        $result = $this->getAuthService()->authenticate($adapter);

        return $result;
    }

    /**
     * onClearIdentity
     *
     * @param Event $e e
     *
     * @return void
     */
    public function onClearIdentity($e)
    {
        $authService = $this->getAuthService();

        if ($authService->hasIdentity()) {
            $authService->clearIdentity();
        }
    }

    /**
     * onHasIdentity
     *
     * @param Event $e e
     *
     * @return bool|Result
     */
    public function onHasIdentity($e)
    {
        $authService = $this->getAuthService();

        return $authService->hasIdentity();
    }

    /**
     * onSetIdentity
     *
     * @param Event $e e
     *
     * @return void
     */
    public function onSetIdentity($e)
    {
        $identity = $e->getParam('identity');

        $authService = $this->getAuthService();

        $authService->setIdentity($identity);
    }

    /**
     * onGetIdentity
     *
     * @param Event $e e
     *
     * @return UserInterface|null
     */
    public function onGetIdentity($e)
    {
        $authService = $this->getAuthService();

        if ($authService->hasIdentity()) {
            return $authService->getIdentity();
        }

        return null;
    }
}
