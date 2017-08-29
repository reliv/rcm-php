<?php

namespace Rcm\Api\Repository\Setting;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Setting;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindSettingByName
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Setting::class
        );
    }

    /**
     * @param string $name
     * @param array  $options
     *
     * @return null|object
     */
    public function __invoke(
        string $name,
        array $options = []
    ) {
        return $this->repository->findOneBy(['name' => $name]);
    }
}
