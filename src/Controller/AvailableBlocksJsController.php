<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Service\SiteService;
use RcmAdmin\Service\RendererAvailableBlocksJs;
use RcmUser\Service\RcmUserService;
use Zend\Http\Headers;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class AvailableBlocksJsController
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AvailableBlocksJsController extends AbstractActionController
{
    /**
     * @var RendererAvailableBlocksJs
     */
    protected $rendererAvailableBlocksJs;

    /**
     * @var CmsPermissionChecks
     */
    protected $cmsPermissionChecks;

    /**
     * @var SiteService
     */
    protected $siteService;

    /**
     * Constructor.
     *
     * @param RendererAvailableBlocksJs $rendererAvailableBlocksJs
     * @param CmsPermissionChecks       $cmsPermissionChecks
     * @param SiteService               $siteService
     */
    public function __construct(
        RendererAvailableBlocksJs $rendererAvailableBlocksJs,
        CmsPermissionChecks $cmsPermissionChecks,
        SiteService $siteService
    ) {
        $this->rendererAvailableBlocksJs = $rendererAvailableBlocksJs;
        $this->cmsPermissionChecks = $cmsPermissionChecks;
        $this->siteService = $siteService;
    }

    /**
     * indexAction
     *
     * @return Response
     */
    public function indexAction()
    {
        /** @var Response $response */
        $response = $this->getResponse();

        $headers = new Headers();

        $headers->addHeaders(
            [
                'cache-control' => 'no-store, no-cache, must-revalidate',
                'content-type' => 'application/javascript',
                'pragma' => 'no-cache',
            ]
        );

        $response->setHeaders(
            $headers
        );

        $isAllowed = $this->cmsPermissionChecks->siteAdminCheck($this->siteService->getCurrentSite());
        /**
         *
         */
        if(!$isAllowed) {
            $response->setContent(
                '// rcmBlockConfigs not available'
            );
            return $response;
        }

        $content = $this->rendererAvailableBlocksJs->__invoke();

        $response->setContent(
            $content
        );

        return $response;
    }
}
