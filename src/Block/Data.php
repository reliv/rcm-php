<?php

namespace Rcm\Block;

/**
 * @depreicated - THIS IS A DRAFT. DO NOT USE IT YET.
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
