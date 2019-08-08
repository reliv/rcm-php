<?php

namespace Reliv\App\RcmApi\Page\PipeRat2\Http;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\NotAllowedException;
use Rcm\Api\Repository\Page\FindPageById;
use Rcm\Api\Repository\Page\PageExists;
use Rcm\RequestContext\RequestContext;
use RcmAdmin\Service\PageSecureRepo;
use RcmUser\Api\Authentication\GetCurrentUser;
use Reliv\PipeRat2\Core\Api\ResponseWithDataBody;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class CopyPage implements MiddlewareInterface
{
    protected $findPageById;
    protected $pageExists;

    public function __construct(
        FindPageById $findPageById,
        PageExists $pageExists
    ) {
        $this->findPageById = $findPageById;
        $this->pageExists = $pageExists;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        // Get Route param from attributes
        $sourcePageId = (int)$request->getAttribute('sourcePageId');

        if (empty($sourcePageId)) {
            return new DataResponseBasic(
                null,
                400,
                [],
                'sourcePageId required'
            );
        }

        /** @var \Rcm\Entity\Page $sourcePage */
        $sourcePage = $this->findPageById->__invoke($sourcePageId);

        if (empty($sourcePage)) {
            return new DataResponseBasic(
                null,
                400,
                [],
                'page entity not found'
            );
        }

        // @todo Might get source site from request
        $sourceSite = $sourcePage->getSite();

        $destinationSite = $sourceSite;

        $data = $request->getParsedBody();

        $pageType = null;

        if (!empty($data['pageType'])) {
            $pageType = $data['pageType'];
        } else {
            $pageType = $sourcePage->getPageType();
        }

        $pageExists = $this->pageExists->__invoke(
            $sourceSite->getSiteId(),
            $data['name'],
            $pageType
        );

        if ($pageExists) {
            return new DataResponseBasic(
                null,
                400,
                [],
                'duplicated page name'
            );
        }

        /**
         * @var $requestContext ContainerInterface
         */
        $requestContext = $request->getAttribute(RequestContext::class);

        /**
         * @var $pageMutationService PageSecureRepo
         */
        $pageMutationService = $requestContext->get(PageSecureRepo::class);

        try {
            $pageMutationService->duplicatePage(
                $sourcePage,
                $destinationSite->getSiteId(),
                $data['name'],
                $pageType
            );
        } catch (NotAllowedException $e) {
            return new HtmlResponse('Forbidden', 403);
        }

        return new JsonResponse(['success' => true]);
    }
}
