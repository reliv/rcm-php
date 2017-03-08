<?php

namespace Rcm\Block\Instance;

use Rcm\Core\Repository\Repository;

/**
 * Interface InstanceRepository
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface InstanceRepository extends Repository
{
    /**
     * @param int $id
     * @return Instance|null
     */
    public function findById($id);
}
