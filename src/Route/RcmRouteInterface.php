<?php
/**
 * Rcm Route Interface
 *
 * This interface is used to identify RCM/CMS routes from normal Zend Routes.  This allows the
 * CMS to stay out of the way and not break existing applications.
 *
 * PHP version 5.5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <shafer_w2002@yahoo.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace Rcm\Route;

use Zend\Mvc\Router\Http\RouteInterface;

/**
 * Rcm Route Interface
 *
 * This interface is used to identify RCM/CMS routes from normal Zend Routes.  This allows the
 * CMS to stay out of the way and not break existing applications.
 *
 * PHP version 5.5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <shafer_w2002@yahoo.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface RcmRouteInterface extends RouteInterface
{

}
