<?php

namespace Rcm\Api\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Redirect;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RemoveRedirect
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int    $id
     * @param string $modifiedByUserId
     * @param string $modifiedReason
     * @param array  $options
     *
     * @return bool
     */
    public function __invoke(
        int $id,
        string $modifiedByUserId,
        string $modifiedReason,
        array $options = []
    ) {
        $redirectRepository = $this->entityManager->getRepository(
            Redirect::class
        );

        $redirect = $redirectRepository->findOneBy(
            ['redirectId' => $id]
        );

        if (empty($redirect)) {
            return false;
        }

        $this->entityManager->remove($redirect);
        $this->entityManager->flush($redirect);

        return true;
    }
}
