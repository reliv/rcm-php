<?php

namespace Rcm\Block;

/**
 * Class ConfigMap
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https =>//github.com/jerv13
 */
class ConfigMap
{
    protected $fields
        = [
            'name' => '',
            'category' => '',
            'label' => '',
            'description' => '',
            'renderer' => '',
            'data-provider' => '',
            'icon' => '',
            'cache' => true,
            'fields' => [],
            'config' => []
        ];

    protected $fieldsBc
        = [
            'name' => '',
            'category' => '',
            'label' => '',
            'description' => '',
            'renderer' => '',
            'data-provider' => '',
            'icon' => '',
            'cache' => true,
            'fields' => [],
            'config' => []
        ];

    /**
     * getFieldBc
     *
     * @param $field
     *
     * @return mixed|null
     */
    public function getFieldBc($field)
    {
        if (array_key_exists($field, $this->fieldsBc)) {
            return $this->fieldsBc[$field]
        }

        return null;
    }
}
