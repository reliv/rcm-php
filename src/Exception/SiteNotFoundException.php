<?php

namespace Rcm\Exception;

use Rcm\Exception\ExceptionInterface as RcmExceptionInterface;

/**
 * Reliv Common's site not found exception
 *
 * Reliv Common's site not found exception Is thrown when a site is not found
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class SiteNotFoundException extends \InvalidArgumentException implements RcmExceptionInterface
{

}
