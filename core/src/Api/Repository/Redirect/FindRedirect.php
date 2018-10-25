<?php

namespace Rcm\Api\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Redirect;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindRedirect
{
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
     * @param int   $id
     * @param array $options
     *
     * @return null|Redirect
     */
    public function __invoke(
        $id,
        array $options = []
    ) {
        return $this->repository->find($id);
    }
}
