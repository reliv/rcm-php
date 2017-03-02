<?php

namespace Rcm\Block;

use Rcm\Core\Repository\AbstractRepository;
use Rcm\Core\Repository\Repository;

/**
 * @GammaRelease
 * Class ConfigRepositoryBc
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigRepositoryBc extends AbstractRepository implements ConfigRepository
{
    const CACHE_KEY = 'ConfigRepositoryBc';

    /**
     * __invoke
     *
     * @return array
     */
    public function __invoke()
    {
        return [];
    }
}
