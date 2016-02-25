<?php

namespace Rcm\Controller;

use Rcm\View\Model\ApiJsonModel;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @deprecated Use Reliv\RcmApiLib controller
 * Class AbstractRestfulJsonController
 *
 * ZF2 AbstractRestfulController returns arrays for missing methods
 * This allows proper responses to be returned
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractRestfulJsonController extends AbstractRestfulController
{
    /**
     * methodNotAllowed
     *
     * @return ApiJsonModel
     */
    protected function methodNotAllowed()
    {
        $this->response->setStatusCode(405);

        return new ApiJsonModel(null, 405, 'Method Not Allowed');
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $data
     *
     * @return ApiJsonModel
     */
    public function create($data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $id
     *
     * @return ApiJsonModel
     */
    public function delete($id)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     * @todo Implement after Zend Framework 2.4 is implemented
     *
     * @param $data
     *
     * @return ApiJsonModel
     *
    public function deleteList($data)
    {
        return $this->methodNotAllowed();
    }
     */

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $id
     *
     * @return ApiJsonModel
     */
    public function get($id)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @return ApiJsonModel
     */
    public function getList()
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param null $id
     *
     * @return ApiJsonModel
     */
    public function head($id = null)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @return ApiJsonModel
     */
    public function options()
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $id
     * @param $data
     *
     * @return ApiJsonModel
     */
    public function patch($id, $data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $data
     *
     * @return ApiJsonModel
     */
    public function replaceList($data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $data
     *
     * @return ApiJsonModel
     */
    public function patchList($data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $id
     * @param $data
     *
     * @return ApiJsonModel
     */
    public function update($id, $data)
    {
        return $this->methodNotAllowed();
    }
}
