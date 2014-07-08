<?php
/**
 * Reliv Common's language not found exception
 *
 * Is thrown when a language is not found
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Exception;

use Rcm\Exception\ExceptionInterface as RcmExceptionInterface;

/**
 * Reliv Common's language not found exception
 *
 * Is thrown when a language is not found
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class LanguageNotFoundException extends \InvalidArgumentException implements RcmExceptionInterface
{

}
