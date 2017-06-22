<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rcm\Tracking\Model\Tracking;

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
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_setting")
 */
class Setting extends ApiModelTrackingAbstract implements Tracking
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
     * <tracking>
     * @var \DateTime Date object was first created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdDate;

    /**
     * <tracking>
     * @var string User ID of creator
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $createdByUserId;

    /**
     * <tracking>
     * @var string Short description of create reason
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $createdReason = Tracking::UNKNOWN_REASON;

    /**
     * <tracking>
     * @var \DateTime Date object was modified
     *
     * @ORM\Column(type="datetime")
     */
    protected $modifiedDate;

    /**
     * <tracking>
     * @var string User ID of modifier
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $modifiedByUserId;

    /**
     * <tracking>
     * @var string Short description of create reason
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $modifiedReason = Tracking::UNKNOWN_REASON;

    /**
     * @param string $createdByUserId <tracking>
     * @param string $createdReason   <tracking>
     */
    public function __construct(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        parent::__construct($createdByUserId, $createdReason);
    }

    /**
     * @return void
     */
    public function __clone()
    {
        parent::__clone();
    }

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

    /**
     * <tracking>
     * @return void
     *
     * @ORM\PrePersist
     */
    public function assertHasTrackingData()
    {
        parent::assertHasTrackingData();
    }

    /**
     * <tracking>
     * @return void
     *
     * @ORM\PreUpdate
     */
    public function assertHasNewModifiedData()
    {
        parent::assertHasNewModifiedData();
    }
}
