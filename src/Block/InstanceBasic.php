<?php

namespace Rcm\Block;

/**
 * @GammaRelease
 *
 * Interface BlockInstance
 * @package Rcm\Entity
 */
class InstanceBasic implements Instance
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * Constructor.
     *
     * @param string $id
     * @param string $name
     * @param array  $config
     */
    public function __construct($id, $name, $config)
    {
        $this->id = $id;
        $this->name;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array The instance config for this block instance. This is what admins can edit in the CMS
     */
    public function getConfig()
    {
        return $this->config;
    }
}
