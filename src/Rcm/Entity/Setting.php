<?php
/**
 * General Config Setting Entity.
 *
 * This is a Doctrine 2 definition file for Config Settings.  This file
 * is used for any module that needs to know about general settings.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * General Config Setting Entity.
 *
 * This object is used to store general settings for the CMS.  This is a simple
 * key/value object.  This is not used by the Core system but is utilized by
 * some plugins.  Please note that this is intended to be used for global system
 * settings.  For most plugins this will not be what you want.  Use sparingly.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_setting")
 */
class Setting
{
    /**
     * @var string name
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var int Owners account number
     *
     * @ORM\Column(type="text")
     */
    protected $value;

    /**
     * Set the key for the setting
     *
     * @param string $name Name for setting
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the key name of the setting
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the Value for the setting
     *
     * @param int $value Setting
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the Value for the setting
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
