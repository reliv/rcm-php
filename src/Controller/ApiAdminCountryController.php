<?php


namespace RcmAdmin\Controller;

use Rcm\View\Model\ApiJsonModel;
use Zend\Http\Response;

/**
 * Class ApiAdminCountryController
 *
 * LongDescHere
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

class ApiAdminCountryController extends ApiAdminBaseController
{

    /**
     * getList
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

        /** @var \Rcm\Repository\Country $repo */
        $repo = $this->getEntityManager()->getRepository('\Rcm\Entity\Country');

        try {
            $results = $repo->findBy([], ['countryName' => 'ASC']);
        } catch (\Exception $e) {
            return new ApiJsonModel(null, 1, 'An error occurred will getting countries.');
        }

        return new ApiJsonModel($results, 0, 'Success');
    }
}
