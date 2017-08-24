<?php

namespace Rcm\Repository\Page;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Page;
use Rcm\Repository\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindPages
{
    const OPTION_ORDER_BY = 'orderBy';
    const OPTION_LIMIT = 'limit';
    const OPTION_OFFSET = 'offset';
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
     * @param int   $siteId
     * @param array $options
     *
     * @return Page[]
     */
    public function __invoke(
        int $siteId,
        array $options = []
    ) {
        $orderBy = Options::get($options, self::OPTION_ORDER_BY, null);
        $limit = Options::get($options, self::OPTION_LIMIT, null);
        $offset = Options::get($options, self::OPTION_OFFSET, null);

        return $this->repository->findBy(['siteId' => $siteId], $orderBy, $limit, $offset);
    }
}
