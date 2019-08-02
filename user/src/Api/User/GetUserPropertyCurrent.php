<?php

namespace RcmUser\Api\User;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetUserPropertyCurrent
{
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
    );
}
