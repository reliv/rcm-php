<?php

namespace RcmUser\Api\MiddlewareAcl;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Api\MiddlewareResponse\GetNotAllowedResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedApiMiddleware implements MiddlewareInterface
{
    const DEFAULT_NOT_ALLOWED_STATUS = 401;

    protected $isAllowed;
    protected $resourceId;
    protected $privilege;
    protected $getNotAllowedResponse;
    protected $notAllowedStatus;

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
        $this->isAllowed = $isAllowed;
        $this->resourceId = $resourceId;
        $this->privilege = $privilege;
        $this->getNotAllowedResponse = $getNotAllowedResponse;
        $this->notAllowedStatus = $notAllowedStatus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        if (!$this->isAllowed->__invoke($request, $this->resourceId, $this->privilege)) {
            return $this->getNotAllowedResponse->__invoke(
                $request,
                'Not Allowed: resource: ' . $this->resourceId,
                $this->notAllowedStatus
            );
        }

        return $delegate->process($request);
    }
}
