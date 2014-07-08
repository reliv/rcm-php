<?php
/**
 * Plugin Data Not Found Exception
 *
 * The Plugin Instance Not Found Exception is used when the system asks for a
 * plugin instance that does not exist
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Exception;

use Rcm\Exception\ExceptionInterface as RcmExceptionInterface;
use Zend\Stdlib\ResponseInterface;

/**
 * Reliv Common's Plugin Data Not Found Exception
 *
 * Reliv Common's Plugin Data Not Found Exception
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PluginReturnedResponseException extends \RuntimeException implements RcmExceptionInterface
{

    /** @var ResponseInterface */
    protected $response;

    /**
     * Get Response passed into exception
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set the response for the exception
     *
     * @param ResponseInterface $response Response object
     *
     * @return void
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }
}
