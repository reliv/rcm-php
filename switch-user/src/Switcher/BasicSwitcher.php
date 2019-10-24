<?php

namespace Rcm\SwitchUser\Switcher;

use Rcm\SwitchUser\Model\SuProperty;
use Rcm\SwitchUser\Result;
use Rcm\SwitchUser\Service\SwitchUserLogService;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\Authentication\SetIdentityInsecure;
use RcmUser\Api\GetPsrRequest;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BasicSwitcher extends SwitcherAbstract implements Switcher
{
    /**
     * @var string
     */
    protected $name = 'basic';

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
        $psrRequest = GetPsrRequest::invoke();

        // Get current user
        $currentUser = $this->getIdentity->__invoke($psrRequest);

        $currentUserId = $currentUser->getId();

        $impersonatorUserId = $impersonatorUser->getId();

        $result = new Result();

        // Force login as $suUser
        $this->setIdentity->__invoke($psrRequest, $impersonatorUser);

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
