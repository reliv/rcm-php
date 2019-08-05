<?php

namespace Reliv\App\RcmApi\Page\PipeRat2\Api;

use Reliv\PipeRat2\Repository\Api\FindOne;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindOnePage implements FindOne
{
    protected $findOnePage;

    /**
     * @param \Rcm\Api\Repository\Page\FindOnePage $findOnePage
     */
    public function __construct(
        \Rcm\Api\Repository\Page\FindOnePage $findOnePage
    ) {
        $this->findOnePage = $findOnePage;
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
        return $this->findOnePage->__invoke($criteria, $orderBy, $options);
    }
}
