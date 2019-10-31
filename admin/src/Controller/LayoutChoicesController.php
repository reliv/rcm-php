<?php

namespace RcmAdmin\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\NotAllowedException;
use Rcm\Api\GetSiteByRequest;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\SiteSecureRepo;
use Rcm\Service\CurrentSite;
use Rcm\Service\LayoutManager;
use Zend\Diactoros\Response\JsonResponse;

class LayoutChoicesController implements MiddlewareInterface
{
    protected $getSiteByRequest;

    public function __construct(
        GetSiteByRequest $getSiteByRequest
    ) {
        $this->getSiteByRequest = $getSiteByRequest;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $siteSecureRepo = $request->getAttribute(RequestContext::class)->get(SiteSecureRepo::class);

        try {
            $layoutChoices = $siteSecureRepo->getLayoutChoicesBySite(
                $this->getSiteByRequest->__invoke($request)
            );
        } catch (NotAllowedException $e) {
            return new JsonResponse(['errorMessage' => 'Not Found'], 404);
        }

        $layoutChoicesArray = [];
        foreach ($layoutChoices as $value => $label) {
            $layoutChoicesArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return new JsonResponse($layoutChoicesArray);
    }
}
