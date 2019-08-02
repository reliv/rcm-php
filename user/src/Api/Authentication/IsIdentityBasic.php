<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsIdentityBasic implements IsIdentity
{
    protected $getIdentity;

    /**
     * @param GetIdentity $getIdentity
     */
    public function __construct(
        GetIdentity $getIdentity
    ) {
        $this->getIdentity = $getIdentity;
    }

    /**
     * @param ServerRequestInterface $request
     * @param UserInterface          $user
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        UserInterface $user
    ): bool {
        $sessionUser = $this->getIdentity->__invoke($request);

        if (empty($sessionUser)) {
            return false;
        }

        // @todo make sure this is a valid check for all cases
        $id = $user->getId();
        if (!empty($id)
            && $user->getId() === $sessionUser->getId()
        ) {
            return true;
        }

        $username = $user->getUsername();
        if (!empty($username)
            && $user->getUsername() === $sessionUser->getUsername()
        ) {
            return true;
        }

        return false;
    }
}
