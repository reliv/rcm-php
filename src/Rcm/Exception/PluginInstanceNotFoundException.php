<?php
/**
 * Plugin Instance Not Found Exception
 *
 * The Plugin Instance Not Found Exception is used when the system asks for a
 * plugin instance that does not exist
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
 * Plugin Instance Not Found Exception
 *
 * Plugin Instance Not Found Exception
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PluginInstanceNotFoundException extends \RuntimeException implements RcmExceptionInterface
{

}
