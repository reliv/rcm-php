<?php

namespace Rcm\Block\InstanceWithData;

use Rcm\Block\Instance\Instance;

/**
 * @GammaRelease
 *
 * Interface InstanceWithData
 * @package Rcm\Entity
 */
interface InstanceWithData extends Instance
{
    /**
     * @return array Any custom data that was delivered by a registered block data service. this is optional
     */
    public function getData();
}
