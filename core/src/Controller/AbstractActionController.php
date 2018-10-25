<?php

namespace Rcm\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractActionController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class AbstractActionController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * InstanceConfigApiController constructor.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * getServiceLocator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
