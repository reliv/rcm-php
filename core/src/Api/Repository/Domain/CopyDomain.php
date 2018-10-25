<?php

namespace Rcm\Api\Repository\Domain;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Domain;
use Rcm\Tracking\Model\Tracking;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CopyDomain
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
     * @param Domain $sourceDomain
     * @param string $newDomainName
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $options
     *
     * @return Domain
     */
    public function __invoke(
        Domain $sourceDomain,
        string $newDomainName,
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        array $options = []
    ) {
        $newDomain = $sourceDomain->newInstance(
            $createdByUserId,
            $createdReason
        );

        $newDomain->setDomainName($newDomainName);

        $this->entityManager->persist($newDomain);
        $this->entityManager->flush($newDomain);

        return $newDomain;
    }
}
