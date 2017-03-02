<?php

namespace Rcm\Block;

/**
 * @GammaRelease
 * Class Configs
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface Configs
{
    /**
     * get
     *
     * @param string $blockName
     * @param null   $default
     *
     * @return mixed|null
     */
    public function get($blockName, $default = null);

    /**
     * getAll
     *
     * @return array
     */
    public function getAll();
}
