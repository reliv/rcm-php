<?php

namespace Rcm\Api\Repository\Container;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Container;
use Rcm\Api\Repository\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindContainers
{
    const OPTION_ORDER_BY = 'orderBy';
    const OPTION_LIMIT = 'limit';
    const OPTION_OFFSET = 'offset';
    /**
     * @var \Rcm\Repository\Container
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Container::class
        );
    }

    /**
     * @param int   $siteId
     * @param array $options
     *
     * @return Container[]
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
