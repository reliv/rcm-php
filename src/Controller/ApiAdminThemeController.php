<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\ResourceName;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

/**
 * Class ApiAdminThemeController
 *
 * API for Rcm Theme
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
class ApiAdminThemeController extends ApiAdminBaseController
{

    /**
     * getThemesConfig
     *
     * @return array
     */
    protected function getThemesConfig()
    {
        $config = $this->serviceLocator->get('config');

        $myConfig = $config['Rcm'];

        if (empty($myConfig['themes'])) {
            return [];
        }

        return $myConfig['themes'];
    }

    /**
     * getList
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

        $themes = $this->getThemesConfig();

        return new ApiJsonModel($themes, 0, 'Success');
    }
}
