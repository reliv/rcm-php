<?php

namespace RcmUser\Api\User;

use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Service\UserDataService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildUserBasic implements BuildUser
{
    protected $userDataService;

    /**
     * @param UserDataService $userDataService
     */
    public function __construct(
        UserDataService $userDataService
    ) {
        $this->userDataService = $userDataService;
    }

    /**
     * @param UserInterface $user
     * @param array         $options
     *
     * @return UserInterface
     * @throws RcmUserException
     */
    public function __invoke(
        UserInterface $user,
        array $options = []
    ): UserInterface {
        $result = $this->userDataService->buildUser($user);

        // since build user is an event, we might not get anything
        if (empty($result)) {
            return $user;
        }

        if ($result->isSuccess() || $result->getUser() == null) {
            return $result->getUser();
        } else {
            // this should not fail, if it does, something is really wrong
            throw new RcmUserException(
                'User could not be built or was not returned'
            );
        }
    }
}
