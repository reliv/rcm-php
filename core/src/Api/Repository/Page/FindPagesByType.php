<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Page;
use Rcm\Page\PageTypes\PageTypes;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindPagesByType
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
     * @param int    $siteId
     * @param string $pageType
     * @param array  $options
     *
     * @return Page[]
     */
    public function __invoke(
        int $siteId,
        string $pageType = PageTypes::NORMAL,
        array $options = []
    ) {
        return $this->repository->findBy(
            [
                'siteId' => $siteId,
                'pageType' => $pageType
            ]
        );
    }
}
