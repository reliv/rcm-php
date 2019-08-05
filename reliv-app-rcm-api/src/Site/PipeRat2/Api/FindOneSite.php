<?php

namespace Reliv\App\RcmApi\Site\PipeRat2\Api;

use Reliv\PipeRat2\Repository\Api\FindOne;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindOneSite implements FindOne
{
    protected $findOneSite;

    /**
     * @param \Rcm\Api\Repository\Site\FindOneSite $findOneSite
     */
    public function __construct(
        \Rcm\Api\Repository\Site\FindOneSite $findOneSite
    ) {
        $this->findOneSite = $findOneSite;
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
        return $this->findOneSite->__invoke($criteria, $orderBy, $options);
    }
}
