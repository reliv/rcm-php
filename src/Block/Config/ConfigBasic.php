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
     * The array is a mix of associative and indexed values, although the
     * order of indexed values is irrelevant. When a value is indexed, it must
     * be a string. When it is associative, it must be either an array or a
     * boolean. Each string, whether it is a key or a value, correlates with a
     * key in the block config.
     *
     * If a string appears as an indexed value, the correlating key in the block
     * config, including all fields below it, are whitelisted.
     *
     * If a string appears as a key, and the value is a boolean, it indicates
     * whether the associated config is whitelisted or not.
     *
     * If a string appears as a key, and the value is an array, this indicates
     * a more specific whitelist rule for sub-keys of the associated config
     * item. Whitelisting rules then proceed recursively.
     *
     * By default, all fields are NOT whitelisted, and no config will be
     * encoded to JSON.
     *
     * @example
     *
     * [
     *      'foo',
     *      'bar' => true,
     *      'baz' => [
     *          'flip' => true,
     *          'flop' => [
     *              ...
     *          ],
     *          'quux',
     *      ]
     * ]
     *
     * @return array
     */
    public function getConfigJsonWhitelist()
    {
        return $this->get(ConfigFields::CONFIG_JSON_WHITELIST);
    }

    /**
     * Get JSON-whitelisted config values from a block instance.
     *
     * @param Instance $instance
     * @return array
     */
    public function getInstanceJsonWhitelistedConfig(Instance $instance)
    {
        $wconf = $this->getConfigJsonWhitelist();
        $iconf = $instance->getConfig();
        return $this->getInstanceWhitelistConfigRecurse($wconf, $iconf, []);
    }

    private function getInstanceWhitelistConfigRecurse(array $wconf, array $iconf, array $keyPath)
    {
        $oconf = [];
        foreach ($wconf as $key => $val) {
            if (is_int($key)) {
                if (!is_string($val)) {
                    $path = '"' . implode('" => "', $keyPath) . '"';
                    $m = "{$this->getName()}: \""
                        . ConfigFields::CONFIG_JSON_WHITELIST
                        . "\" => $m: All indexed values must be strings";
                }
                $key = $val;
            }
            if (is_string($val) || (is_bool($val) && $val)) {
                if (array_key_exists($key, $iconf)) {
                    $oconf[$key] = $iconf[$key];
                }
            } elseif (is_array($val)) {
                if (array_key_exists($key, $iconf)) {
                    $oconf[$key] =
                        $this->getInstanceWhitelistConfigRecurse($val, $iconf[$key], array_merge($keyPath, [$key]));
                }
            } else {
                $path = '"' . implode('" => "', array_merge($keyPath, [$key])) . '"';
                $m = "{$this->getName()}: \""
                    . ConfigFields::CONFIG_JSON_WHITELIST
                    . "\" => $m: Value must be string, bool, or array";
                throw new InvalidPluginException($m);
            }
        }
        return $oconf;
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
