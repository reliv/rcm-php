<?php

namespace Rcm\Api\Repository\Domain;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindDomainsWithSubDomain
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
     * @param string $domainPrefix
     * @param array  $options
     *
     * @return array
     */
    public function __invoke(
        string $domainPrefix,
        array $options = []
    ): array {
        /** @var \Doctrine\ORM\Query $query */
        $query = $this->entityManager->createQuery(
            'SELECT d.domain ' .
            'FROM Rcm\\Entity\\Domain d ' .
            'WHERE d.domain LIKE :domainPrefix ' .
            'OR d.domain = :domain'
        );
        $query->setParameter('domainPrefix', $domainPrefix . '.%');
        $query->setParameter('domain', $domainPrefix);

        try {
            $results = $query->getResult();
        } catch (NoResultException $e) {
            return [];
        }

        return $results;
    }
}
