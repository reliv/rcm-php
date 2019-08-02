<?php

namespace RcmUser\Api;

use RcmUser\Api\Middleware\ApiMiddlewareAclRolesGet;
use RcmUser\Api\MiddlewareAcl\IsAllowedApiMiddlewareAclRole;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfigRoutes
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'routes' => [
                '[GET]/api/admin/rcmuser-acl-role' => [
                    'name' => '[GET]/api/admin/rcmuser-acl-role',
                    'path' => '/api/admin/rcmuser-acl-role',
                    'middleware' => [
                        'acl' => IsAllowedApiMiddlewareAclRole::class,
                        'api' => ApiMiddlewareAclRolesGet::class,
                    ],
                    'options' => [],
                    'allowed_methods' => ['GET'],
                    'swagger' => [
                        'post' => [
                            'description'
                            => 'Return full list of roles keyed by namespace in format: {root}.{child}...',
                            'produces' => [
                                'application/json',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
