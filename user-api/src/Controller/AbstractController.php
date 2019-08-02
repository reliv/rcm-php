<?php

namespace RcmUser\Api\Controller;

use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\Result;
use Reliv\RcmApiLib\Controller\AbstractRestfulJsonController;
use Zend\Http\Response;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Api\ApiController
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AbstractController extends AbstractRestfulJsonController
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * AbstractController constructor.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * getRcmUserService
     *
     * @return \RcmUser\Service\RcmUserService
     */
    protected function getRcmUserService()
    {
        return $this->serviceLocator->get(
            'RcmUser\Service\RcmUserService'
        );
    }

    /**
     * isAllowed
     *
     * @param string $resourceId resourceId
     * @param string $privilege  privilege
     *
     * @return mixed
     */
    public function isAllowed(
        $resourceId = RcmUserAclResourceProvider::RESOURCE_ID_ROOT,
        $privilege = null
    ) {
        return $this->getRcmUserService()->isAllowed(
            $resourceId,
            $privilege,
            RcmUserAclResourceProvider::PROVIDER_ID
        );
    }

    /**
     * getNotAllowedResponse
     *
     * @return mixed
     */
    public function getNotAllowedResponse()
    {
        $response = $this->getResponse();
        $response->setStatusCode(Response::STATUS_CODE_401);
        $result = new Result(
            null,
            Result::CODE_FAIL,
            $response->renderStatusLine()
        );

        return $this->getJsonResponse($result);
    }

    /**
     * getExceptionResponse @todo Return generic message and log exception
     *
     * @param \Exception $e e
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function getExceptionResponse(\Exception $e)
    {
        $result = new Result(
            null,
            Result::CODE_FAIL,
            "Code: " . $e->getCode() . ": " . $e->getMessage()
        );

        /*
        . " | " .$e->getFile() .
         ":" . $e->getLine() .
         " | " . $e->getTraceAsString()
        */

        return $this->getJsonResponse($result);
    }

    /**
     * getJsonResponse
     *
     * @param Result $result result
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function getJsonResponse($result)
    {
        $response = new Response();

        $json = json_encode($result);

        $response->setContent($json);

        $response->getHeaders()->addHeaders(
            [
                'Content-Type' => 'application/json'
            ]
        );

        return $response;
    }
}
