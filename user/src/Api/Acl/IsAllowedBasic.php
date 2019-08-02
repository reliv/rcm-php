<?php

namespace RcmUser\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\Authentication\GetIdentity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedBasic implements IsAllowed
{
    protected $getIdentity;
    protected $isUserAllowed;

    /**
     * @param GetIdentity   $getIdentity
     * @param IsUserAllowed $isUserAllowed
     */
    public function __construct(
        GetIdentity $getIdentity,
        IsUserAllowed $isUserAllowed
    ) {
        $this->getIdentity = $getIdentity;
        $this->isUserAllowed = $isUserAllowed;
    }

    /**
     * @param ServerRequestInterface $request
     * @param string                 $resourceId
     * @param string|null            $privilege
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        $resourceId,
        $privilege = null
    ):bool {
        $user = $this->getIdentity->__invoke($request);

        return $this->isUserAllowed->__invoke(
            $user,
            $resourceId,
            $privilege
        );
    }
}
