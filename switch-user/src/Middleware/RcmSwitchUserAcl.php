<?php

namespace Rcm\SwitchUser\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rcm\SwitchUser\Service\SwitchUserAclService;
use Reliv\PipeRat\Middleware\Acl\AbstractAcl;
use Reliv\PipeRat\Middleware\Middleware;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RcmSwitchUserAcl extends AbstractAcl implements Middleware
{
    /**
     * @var SwitchUserAclService
     */
    protected $switchUserAclService;

    /**
     * @param SwitchUserAclService $switchUserAclService
     */
    public function __construct(
        SwitchUserAclService $switchUserAclService
    ) {
        $this->switchUserAclService = $switchUserAclService;
    }

    /**
     * __invoke
     *
     * @param Request       $request
     * @param Response      $response
     * @param callable|null $out
     *
     * @return mixed
     */
    public function __invoke(
        Request $request,
        Response $response,
        callable $out = null
    ) {
        $isAllowed = $this->switchUserAclService->currentUserIsAllowed(
            $this->getOption($request, 'resourceId', null),
            $this->getOption($request, 'privilege', null)
        );

        if ($isAllowed) {
            return $out($request, $response);
        }

        return $this->getResponseWithAclFailStatus($request, $response);
    }
}
