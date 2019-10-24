<?php

namespace Rcm\SwitchUser\Switcher;

use Rcm\Api\GetPsrRequest;
use Rcm\SwitchUser\Model\SuProperty;
use Rcm\SwitchUser\Result;
use Rcm\SwitchUser\Service\SwitchUserLogService;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\Authentication\SetIdentity;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class SwitcherAbstract
{
    /**
     * @var SetIdentity
     */
    protected $setIdentity;

    /**
     * @var GetIdentity
     */
    protected $getIdentity;

    /**
     * @var SwitchUserLogService
     */
    protected $switchUserLogService;

    /**
     * @param SetIdentity  $setIdentity
     * @param GetIdentity          $getIdentity
     * @param SwitchUserLogService $switchUserLogService
     */
    public function __construct(
        SetIdentity $setIdentity,
        GetIdentity $getIdentity,
        SwitchUserLogService $switchUserLogService
    ) {
        $this->setIdentity = $setIdentity;
        $this->getIdentity = $getIdentity;
        $this->switchUserLogService = $switchUserLogService;
    }
    /**
     * switchTo
     *
     * @param UserInterface $targetUser
     *
     * @return Result
     */
    public function switchTo(UserInterface $targetUser, $options = [])
    {
        $psrRequest = GetPsrRequest::invoke();

        $currentUser = $this->getIdentity->__invoke($psrRequest);

        $result = new Result();

        // Force login as $targetUser
        $this->setIdentity->__invoke($psrRequest, $targetUser);

        // add SU property to target user
        $targetUser->setProperty(
            SuProperty::SU_PROPERTY,
            new SuProperty($currentUser)
        );

        // log action
        $this->logAction(
            $currentUser->getId(),
            $targetUser->getId(),
            'SU was successful',
            true
        );

        $result->setSuccess(true, 'SU was successful');

        return $result;
    }


    /**
     * logAction
     *
     * @param $adminUserId
     * @param $targetUserId
     * @param $action
     * @param $actionSuccess
     *
     * @return void
     */
    protected function logAction(
        $adminUserId,
        $targetUserId,
        $action,
        $actionSuccess
    ) {
        $this->switchUserLogService->logAction(
            $adminUserId,
            $targetUserId,
            $action,
            $actionSuccess
        );
    }
}
