<?php

namespace Rcm\Api\Repository\Language;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Language;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindLanguageByIso6392t
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
     * @param string $iso639_2t
     * @param array  $options
     *
     * @return null|Language
     */
    public function __invoke(
        string $iso639_2t,
        array $options = []
    ) {
        return $this->repository->findOneBy(['iso639_2t' => $iso639_2t]);
    }
}
