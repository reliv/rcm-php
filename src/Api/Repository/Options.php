<?php

namespace Rcm\Api\Repository;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Options
{
    /**
     * @param array $options
     * @param       $key
     * @param null  $default
     *
     * @return mixed|null
     */
    public static function get(
        array $options,
        $key,
        $default = null
    ) {
        return (array_key_exists($key, $options) ? $options[$key] : $default);
    }
}
