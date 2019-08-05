<?php

namespace Reliv\App\RcmApi\Site\PipeRat2\Api;

use Rcm\Api\Repository\Site\FindSite;
use Reliv\PipeRat2\Repository\Api\FindById;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindByIdSite implements FindById
{
    protected $findSite;

    /**
     * @param FindSite $findSite
     */
    public function __construct(
        FindSite $findSite
    ) {
        $this->findSite = $findSite;
    }

    /**
     * @param int|string $id
     * @param array      $options
     *
     * @return object|null $data
     */
    public function __invoke(
        $id,
        array $options = []
    ) {
        return $this->findSite->__invoke(
            $id,
            $options
        );
    }
}
