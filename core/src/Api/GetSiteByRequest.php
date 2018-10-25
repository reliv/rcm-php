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
     * @todo Implement real cache that can expire
     *
     * @var array
     */
    protected $cache = [];

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

        if (array_key_exists($domain, $this->cache)) {
            return $this->cache[$domain];
        }

        $this->cache[$domain] = $this->repository->getSiteByDomain($domain);

        return $this->cache[$domain];
    }
}
