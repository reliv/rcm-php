<?php

namespace RcmUser\User\Entity;

/**
 * Class Links
 *
 * Links
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Links implements \JsonSerializable, \IteratorAggregate
{

    /**
     * @var array $links List of links
     */
    protected $links = [];

    /**
     * __construct
     *
     * @param array $links List of links
     */
    public function __construct($links = [])
    {
        $this->links = $links;
    }

    /**
     * getLinks
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * addLink
     *
     * @param Link $link link object
     *
     * @return void
     */
    public function addLink(Link $link)
    {
        $this->links[] = $link;
    }

    /**
     * getIterator
     *
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        $links = $this->getLinks();

        return new \ArrayIterator($links);
    }

    /**
     * jsonSerialize
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->links;
    }
}
