<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\ResourceName;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\View\Model\JsonModel;

/**
 * Class ApiAdminPageTypesController
 *
 * ApiAdminPageTypesController
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

class ApiAdminPageTypesController extends ApiAdminBaseController
{

    /**
     * getList of available page types
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        /** @var RcmUserService $rcmUserService */
        $rcmUserService = $this->serviceLocator->get(RcmUserService::class);

        //ACCESS CHECK
        if (!$rcmUserService->isAllowed(
            ResourceName::RESOURCE_SITES,
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        $config = $this->getConfig();

        $pageTypes = $config['Rcm']['pageTypes'];

        return new ApiJsonModel($pageTypes, 0, 'Success');
    }
}
