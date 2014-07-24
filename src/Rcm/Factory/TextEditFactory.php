<?php
/**
 * TextEditFactory
 *
 * TextEditFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfig\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Factory;


use Rcm\View\Helper\RcmEdit;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * TextEditFactory
 *
 * LongDescHere
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
class TextEditFactory implements FactoryInterface
{
    /**
     * Creates this service
     *
     * @param ServiceLocatorInterface $serviceLocator zf2 service locator
     *
     * @return RcmEdit
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RcmEdit(
            $serviceLocator->getServiceLocator()->get('RcmHtmlPurifier'),
            false
        );
    }
}
