<?php

namespace Rcm\Factory;

use Rcm\View\Helper\RcmEdit;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RichEditFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfig\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RichEditFactory
{
    /**
     * Creates this service
     *
     * @param ServiceLocatorInterface $serviceLocator zf2 service locator
     *
     * @return RcmEdit
     */
    public function __invoke($serviceLocator)
    {
        return new RcmEdit(
            $serviceLocator->getServiceLocator()->get('RcmHtmlPurifier'),
            true
        );
    }
}
