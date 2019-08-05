<?php

namespace RcmLogin\Factory;

use RcmLogin\Email\DefaultMailer;
use RcmLogin\Validator\RedirectValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DefaultMailerFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmLogin\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class DefaultMailerFactory implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RedirectValidator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mailer = new DefaultMailer();

        return $mailer;
    }
}
