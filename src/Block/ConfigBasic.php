<?php

namespace Rcm\Block;

/**
 * @GammaRelease
 * Class ConfigBasic
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigBasic implements Config
{
    protected $config = [];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * get
     *
     * @param string     $key
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        return $default;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * getCategory
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->get('category');
    }

    /**
     * getLabel
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->get('label');
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * getRenderer
     *
     * @return string
     */
    public function getRenderer()
    {
        return $this->get('renderer');
    }

    /**
     * getDataProvider
     *
     * @return string
     */
    public function getDataProvider()
    {
        return $this->get('data-provider');
    }

    /**
     * getIcon (URL)
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->get('icon');
    }

    /**
     * getCache
     *
     * @return bool
     */
    public function getCache()
    {
        return $this->get('cache');
    }

    /**
     * getFields
     *
     * @return array
     */
    public function getFields()
    {
        return $this->get('fields');
    }

    /**
     * getDefaultConfig
     *
     * @return array
     */
    public function getDefaultConfig()
    {
        return $this->get('default-config');
    }
}
