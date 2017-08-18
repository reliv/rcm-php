<?php

namespace Rcm\Factory;

use Rcm\View\Helper\RcmHtmlIncludes;
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
class RcmHtmlIncludesHelperFactory
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $viewServiceManager
     *
     * @return RcmHtmlIncludes
     */
    public function __invoke($viewServiceManager)
    {
        /** @var \Zend\View\HelperPluginManager $viewManager */
        $viewManager = $viewServiceManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $viewManager->getServiceLocator();

        $htmlIncludesService = $serviceLocator->get(\Rcm\Service\HtmlIncludes::class);

        return new RcmHtmlIncludes($htmlIncludesService);
    }
}
