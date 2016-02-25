<?php

namespace Rcm\Factory;

use Rcm\View\Helper\RcmHtmlIncludes;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RcmHtmlIncludesHelperFactory
 *
 * RcmHtmlIncludesHelperFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmHtmlIncludesHelperFactory implements FactoryInterface
{

    /**
     * createService
     *
     * @param ServiceLocatorInterface $viewServiceManager
     *
     * @return RcmHtmlIncludes
     */
    public function createService(ServiceLocatorInterface $viewServiceManager)
    {
        /** @var \Zend\View\HelperPluginManager $viewManager */
        $viewManager = $viewServiceManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $viewManager->getServiceLocator();

        $config = $serviceLocator->get('config');

        return new RcmHtmlIncludes($config['Rcm']['HtmlIncludes']);
    }
}
