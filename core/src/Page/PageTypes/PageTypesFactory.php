<?php

namespace Rcm\Page\PageTypes;

use Interop\Container\ContainerInterface;

/**
 * @GammaRelease
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
