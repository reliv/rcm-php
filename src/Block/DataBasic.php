<?php

namespace Rcm\Block;

/**
 * @depreicated - THIS IS A DRAFT. DO NOT USE IT YET.
 *
 * Interface BlockInstance
 * @package     Rcm\Entity
 */
class DataBasic extends InstanceBasic implements Data
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Constructor.
     *
     * @param string $id
     * @param string $config
     * @param array  $name
     * @param array  $data
     */
    public function __construct($id, $config, $name, $data = [])
    {
        $this->data = $data;
        parent::__construct($id, $config, $name);
    }

    /**
     * @return array Any custom data that was delivered by a registered block data service. this is optional
     */
    public function getData()
    {
        return $this->data;
    }
}
