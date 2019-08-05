<?php

namespace Reliv\App\RcmApi\Site\PipeRat2\Api;

use Reliv\PipeRat2\Repository\Api\Find;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindSites implements Find
{
    protected $findSites;

    /**
     * @param \Rcm\Api\Repository\Site\FindSites $findSites
     */
    public function __construct(
        \Rcm\Api\Repository\Site\FindSites $findSites
    ) {
        $this->findSites = $findSites;
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     * @param array      $options
     *
     * @return array|null
     */
    public function __invoke(
        array $criteria = [],
        array $orderBy = null,
        $limit = null,
        $offset = null,
        array $options = []
    ) {
        return $this->findSites->__invoke(
            $criteria,
            $orderBy,
            $limit,
            $offset,
            $options
        );
    }
}
