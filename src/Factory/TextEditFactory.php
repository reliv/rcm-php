<?php

namespace Rcm\Factory;

use Rcm\View\Helper\RcmEdit;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * TextEditFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfig\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2018 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class TextEditFactory
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
            false
        );
    }
}
