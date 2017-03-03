<?php

namespace Rcm\Block\DataProvider;

/**
 * @GammaRelease
 * Interface DataProviderRepository
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface DataProviderRepository
{
    /**
     * findByName
     *
     * @param $blockName
     *
     * @return DataProvider
     */
    public function findByName($blockName);
}
