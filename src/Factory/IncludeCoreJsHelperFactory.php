<?php

namespace Rcm\Factory;

use Rcm\View\Helper\IncludeCoreJs;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IncludeCoreJsHelperFactory
 *
 * IncludeCoreJsHelperFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class IncludeCoreJsHelperFactory implements FactoryInterface
{

    /**
     * createService
     *
     * @param ServiceLocatorInterface $viewServiceManager
     *
     * @return IncludeCoreJs
     */
    public function createService(ServiceLocatorInterface $viewServiceManager)
    {
        /** @var \Zend\View\HelperPluginManager $viewManager */
        $viewManager = $viewServiceManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $viewManager->getServiceLocator();

        $translator = $serviceLocator->get('MvcTranslator');

        return new IncludeCoreJs($translator);
    }
}
