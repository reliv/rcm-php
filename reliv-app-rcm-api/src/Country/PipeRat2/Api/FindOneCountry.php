<?php

namespace Reliv\App\RcmApi\Country\PipeRat2\Api;

use Reliv\PipeRat2\Repository\Api\FindOne;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindOneCountry implements FindOne
{
    protected $findOneCountry;

    /**
     * @param \Rcm\Api\Repository\Country\FindOneCountry $findOneCountry
     */
    public function __construct(
        \Rcm\Api\Repository\Country\FindOneCountry $findOneCountry
    ) {
        $this->findOneCountry = $findOneCountry;
    }

    /**
     * @param array $criteria
     * @param null  $orderBy
     * @param array $options
     *
     * @return null|object
     */
    public function __invoke(
        array $criteria = [],
        $orderBy = null,
        array $options = []
    ) {
        return $this->findOneCountry->__invoke($criteria, $orderBy, $options);
    }
}
