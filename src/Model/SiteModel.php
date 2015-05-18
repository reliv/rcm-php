<?php

namespace RcmAdmin\Model;

use Doctrine\ORM\EntityManagerInterface;
use Rcm\Entity\Domain;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Exception\DomainNotFoundException;
use Rcm\Exception\SiteNotFoundException;

/**
 * Class SiteModel
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class SiteModel
{

    protected $config;

    /**
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * getConfig
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}
