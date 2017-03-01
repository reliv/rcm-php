<?php

namespace Rcm\Block;

/**
 * @depreicated - THIS IS A DRAFT. DO NOT USE IT YET.
 *
 * Interface BlockInstance
 * @package Rcm\Entity
 */
interface Instance
{
    /**
     * @return integer The instance id
     */
    public function getId();

    /**
     * getName
     *
     * @return string
     */
    public function getName();

    /**
     * @return array The instance config for this block instance. This is what admins can edit in the CMS
     */
    public function getConfig();
}
