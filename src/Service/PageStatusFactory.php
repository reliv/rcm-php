<?php

namespace Rcm\Service;

use Interop\Container\ContainerInterface;

/**
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
