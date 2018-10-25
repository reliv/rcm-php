<?php

namespace RcmAdmin\Controller;

use Rcm\View\Model\ApiJsonModel;
use Zend\Http\Response;

/**
 * Class RpcAdminKeepAlive
 *
 * Session Keep Alive
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

class RpcAdminKeepAlive extends ApiAdminBaseController
{

    /**
     * create
     *
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        $dateTime = new \DateTime();

        $result = [
                'responseTime' => $dateTime->getTimestamp(),
                'requestTime' => (float) $data['requestTime']
        ];

        return new ApiJsonModel($result);
    }
}
