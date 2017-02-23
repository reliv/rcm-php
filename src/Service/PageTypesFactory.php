<?php

namespace Rcm\Service;

use Interop\Container\ContainerInterface;

/**
 * Class PageTypesFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageTypesFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('Config');

        return new PageTypes(
            $config['Rcm']['pageTypes']
        );
    }
}
