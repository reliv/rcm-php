<?php

namespace Rcm\Block\Config;

use Rcm\Block\Instance;
use Rcm\Exception\InvalidPluginException;

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
    /**
     * @var array
     */
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
        return $this->get(ConfigFields::NAME);
    }

    /**
     * getDirectory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->get(ConfigFields::DIRECTORY);
    }


    /**
     * getCategory
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->get(ConfigFields::CATEGORY);
    }

    /**
     * getLabel
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->get(ConfigFields::LABEL);
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->get(ConfigFields::DIRECTORY);
    }

    /**
     * getRenderer
     *
     * @return string
     */
    public function getRenderer()
    {
        return $this->get(ConfigFields::RENDERER);
    }

    /**
     * getDataProvider
     *
     * @return string
     */
    public function getDataProvider()
    {
        return $this->get(ConfigFields::DATA_PROVIDER);
    }

    /**
     * getIcon (URL)
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->get(ConfigFields::ICON);
    }

    /**
     * getEditor
     *
     * @return string
     */
    public function getEditor()
    {
        return $this->get(ConfigFields::EDITOR);
    }

    /**
     * getCache
     *
     * @return bool
     */
    public function getCache()
    {
        return $this->get(ConfigFields::CACHE);
    }

    /**
     * getFields
     *
     * @return array
     */
    public function getFields()
    {
        return $this->get(ConfigFields::FIELDS);
    }

    /**
     * getDefaultConfig
     *
     * @return array
     */
    public function getDefaultConfig()
    {
        return $this->get(ConfigFields::CONFIG);
    }

    /**
     * Get a manifest of config values that are whitelisted for JSON encoding.
     *
     * This is in WhiteRat format.
     *
     * @return array
     * @see Reliv\WhiteRat
     */
    public function getConfigJsonWhitelist()
    {
        return $this->get(ConfigFields::CONFIG_JSON_WHITELIST);
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return $this->config;
    }
}
