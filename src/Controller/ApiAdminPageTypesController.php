<?php

namespace RcmAdmin\Controller;

use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;

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
        //ACCESS CHECK
        if (!$this->rcmIsAllowed(
            'sites',
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
