<?php

namespace RcmAdmin\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Api\GetSiteByRequest;
use Rcm\Service\CurrentSite;
use Rcm\Service\LayoutManager;
use RcmUser\Api\Acl\IsAllowed;
use Zend\Diactoros\Response\JsonResponse;

class LayoutChoicesController implements MiddlewareInterface
{
    protected $layoutManager;
    protected $getSiteByRequest;
    protected $isAllowed;

    public function __construct(
        LayoutManager $layoutManager,
        GetSiteByRequest $getSiteByRequest,
        IsAllowed $isAllowed
    ) {
        $this->layoutManager = $layoutManager;
        $this->getSiteByRequest = $getSiteByRequest;
        $this->isAllowed = $isAllowed;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if (!$this->isAllowed->__invoke($request, 'sites', 'admin')) {
            return new JsonResponse('Unauthorized', 401);
        }

        $site = $this->getSiteByRequest->__invoke($request);
        $theme = $site->getTheme();
        $layoutChoices = $this->layoutManager->siteThemeLayoutsConfigToAssociativeArray(
            $this->layoutManager->getSiteThemeLayoutsConfig($theme)
        );
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
