<?php

namespace Rcm\Exception;

use Rcm\Exception\ExceptionInterface as RcmExceptionInterface;

/**
 * Reliv Common's Invalid Argument Exception
 *
 * Reliv Common's Invalid Argument Exception
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PageNotFoundException extends \InvalidArgumentException implements RcmExceptionInterface
{

}
