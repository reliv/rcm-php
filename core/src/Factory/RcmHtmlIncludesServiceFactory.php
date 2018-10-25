<?php

namespace Rcm\Factory;

use Rcm\Service\HtmlIncludes;
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
class RcmHtmlIncludesServiceFactory
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return HtmlIncludes
     */
    public function __invoke($serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        return new HtmlIncludes($config['Rcm']['HtmlIncludes']);
    }
}
