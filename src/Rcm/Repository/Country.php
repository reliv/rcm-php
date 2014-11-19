<?php


namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Rcm\Exception\CountryNotFoundException;


/**
 * Class Country
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Repository
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class Country extends EntityRepository {

    protected $validFormats = array(
        'iso3',
        'iso2',
        'countryName',
    );

    public function getCountryByString(
        $code,
        $format = 'iso3',
        $default = null
    ) {
        if (empty($code)) {

            return $default;
        }

        if(!in_array($format, $this->validFormats)){

            throw new CountryNotFoundException('Country Format not valid.');
        }

        try {

            $result = $this->findOneBy(array($format => $code));
        } catch (NoResultException $e) {

            $result = $default;
        }

        return $result;
    }
} 