<?php

namespace RcmUser\Api;

use RcmUser\Api\Middleware\ApiMiddlewareAclRolesGet;
use RcmUser\Api\Middleware\ApiMiddlewareAclRolesGetFactory;
use RcmUser\Api\MiddlewareAcl\IsAllowedApiMiddlewareAclRole;
use RcmUser\Api\MiddlewareAcl\IsAllowedApiMiddlewareAclRoleFactory;
use RcmUser\Api\MiddlewareRequest\JsonBodyParser;
use RcmUser\Api\MiddlewareRequest\JsonBodyParserFactory;
use RcmUser\Api\MiddlewareResponse\GetExceptionResponse;
use RcmUser\Api\MiddlewareResponse\GetExceptionResponseFactory;
use RcmUser\Api\MiddlewareResponse\GetNotAllowedResponse;
use RcmUser\Api\MiddlewareResponse\GetNotAllowedResponseFactory;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    ApiMiddlewareAclRolesGet::class => [
                        'factory' => ApiMiddlewareAclRolesGetFactory::class
                    ],
                    IsAllowedApiMiddlewareAclRole::class => [
                        'factory' => IsAllowedApiMiddlewareAclRoleFactory::class
                    ],
                    JsonBodyParser::class => [
                        'factory' => JsonBodyParserFactory::class
                    ],
                    GetExceptionResponse::class => [
                        'factory' => GetExceptionResponseFactory::class
                    ],
                    GetNotAllowedResponse::class => [
                        'factory' => GetNotAllowedResponseFactory::class
                    ],
                ],
            ],
        ];
    }
}
