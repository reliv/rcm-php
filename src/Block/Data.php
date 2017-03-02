<?php

namespace Rcm\Block;

/**
 * @GammaRelease
 *
 * Interface BlockInstance
 * @package Rcm\Entity
 */
interface Data extends Instance
{
    /**
     * @return array Any custom data that was delivered by a registered block data service. this is optional
     */
    public function getData();
}
