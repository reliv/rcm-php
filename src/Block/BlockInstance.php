<?php

namespace Rcm\Block;

/**
 * @depreicated - THIS IS A DRAFT. DO NOT USE IT YET.
 *
 * Interface BlockInstance
 * @package Rcm\Entity
 */
interface BlockInstance
{
    /**
     * @return integer The instance id
     */
    public function getId();

    /**
     * @return array The instance config for this block instance. This is what admins can edit in the CMS
     */
    public function getConfig();

    /**
     * @return array Any custom data that was delivered by a registered block data service. this is optional
     */
    public function getData();
}
