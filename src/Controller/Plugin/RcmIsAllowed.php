<?php
/**
 * RcmIsAllowed.php
 *
 * RcmIsAllowed
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace Rcm\Controller\Plugin;

use RcmUser\Controller\Plugin\RcmUserIsAllowed;

/**
 * RcmIsAllowed
 *
 * RcmIsAllowed
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmIsAllowed extends RcmUserIsAllowed
{
    /**
     * __invoke
     *
     * @param string $resourceId resourceId
     * @param string $privilege  privilege
     * @param string $providerId providerId
     *
     * @return bool
     */
    public function __invoke(
        $resourceId,
        $privilege = null,
        $providerId = 'Rcm\Acl\ResourceProvider'
    ) {
        return parent::__invoke(
            $resourceId,
            $privilege,
            $providerId
        );
    }
}
