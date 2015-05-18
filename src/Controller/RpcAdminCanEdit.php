<?php


namespace RcmAdmin\Controller;

use Rcm\View\Model\ApiJsonModel;
use Zend\Http\Response;

/**
 * Class RpcAdminCanEdit
 *
 * RpcAdminCanEdit
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class RpcAdminCanEdit extends ApiAdminBaseController
{

    /**
     * create
     *
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        $result = $this->rcmIsAllowed('sites', 'admin');

        return new ApiJsonModel(['canEdit' => $result]);
    }
}
