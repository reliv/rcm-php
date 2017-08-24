<?php

namespace Rcm\Repository\Language;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Language;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindLanguageByIso639_1
{
    /**
     * @var \Rcm\Repository\Language
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Language::class
        );
    }

    /**
     * @param string $iso639_1
     * @param array  $options
     *
     * @return null|Language
     */
    public function __invoke(
        string $iso639_1,
        array $options = []
    ) {
        return $this->repository->findOneBy(['iso639_1' => $iso639_1]);
    }
}
