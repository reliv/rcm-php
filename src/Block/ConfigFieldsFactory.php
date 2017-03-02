<?php

namespace Rcm\Block;

use Interop\Container\ContainerInterface;

/**
 * Class ConfigFieldsFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigFieldsFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return ConfigFields
     */
    public function __invoke($container)
    {
        return new ConfigFields();
    }
}
