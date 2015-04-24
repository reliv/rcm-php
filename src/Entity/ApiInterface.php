<?php


namespace Rcm\Entity;


 /**
 * interface ApiInterface
 *
 * Rcm Standard Api Interface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

interface ApiInterface extends \JsonSerializable, \IteratorAggregate   {

    /**
     * populate
     *
     * @param $data
     *
     * @return void
     */
    public function populate($data);

    /**
     * populate
     *
     * @param ApiInterface $object - Should be of the same type as $this
     *
     * @return void
     */
    public function populateFromObject(ApiInterface $object);

    /**
     * toArray
     *
     * @return array
     */
    public function toArray();

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize();

    /**
     * getIterator
     *
     * @return array|Traversable
     */
    public function getIterator();


} 