<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Page;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindPageById
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
     * @param int   $id
     * @param array $options
     *
     * @return null|Page
     */
    public function __invoke(
        int $id,
        array $options = []
    ) {
        return $this->repository->find($id);
    }
}
