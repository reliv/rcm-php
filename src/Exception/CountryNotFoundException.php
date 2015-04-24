<?php

namespace Rcm\Exception;

use Rcm\Exception\ExceptionInterface as RcmExceptionInterface;

/**
 * Rcm Country not found exception
 *
 * Is thrown when a Country is not found
 *
 * @category  Reliv
 * @package   Rcm\Exception
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class CountryNotFoundException extends \InvalidArgumentException implements RcmExceptionInterface
{

}
