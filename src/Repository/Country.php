<?php

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Rcm\Exception\CountryNotFoundException;

/**
 * @deprecated Repository should not be used directly, please use the /Rcm/Api/{model}/Repository functions
 * Class Country
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Repository
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2018 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Country extends EntityRepository
{
    /**
     * @var array
     */
    protected $validFormats
        = [
            'iso3',
            'iso2',
            'countryName',
        ];

    /**
     * getCountryByString
     *
     * @param string $code
     * @param string $format
     * @param null   $default
     *
     * @return null|object
     */
    public function getCountryByString(
        $code,
        $format = 'iso3',
        $default = null
    ) {
        if (empty($code)) {
            return $default;
        }

        if (!in_array($format, $this->validFormats)) {
            throw new CountryNotFoundException('Country Format not valid.');
        }

        try {
            $result = $this->findOneBy([$format => $code]);
        } catch (NoResultException $e) {
            $result = $default;
        }

        return $result;
    }
}
