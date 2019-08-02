<?php

namespace RcmUser\Ui\Factory;

use RcmUser\Ui\Service\RcmUserHtmlService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RcmUserHtmlServiceFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class RcmUserHtmlServiceFactory implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RcmUserHtmlService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(
            'Config'
        );

        $service = new RcmUserHtmlService($config);

        return $service;
    }
}
