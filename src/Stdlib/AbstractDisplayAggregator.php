<?php


namespace Rcm\Stdlib;

use Rcm\Entity\DisplayInterface;


/**
 * Class AbstractDisplayAggregator
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Stdlib
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractDisplayAggregator implements \IteratorAggregate
{
    /**
     * @var array List of current displayable items
     */
    protected $items = [];

    /**
     * @var int Total items that need to be displayed
     */
    protected $displayCount;

    /**
     * @var int Total items need to be in the list
     */
    protected $requiredCount;

    /**
     * @param array $items
     * @param int   $requiredCount
     * @param int   $displayCount
     */
    public function __construct(
        $items,
        $requiredCount,
        $displayCount
    ) {
        $this->addItems($items);
        $this->setRequiredCount($requiredCount);
        $this->setDisplayCount($displayCount);
    }

    /**
     * setRequiredCount
     *
     * @param $requiredCount
     *
     * @return void
     */
    public function setRequiredCount($requiredCount)
    {
        $requiredCount = (int)$requiredCount;

        $this->requiredCount = $requiredCount;
    }

    /**
     * setDisplayCount
     *
     * @param $displayCount
     *
     * @return void
     */
    public function setDisplayCount($displayCount)
    {
        $displayCount = (int)$displayCount;

        $this->displayCount = $displayCount;
    }

    /**
     * getItems
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * getRequiredNumber
     *
     * @return int
     */
    public function getRequiredNumber()
    {
        $count = $this->requiredCount - count($this->items);

        // if we have more than we need, then we require 0
        if ($count < 0) {
            $count = 0;
        }

        return $count;
    }

    /**
     * addItems
     *
     * @param $items
     *
     * @return void
     */
    public function addItems($items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * addItem
     *
     * @param DisplayInterface $item
     *
     * @return void
     */
    public function addItem(DisplayInterface $item)
    {
        $this->items[] = $item;
    }

    /**
     * getDisplayList - Return a prepared list of display objects
     *
     * @return array
     */
    public function getDisplayList()
    {
        $this->prepareList();

        return $this->items;
    }

    /**
     * prepareList
     *
     * @return void
     */
    protected function prepareList()
    {
        $displayCount = 0;

        foreach ($this->items as &$item) {

            $displayCount++;
            // @todo this should not ignore the items current display state
            // - Should check the current display state and try to keep it if possible
            if ($displayCount > $this->displayCount) {
                $item->setDisplay(0);
            } else {
                $item->setDisplay(1);
            }
        }
    }

    /**
     * getIterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $items = $this->getDisplayList();
        return new \ArrayIterator($items);
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getIterator()->getArrayCopy();
    }

}