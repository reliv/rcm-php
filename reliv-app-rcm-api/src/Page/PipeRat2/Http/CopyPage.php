<?php

namespace Reliv\App\RcmApi\Page\PipeRat2\Http;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Api\Repository\Page\FindPageById;
use Rcm\Api\Repository\Page\PageExists;
use RcmAdmin\Service\PageMutationService;
use RcmUser\Api\Authentication\GetCurrentUser;
use Reliv\PipeRat2\Core\Api\ResponseWithDataBody;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Zend\Diactoros\Response\JsonResponse;

class CopyPage implements MiddlewareInterface
{
    protected $findPageById;
    protected $pageExists;
    protected $getCurrentUser;
    protected $pageMutationService;

    public function __construct(
        FindPageById $findPageById,
        PageExists $pageExists,
        GetCurrentUser $getCurrentUser,
        PageMutationService $pageMutationService
    ) {
        $this->findPageById = $findPageById;
        $this->pageExists = $pageExists;
        $this->getCurrentUser = $getCurrentUser;
        $this->pageMutationService = $pageMutationService;
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

        $currentUser = $this->getCurrentUser->__invoke($request);

        if (empty($currentUser)) {
            throw new \Exception('A valid user is required in ' . self::class);
        }

        $this->pageMutationService->duplicatePage(
            $currentUser,
            $sourcePage,
            $destinationSite->getSiteId(),
            $data['name'],
            $pageType
        );

        return new JsonResponse(['success' => true]);
    }
}
