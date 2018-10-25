<?php


namespace RcmAdmin\Controller;

use Rcm\Acl\ResourceName;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

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
 * @copyright 2017 Reliv International
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
        /** @var RcmUserService $rcmUserService */
        $rcmUserService = $this->serviceLocator->get(RcmUserService::class);

        //ACCESS CHECK
        $result = $rcmUserService->isAllowed(
            ResourceName::RESOURCE_SITES,
            'admin'
        );

        return new ApiJsonModel(['canEdit' => $result]);
    }
}
