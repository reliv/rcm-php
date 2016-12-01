<?php

namespace Rcm\Service;

use Rcm\Entity\Site;
use Zend\Validator\Ip;

/**
 * Class DomainService
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class DomainService
{
    /**
     * @var string|null
     */
    protected $defaultDomain = null;

    /**
     * DomainDefaultService constructor.
     *
     * @param $config
     */
    public function __construct(
        $config
    ) {
        $this->setDefaultDomainName($config);
    }

    /**
     * setDefaultDomainName
     *
     * @param array $config
     *
     * @return void
     */
    protected function setDefaultDomainName($config)
    {
        if (!empty($config['Rcm']['defaultDomain'])) {
            $this->defaultDomain = $config['Rcm']['defaultDomain'];
        }
    }

    /**
     * getDefaultDomainName
     *
     * @return string|null
     */
    public function getDefaultDomainName()
    {
        return $this->defaultDomain;
    }
}
