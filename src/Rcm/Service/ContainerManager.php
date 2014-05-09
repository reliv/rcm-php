<?php
/**
 * Container Manager
 *
 * This file contains the class used to manage plugin containers.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Rcm\Exception\ContainerNotFoundException;
use Zend\Cache\Storage\StorageInterface;

/**
 * Plugin Container Manager.
 *
 * The Plugin Container Manager is used to manage containers in the CMS.  These
 * containers can be used by anything that wants to have a container or a set
 * of CMS plugin instances.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class ContainerManager extends ContainerAbstract
{

}