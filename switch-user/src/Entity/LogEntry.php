<?php

namespace Rcm\SwitchUser\Entity;

use Doctrine\ORM\Mapping as ORM;
use Reliv\RcmApiLib\Model\AbstractApiModel;

/**
 * Class LogEntry Switch User Log Entry
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_switch_user_log")
 */
class LogEntry extends AbstractApiModel
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $adminUserId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $targetUserId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $action;

    /**
     * @var string
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $actionSuccess;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $dateTime;

    /**
     * @param string $adminUserId
     * @param string $targetUserId
     * @param string $action
     */
    public function __construct($adminUserId, $targetUserId, $action, $actionSuccess)
    {
        $this->adminUserId = (string)$adminUserId;
        $this->targetUserId = (string)$targetUserId;
        $this->action = (string)$action;
        $this->actionSuccess = (bool) $actionSuccess;
        $this->dateTime = new \DateTime();
    }

    /**
     * getAdminUserId
     *
     * @return string
     */
    public function getAdminUserId()
    {
        return $this->adminUserId;
    }

    /**
     * getTargetUserId
     *
     * @return string
     */
    public function getTargetUserId()
    {
        return $this->targetUserId;
    }

    /**
     * getAction
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * getActionSuccess
     *
     * @return bool
     */
    public function getActionSuccess()
    {
        return $this->actionSuccess;
    }

    /**
     * getDateTime
     *
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * setDateTime
     *
     * @param \DateTime $dateTime
     *
     * @return void
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * setEndDateString
     *
     * @param string $format
     *
     * @return null|string
     */
    public function getDateTimeString($format = 'Y-m-d H:i:s')
    {
        if (empty($this->dateTime)) {
            return null;
        }

        return $this->dateTime->format($format);
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = ['dateTime'])
    {
        $array = parent::toArray($ignore);

        if (!in_array('dateTimeString', $ignore)) {
            $array['dateTimeString'] = $this->getDateTimeString();
        }

        return $array;
    }
}
