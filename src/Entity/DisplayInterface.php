<?php


namespace Rcm\Entity;


 /**
 * Class DisplayInterface
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

interface DisplayInterface {

    /**
     * getDisplay
     *
     * @return bool
     */
    public function canDisplay();

    /**
     * setDisplay
     *
     * @param bool $canDisplay
     *
     * @return mixed
     */
    public function setDisplay($canDisplay);

}