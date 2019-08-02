<?php

namespace RcmUser\Api\MiddlewareAcl;

use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Api\MiddlewareResponse\GetNotAllowedResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedApiMiddlewareAclRole extends IsAllowedApiMiddleware
{
    /**
     * @param IsAllowed             $isAllowed
     * @param string                $resourceId
     * @param string|null           $privilege
     * @param GetNotAllowedResponse $getNotAllowedResponse
     * @param int                   $notAllowedStatus
     */
    public function __construct(
        IsAllowed $isAllowed,
        string $resourceId,
        string $privilege = null,
        GetNotAllowedResponse $getNotAllowedResponse,
        int $notAllowedStatus = self::DEFAULT_NOT_ALLOWED_STATUS
    ) {
        parent::__construct(
            $isAllowed,
            $resourceId,
            $privilege,
            $getNotAllowedResponse,
            $notAllowedStatus
        );
    }
}
