<?php

namespace Reliv\App\RcmApi\Country\PipeRat2\Api;

use Rcm\Api\Repository\Country\FindCountryByIso3;
use Reliv\PipeRat2\Repository\Api\FindById;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindByIso3Country implements FindById
{
    protected $findCountryByIso3;

    /**
     * @param FindCountryByIso3 $findCountryByIso3
     */
    public function __construct(
        FindCountryByIso3 $findCountryByIso3
    ) {
        $this->findCountryByIso3 = $findCountryByIso3;
    }

    /**
     * @param int|string $iso3
     * @param array      $options
     *
     * @return object|null $data
     */
    public function __invoke(
        $iso3,
        array $options = []
    ) {
        return $this->findCountryByIso3->__invoke(
            $iso3,
            $options
        );
    }
}
