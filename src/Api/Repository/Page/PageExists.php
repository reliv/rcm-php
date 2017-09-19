<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Page;
use Rcm\Page\PageTypes\PageTypes;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PageExists
{
    /**
     * @var \Rcm\Repository\Page
     */
    protected $repository;

    /**
     * @var \Rcm\Repository\Site
     */
    protected $siteRepository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Page::class
        );

        $this->siteRepository = $entityManager->getRepository(
            Page::class
        );
    }

    /**
     * @param int    $siteId
     * @param string $pageName
     * @param string $pageType
     * @param array  $options
     *
     * @return bool
     */
    public function __invoke(
        $siteId,
        string $pageName,
        string $pageType = PageTypes::NORMAL,
        array $options = []
    ): bool {
        if (empty($siteId)) {
            return false;
        }

        $site = $this->siteRepository->find($siteId);

        if (empty($site)) {
            return false;
        }

        try {
            $page = $this->repository->getPageByName(
                $site,
                $pageName,
                $pageType
            );
        } catch (\Exception $e) {
            $page = null;
        }

        return !empty($page);
    }
}
