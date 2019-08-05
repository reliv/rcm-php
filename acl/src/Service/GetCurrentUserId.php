<?php

namespace Rcm\Acl\Service;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\Authentication\GetCurrentUser;

/**
 * @deprecated use Rcm\Acl\GetCurrentUser instead
 *
 * Class GetCurrentUserId
 * @package Rcm\Acl\Service
 */
class GetCurrentUserId
{
    protected $getUserByRequest;
    protected $currentRequest;

    public function __construct(ServerRequestInterface $currentRequest, GetCurrentUser $getUserByRequest)
    {
        $this->getUserByRequest = $getUserByRequest;
        $this->currentRequest = $currentRequest;
    }

    /**
     * Returns the current user ID of null if there is no current user.
     *
     * @return string|null
     * @throws \Exception
     */
    public function __invoke()
    {
        $user = $this->getUserByRequest->__invoke($this->currentRequest);
        if ($user === null) {
            return null;
        }

        return $user->getId();
    }
}
