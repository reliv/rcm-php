<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Repository\Page;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindRevisionList
{
    /**
     * @var \Rcm\Repository\Page
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Page::class
        );
    }

    /**
     * @param int|string $siteId
     * @param string     $pageName
     * @param string     $pageType
     * @param bool       $published
     * @param int        $limit
     *
     * @return array|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(
        int $siteId,
        string $pageName,
        string $pageType = PageTypes::NORMAL,
        bool $published = false,
        int $limit = 10
    ) {
        return $this->repository->getRevisionList(
            $siteId,
            $pageName,
            $pageType,
            $published,
            $limit
        );
    }
}
