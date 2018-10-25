<?php

namespace Rcm\BlockWrapper\Instance;

use Rcm\Block\InstanceWithData\InstanceWithData;

/**
 * Interface Instance
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface Instance
{
    /**
     * getBlockInstance
     *
     * @return InstanceWithData
     */
    public function getBlockInstance();
}
