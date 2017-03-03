<?php

namespace Rcm\Page\PageStatus;

use Interop\Container\ContainerInterface;

/**
 * @GammaRelease
 * Class PageStatusFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageStatusFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return PageStatus
     */
    public function __invoke($container)
    {
        $config = $container->get('Config');

        return new PageStatus(
            $config['Rcm']['pageNameStatusMap']
        );
    }
}
