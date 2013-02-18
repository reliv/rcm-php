<?php
    /**
     * Admin User Database Entity
     *
     * This is a Doctorine 2 definition file for Admin User Objects.
     *
     * PHP version 5.3
     *
     * LICENSE: No License yet
     *
     * @category  Reliv
     * @package   Common\Entites
     * @author    Westin Shafer <wshafer@relivinc.com>
     * @copyright 2012 Reliv International
     * @license   License.txt New BSD License
     * @version   GIT: <git_id>
     * @link      http://ci.reliv.com/confluence
     */

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rcm\Entity\Site;
use Rcm\Entity\Page;

/**
 * Admin User Database Entity
 *
 * This contains all the Admin info and permissions
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_admin_plugin_restrictions")
 */

class AdminPluginRestrictions
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $pluginRestrictionId;

    /**
     * @ORM\Column(type="string", nullable=true)
     **/
    protected $restrictedPlugins;


}