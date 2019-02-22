<?php

namespace Rcm\Block\InstanceWithData;

use Rcm\Block\Instance\InstanceBasic;

/**
 * @GammaRelease
 *
 * Interface BlockInstance
 * @package     Rcm\Entity
 */
class InstanceWithDataBasic extends InstanceBasic implements InstanceWithData
{
    /**
     * @var array | null
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param string $id
     * @param string $config
     * @param array  $name
     * @param array | null $data
     */
    public function __construct($id, $name, $config, $data = null)
    {
        $this->data = $data;
        parent::__construct($id, $name, $config);
    }

    /**
     * @return array Any custom data that was delivered by a registered block data service. this is optional
     */
    public function getData()
    {
        return $this->data;
    }
}
