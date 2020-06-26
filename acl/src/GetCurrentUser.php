<?php

namespace Rcm\Acl;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\Authentication\GetCurrentUser as GetUserByRequest;

class GetCurrentUser
{
    protected $getUserByRequest;
    protected $currentRequest;

    public function __construct(ServerRequestInterface $currentRequest, GetUserByRequest $getUserByRequest)
    {
        $this->getUserByRequest = $getUserByRequest;
        $this->currentRequest = $currentRequest;
    }

    /**
     * Returns the current user ID of null if there is no current user.
     *
     * @return RcmUser\User\Entity\UserInterface
     * @throws \Exception
     */
    public function __invoke()
    {
        return $this->getUserByRequest->__invoke($this->currentRequest);
    }
}
