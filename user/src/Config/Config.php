<?php

namespace RcmUser\Config;

/**
 * Config
 *
 * Config
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Config
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Config
{

    protected $data = [];

    /**
     * __construct
     *
     * @param array $data data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * get
     *
     * @param string $key key
     * @param null   $def def
     *
     * @return null
     */
    public function get(
        $key,
        $def = null
    ) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $def;
    }
}
