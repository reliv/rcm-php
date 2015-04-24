<?php


namespace Rcm\Service;


/**
 * Class DisplayCountService
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class DisplayCountService
{
    /**
     * getRequiredCount
     *
     * @param $validCounts
     *
     * @return mixed
     */
    public function getRequiredCount($validCounts)
    {
        sort($validCounts);

        return $validCounts[(count($validCounts) - 1)];
    }

    /**
     * getValidDisplayCount
     *
     * @param array $items [\Rcm\Entity\DisplayInterface]
     * @param array $validCounts e.g. [0,1,2,3,4,8]
     *
     * @return int|mixed
     */
    public function getValidDisplayCount(
        $items,
        $validCounts
    ) {
        sort($validCounts);

        $displayCount = 0;

        /**
         * @var mixed $key
         * @var \Rcm\Entity\DisplayInterface $item
         */
        foreach ($items as $key => $item) {

            if ($item->canDisplay()) {
                $displayCount++;
            }
        }

        if (in_array($displayCount, $validCounts)) {
            return $displayCount;
        }

        $min = $validCounts[0];

        if ($displayCount < $min) {
            return $min;
        }

        $max = $this->getRequiredCount($validCounts);

        if ($displayCount > $max) {
            return $max;
        }

        // figure out where it is in the list
        $prevCount = $min;

        foreach ($validCounts as $currCount) {
            if ($displayCount > $prevCount && $displayCount < $currCount) {
                return $prevCount;
            }

            $prevCount = $currCount;
        }

        // fall back - not reachable
        return $displayCount;
    }
}