<?php

namespace Rcm\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Redirect;
use Rcm\Repository\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindRedirects
{
    const OPTION_ORDER_BY = 'orderBy';
    const OPTION_LIMIT = 'limit';
    const OPTION_OFFSET = 'offset';
    /**
     * @var \Rcm\Repository\Redirect
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Redirect::class
        );
    }

    /**
     * @param array $options
     *
     * @return Redirect[]
     */
    public function __invoke(
        array $options = []
    ) {
        $orderBy = Options::get($options, self::OPTION_ORDER_BY, null);
        $limit = Options::get($options, self::OPTION_LIMIT, null);
        $offset = Options::get($options, self::OPTION_OFFSET, null);

        return $this->repository->findBy([], $orderBy, $limit, $offset);
    }
}
