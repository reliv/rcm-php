<?php


namespace Rcm\Entity;


/**
 * interface ApiBase
 *
 * Rcm Standard Api Base class
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

class ApiBase implements ApiInterface
{

    /**
     * populate
     *
     * @param $data
     *
     * @return void
     */
    public function populate($data, $ignore = [])
    {
        $setterPrefix = 'set';

        foreach ($data as $property => $value) {

            // Check for ignore keys
            if(in_array($property, $ignore)){
                continue;
            }

            $setter = $setterPrefix . ucfirst($property);

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    /**
     * populateFromObject
     *
     * @param ApiInterface $object Should be of the same type as $this
     *
     * @return void
     */
    public function populateFromObject(ApiInterface $object)
    {
        if ($object instanceof ApiBase) {
            $this->populate($object->toArray());
        }
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * getIterator
     *
     * @return array|Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }
} 