<?php

namespace Rcm\SwitchUser\Switcher;

use Rcm\SwitchUser\Result;
use Rcm\SwitchUser\Service\SwitchUserLogService;
use RcmUser\Api\Authentication\Authenticate;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\Authentication\SetIdentity;
use RcmUser\Api\GetPsrRequest;
use RcmUser\User\Entity\UserInterface;

/**
 * More secure way to switch user back
 *
 * @author James Jervis - https://github.com/jerv13
 */
class AuthSwitcher extends SwitcherAbstract implements Switcher
{
    /**
     * @var string
     */
    protected $name = 'auth';

    /**
     * @var Authenticate
     */
    protected $authenticate;

    /**
     * @param SetIdentity          $setIdentity
     * @param GetIdentity          $getIdentity
     * @param SwitchUserLogService $switchUserLogService
     * @param Authenticate         $authenticate
     */
    public function __construct(
        SetIdentity $setIdentity,
        GetIdentity $getIdentity,
        SwitchUserLogService $switchUserLogService,
        Authenticate $authenticate
    ) {
        $this->authenticate = $authenticate;
        parent::__construct($setIdentity, $getIdentity, $switchUserLogService);
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * switchBack
     *
     * @param UserInterface $impersonatorUser
     * @param array         $options
     *
     * @return Result
     * @throws \Exception
     */
    public function switchBack(UserInterface $impersonatorUser, $options = [])
    {
        if (!isset($options['suUserPassword'])) {
            throw new \Exception('suUserPassword required for AuthSwitcher');
        }
        $suUserPassword = $options['suUserPassword'];

        $psrRequest = GetPsrRequest::invoke();

        // Get current user
        $currentUser = $this->getIdentity->__invoke($psrRequest);

        $currentUserId = $currentUser->getId();

        $impersonatorUserId = $impersonatorUser->getId();

        $result = new Result();

        $impersonatorUser->setPassword($suUserPassword);
        $authResult = $this->authenticate->__invoke($psrRequest, $impersonatorUser);

        if (!$authResult->isValid()) {
            // ERROR
            // log action
            $this->logAction(
                $impersonatorUserId,
                $currentUserId,
                'SU attempted to switched back, provided incorrect credentials',
                true
            );

            $result->setSuccess(false, $authResult->getMessages()[0]);

            return $result;
        }

        // log action
        $this->logAction(
            $impersonatorUserId,
            $currentUserId,
            'SU switched back',
            true
        );

        $result->setSuccess(true, 'SU switch back was successful');

        return $result;
    }
}
