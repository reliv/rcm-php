<?php

namespace Rcm\Entity;

/**
 * @todo FUTURE
 * Interface BlockInstance
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface BlockInstance
{
    /**
     * getId
     *
     * @return int
     */
    public function getId();

    /**
     * getConfig
     *
     * @return array
     */
    public function getConfig();
}
