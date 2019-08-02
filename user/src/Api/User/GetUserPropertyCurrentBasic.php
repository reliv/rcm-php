<?php

namespace RcmUser\Api\User;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\Authentication\GetIdentity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserPropertyCurrentBasic implements GetUserPropertyCurrent
{
    protected $getIdentity;
    protected $getUserProperty;

    /**
     * @param GetIdentity     $getIdentity
     * @param GetUserProperty $getUserProperty
     */
    public function __construct(
        GetIdentity $getIdentity,
        GetUserProperty $getUserProperty
    ) {
        $this->getIdentity = $getIdentity;
        $this->getUserProperty = $getUserProperty;
    }

    /**
     * @param ServerRequestInterface $request
     * @param string                 $propertyNameSpace
     * @param null                   $default
     * @param bool                   $refresh
     *
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        $propertyNameSpace,
        $default = null,
        $refresh = false
    ) {
        $user = $this->getIdentity->__invoke($request);

        if (empty($user)) {
            return $default;
        }

        return $this->getUserProperty->__invoke(
            $user,
            $propertyNameSpace,
            $default,
            $refresh
        );
    }
}
