<?php

namespace RcmUser\Api\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Acl\Service\AclDataService;
use RcmUser\Api\MiddlewareResponse\GetExceptionResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiMiddlewareAclRolesGet implements MiddlewareInterface
{
    protected $aclDataService;
    protected $getExceptionResponse;

    /**
     * @param AclDataService       $aclDataService
     * @param GetExceptionResponse $getExceptionResponse
     */
    public function __construct(
        AclDataService $aclDataService,
        GetExceptionResponse $getExceptionResponse
    ) {
        $this->aclDataService = $aclDataService;
        $this->getExceptionResponse = $getExceptionResponse;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface|JsonResponse
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        try {
            $result = $this->aclDataService->getNamespacedRoles();
        } catch (\Exception $exception) {
            return $this->getExceptionResponse->__invoke($request, $exception);
        }

        return new JsonResponse(
            $result
        );
    }
}
