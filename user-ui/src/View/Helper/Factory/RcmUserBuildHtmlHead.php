<?php

namespace RcmUser\Ui\View\Helper\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RcmUserBuildHtmlHead
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Ui\View\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserBuildHtmlHead implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return mixed|RcmUserBuildHtmlHead
     */
    public function createService(ServiceLocatorInterface $mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();
        $rcmUserHtmlService = $serviceLocator->get(
            'RcmUser\Ui\Service\RcmUserHtmlService'
        );
        $service = new \RcmUser\Ui\View\Helper\RcmUserBuildHtmlHead(
            $rcmUserHtmlService
        );

        return $service;
    }
}
