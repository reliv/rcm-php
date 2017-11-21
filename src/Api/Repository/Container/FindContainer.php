<?php

namespace Rcm\Api\Repository\Container;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Container;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindContainer
{
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
     * @param int   $id
     * @param array $options
     *
     * @return null|Container
     */
    public function __invoke(
        $id,
        array $options = []
    ) {
        return $this->repository->find($id);
    }
}
