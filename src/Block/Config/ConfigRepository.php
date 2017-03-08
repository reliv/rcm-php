<?php

namespace Rcm\Block\Config;

use Rcm\Core\Repository\Repository;

/**
 * Interface ConfigRepository
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface ConfigRepository extends Repository
{
    /**
     * @param int $name
     * @return Config|null
     */
    public function findById($id);
}
