<?php

namespace RcmUser\Authentication\Service;

use RcmUser\Event\EventProvider;
use RcmUser\User\Entity\UserInterface;
use Zend\Authentication\Result;

/**
 * UserAuthenticationService
 *
 * AUTHENTICATION events which trigger the listeners which do the actual work
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Authentication\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserAuthenticationService extends EventProvider
{
    const EVENT_IDENTIFIER = UserAuthenticationService::class;

    const EVENT_VALIDATE_CREDENTIALS = 'validateCredentials';
    const EVENT_VALIDATE_CREDENTIALS_SUCCESS = 'validateCredentialsSuccess';
    const EVENT_VALIDATE_CREDENTIALS_FAIL = 'validateCredentialsFail';

    const EVENT_AUTHENTICATE = 'authenticate';
    const EVENT_AUTHENTICATE_SUCCESS = 'authenticateSuccess';
    const EVENT_AUTHENTICATE_FAIL = 'authenticateFail';

    const EVENT_CLEAR_IDENTITY = 'clearIdentity';
    const EVENT_HAS_IDENTITY = 'hasIdentity';
    const EVENT_SET_IDENTITY = 'setIdentity';
    const EVENT_GET_IDENTITY = 'getIdentity';

    /**
     * @var bool
     * Force returned user to hide password,
     * can cause issues is return object is meant to be saved.
     */
    protected $obfuscatePassword = true;

    /**
     * setObfuscatePassword
     *
     * @param bool $obfuscatePassword obfuscatePassword
     *
     * @return void
     */
    public function setObfuscatePassword($obfuscatePassword)
    {
        $this->obfuscatePassword = $obfuscatePassword;
    }

    /**
     * getObfuscatePassword
     *
     * @return bool
     */
    public function getObfuscatePassword()
    {
        return $this->obfuscatePassword;
    }

    /**
     * validateCredentials
     *
     * @param UserInterface $user user
     *
     * @return mixed
     * @throws \Exception
     */
    public function validateCredentials(UserInterface $user)
    {
        /* + LOW_LEVEL_PREP */
        if (!$user->isEnabled()) {
            return new Result(Result::FAILURE_UNCATEGORIZED, $user, ['User is disabled.']);
        }
        /* - LOW_LEVEL_PREP */

        /* @event validateCredentials
         * - expects listener to return
         * Zend\Authentication\Result with
         * Result->identity == to user object ($user)
         */
        $results = $this->getEventManager()->trigger(
            self::EVENT_VALIDATE_CREDENTIALS,
            $this,
            ['user' => $user],
            function ($result) {
                return $result->isValid();
            }
        );

        $result = $results->last();

        if ($result === null) {
            throw new \Exception('No auth listener registered or no results returned (validateCredentials).');
        }

        if ($results->stopped()) {
            /*
             * @event validateCredentialsSuccess
             */
            $this->getEventManager()->trigger(
                self::EVENT_VALIDATE_CREDENTIALS_SUCCESS,
                $this,
                [
                    'result' => $result,
                    'user' => $user
                ]
            );

            return new Result(
                Result::SUCCESS,
                $this->prepareUser(
                    $result->getIdentity()
                )
            );
        }

        /*
         * @event validateCredentialsFail
         */
        $this->getEventManager()->trigger(
            self::EVENT_VALIDATE_CREDENTIALS_FAIL,
            $this,
            [
                'result' => $result,
                'user' => $user,
            ]
        );

        return $result;
    }

    /**
     * authenticate
     *
     * @param UserInterface $user user
     *
     * @return mixed
     * @throws \Exception
     */
    public function authenticate(UserInterface $user)
    {
        /* @todo This check might go somewhere else */
        if (!$user->isEnabled()) {
            $result = new Result(
                Result::FAILURE_UNCATEGORIZED,
                $user,
                ['User is disabled.']
            );

            $this->getEventManager()->trigger(
                self::EVENT_AUTHENTICATE_FAIL,
                $this,
                [
                    'result' => $result,
                    'user' => $user
                ]
            );

            return $result;
        }

        /* @event authenticate
         * - expects listener to return
         * Zend\Authentication\Result with
         * Result->identity == to user object ($user)
         */
        $results = $this->getEventManager()->trigger(
            self::EVENT_AUTHENTICATE,
            $this,
            ['user' => $user],
            function ($result) {
                return $result->isValid();
            }
        );

        $result = $results->last();

        if ($result === null) {
            throw new \Exception('No auth listener registered or no results returned (authenticate).');
        }

        if ($results->stopped()) {
            /*
             * @event authenticateSuccess
             */
            $this->getEventManager()->trigger(
                self::EVENT_AUTHENTICATE_SUCCESS,
                $this,
                [
                    'result' => $result,
                    'user' => $user
                ]
            );

            return new Result(
                Result::SUCCESS,
                $this->prepareUser(
                    $result->getIdentity()
                )
            );
        }

        /*
         * @event authenticateFail
         */
        $this->getEventManager()->trigger(
            self::EVENT_AUTHENTICATE_FAIL,
            $this,
            [
                'result' => $result,
                'user' => $user
            ]
        );

        return $result;
    }

    /**
     * clearIdentity
     *
     * @return void
     */
    public function clearIdentity()
    {
        /*
         * @event clearIdentity
         */
        $this->getEventManager()->trigger(
            self::EVENT_CLEAR_IDENTITY,
            $this,
            []
        );
    }

    /**
     * hasIdentity
     *
     * @return bool
     */
    public function hasIdentity()
    {
        $results = $this->getEventManager()->trigger(
            self::EVENT_HAS_IDENTITY,
            $this,
            [],
            function ($hasIdentity) {
                return $hasIdentity;
            }
        );

        $hasIdentity = $results->last();

        return $hasIdentity;
    }

    /**
     * setIdentity - used to refresh session user
     *
     * @param UserInterface $identity identity
     *
     * @return void
     */
    public function setIdentity(UserInterface $identity)
    {
        /*
         * @event setIdentity
         */
        $this->getEventManager()->trigger(
            self::EVENT_SET_IDENTITY,
            $this,
            ['identity' => $identity]
        );
    }

    /**
     * getIdentity
     *
     * @param null $default default is no identity
     *
     * @return UserInterface|null
     */
    public function getIdentity($default = null)
    {
        /*
         * @event getIdentity
         */
        $results = $this->getEventManager()->trigger(
            self::EVENT_GET_IDENTITY,
            $this,
            []
        );

        $user = $results->last();

        if (empty($user)) {
            return $default;
        }

        return $this->prepareUser($user);
    }

    /**
     * prepareUser
     *
     * @param UserInterface $user user
     *
     * @return UserInterface
     */
    public function prepareUser(UserInterface $user)
    {
        if ($this->getObfuscatePassword()) {
            $user->setPassword(UserInterface::PASSWORD_OBFUSCATE);
        }

        return $user;
    }
}
