<?php

namespace Rcm\Api;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Entity\Site;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetSiteByRequest
{
    /**
     * @var \Rcm\Repository\Site
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Site::class
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return Site|null
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ) {
        $domain = $request->getUri()->getHost();

        return $this->repository->getSiteByDomain($domain);
    }
}
