<?php

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Rcm\Exception\LanguageNotFoundException;

/**
 * @deprecated Repository should not be used directly, please use the /Rcm/Api/{model}/Repository functions
 * Class Language
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
class Language extends EntityRepository
{

    /**
     * @var array
     */
    protected $validFormats
        = [
            'iso639_1',
            'iso639_2b',
            'iso639_2t',
        ];

    /**
     * getLanguageByString
     *
     * @param string $code
     * @param string $format
     * @param null   $default
     *
     * @return null|object
     */
    public function getLanguageByString(
        $code,
        $format = 'iso639_2t',
        $default = null
    ) {
        if (empty($code)) {
            return $default;
        }

        if (!in_array($format, $this->validFormats)) {
            throw new LanguageNotFoundException('Language Format not valid.');
        }

        try {
            $result = $this->findOneBy([$format => $code]);
        } catch (NoResultException $e) {
            $result = $default;
        }

        return $result;
    }
}
