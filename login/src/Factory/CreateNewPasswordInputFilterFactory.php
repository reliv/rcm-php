<?php


namespace RcmLogin\Factory;

use Interop\Container\ContainerInterface;
use RcmLogin\InputFilter\CreateNewPasswordInputFilter;

/**
 * CreateNewPasswordInputFilterFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmLogin\Factory
 * @author    authorFirstAndLast <author@relivinc.com>
 * @copyright 2017 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class CreateNewPasswordInputFilterFactory
{
    /**
     * @param ContainerInterface $container
     */
    public function __invoke($container)
    {
        return new CreateNewPasswordInputFilter();
    }
}
