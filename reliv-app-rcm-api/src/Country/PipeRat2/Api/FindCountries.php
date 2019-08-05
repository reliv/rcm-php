<?php

namespace Reliv\App\RcmApi\Country\PipeRat2\Api;

use Reliv\PipeRat2\Repository\Api\Find;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindCountries implements Find
{
    protected $findCountries;

    /**
     * @param \Rcm\Api\Repository\Country\FindCountries $findCountries
     */
    public function __construct(
        \Rcm\Api\Repository\Country\FindCountries $findCountries
    ) {
        $this->findCountries = $findCountries;
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
        return $this->findCountries->__invoke(
            $criteria,
            $orderBy,
            $limit,
            $offset,
            $options
        );
    }
}
