<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\User\ReadUser;
use RcmUser\Api\User\ReadUserResult;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\Exception\RcmUserException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RefreshIdentityBasic implements RefreshIdentity
{
    protected $getIdentity;
    protected $readUserResult;
    protected $userAuthenticationService;

    /**
     * @param GetIdentity               $getIdentity
     * @param ReadUserResult            $readUserResult
     * @param UserAuthenticationService $userAuthenticationService
     */
    public function __construct(
        GetIdentity $getIdentity,
        ReadUserResult $readUserResult,
        UserAuthenticationService $userAuthenticationService
    ) {
        $this->getIdentity = $getIdentity;
        $this->readUserResult = $readUserResult;
        $this->userAuthenticationService = $userAuthenticationService;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return null
     * @throws RcmUserException
     */
    public function __invoke(
        ServerRequestInterface $request
    ) {
        $currentUser = $this->getIdentity->__invoke($request);

        if (empty($currentUser)) {
            return null;
        }

        $result = $this->readUserResult->__invoke($currentUser);

        if (!$result->isSuccess()) {
            return null;
        }

        $user = $result->getUser();

        $userId = $user->getId();

        if ($userId != $currentUser->getId()) {
            throw new RcmUserException(
                'RefreshIdentity expects user to be get same identity as current.'
            );
        }

        // Sync properties
        $currentProperties = $currentUser->getProperties();
        $updatedProperties = $user->getProperties();
        foreach ($currentProperties as $currentPropertyId => $currentProperty) {
            if (!array_key_exists($currentPropertyId, $updatedProperties)) {
                $user->setProperty($currentPropertyId, $currentProperty);
            }
        }

        $this->userAuthenticationService->setIdentity($user);
    }
}
