<?php

/**
 * Country State Entity
 *
 * This table Contains a list of states for each country
 *
 * @category  Reliv
 * @package   Rcm\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 */

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country State Entity
 *
 * This table Contains a list of states for each country
 *
 * @category  Reliv
 * @package   Rcm\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 * 
 * @ORM\Entity
 * @ORM\Table(name="rcm_states")
 */

class State
{
    /**
     * @var string ISO Three Char Country Code
     *
     * @link http://en.wikipedia.org/wiki/ISO_3166-1 ISO Standard
     *  
     * @ORM\Column(type="string", length=3)
     * @ORM\Id
     */
    protected $country;

    /**
     * Sets the Country property
     *
     * @param string $country
     *
     * @return null
     *
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Gets the Country property
     *
     * @return string Country
     *
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the State property
     *
     * @param string $state
     *
     * @return null
     *
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Gets the State property
     *
     * @return string State
     *
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @var string State/Province code
     *
     * @ORM\Column(type="string")
     * @ORM\Id
     */
    protected $state;
}