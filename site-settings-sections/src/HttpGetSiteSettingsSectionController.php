<?php


namespace Rcm\SiteSettingsSections;

use Doctrine\ORM\EntityManager;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Entity\Site;
use RcmUser\Api\Acl\IsAllowed;
use Zend\Diactoros\Response\JsonResponse;

class HttpGetSiteSettingsSectionController implements MiddlewareInterface
{
    /** @var GetSection */
    protected $getSection;

    /** @var EntityManager */
    protected $entityManager;

    /** @var Site */
    protected $currentSite;

    /** @var IsAllowed */
    protected $isAllowed;

    public function __construct(
        IsAllowed $isAllowed,
        GetSection $getSection,
        EntityManager $entityManager,
        Site $currentSite
    ) {
        $this->isAllowed = $isAllowed;
        $this->getSection = $getSection;
        $this->entityManager = $entityManager;
        $this->currentSite = $currentSite;
    }

    /**
     * Exmaple Url:
     * http://local.reliv.com:3000/api/rcm/site-settings-section/current/bob
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return JsonResponse
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ): JsonResponse {
        if (!$this->isAllowed->__invoke($request, 'sites', 'admin')) {
            return new JsonResponse(['error' => 'unauthorized'], 401);
        }

        $sectionName = $request->getAttribute('sectionName');

        try {
            $settings = $this->getSection->__invoke(
                $this->currentSite,
                $sectionName
            );
        } catch (InvalidSectionNameException $e) {
            return new JsonResponse(['error' => 'invalid section name'], 400);
        }

        if ($settings === null) {
            return new JsonResponse(['error' => 'settings not found'], 404);
        }

        return new JsonResponse($settings);
    }
}
