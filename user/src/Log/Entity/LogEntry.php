<?php

namespace RcmUser\Log\Entity;

/**
 * Class LogEntry
 *
 * LogEntry
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Log\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class LogEntry
{

    /**
     * @var string $type
     */
    protected $type = 'INFO';

    /**
     * @var string $message
     */
    protected $message = '';

    /**
     * @var string $extra
     */
    protected $extra = '';

    /**
     * @var \DateTime $dateTimeUtc dateTimeUtc
     */
    protected $dateTimeUtc = '';

    /**
     * __construct
     *
     * @param \DateTime $dateTimeUtc dateTimeUtc
     * @param string    $type        type
     * @param string    $message     message
     * @param string    $extra       extra
     */
    public function __construct(
        \DateTime $dateTimeUtc,
        $type,
        $message,
        $extra = ''
    ) {
        $this->setDateTimeUtc($dateTimeUtc);
        $this->setType($type);
        $this->setMessage($message);
        $this->setExtra($extra);
    }

    /**
     * setDateTimeUtc
     *
     * @param \DateTime $dateTimeUtc dateTimeUtc
     *
     * @return void
     */
    public function setDateTimeUtc(\DateTime $dateTimeUtc)
    {
        $this->dateTimeUtc = $dateTimeUtc;
    }

    /**
     * getDateTimeUtc
     *
     * @return \DateTime
     */
    public function getDateTimeUtc()
    {
        return $this->dateTimeUtc;
    }

    /**
     * setExtra
     *
     * @param string $extra extra
     *
     * @return void
     */
    public function setExtra($extra)
    {
        if (empty($extra)) {
            $this->extra = '';

            return;
        }

        $this->extra = $extra;
    }

    /**
     * getExtra
     *
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * setMessage
     *
     * @param string $message message
     *
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * setType
     *
     * @param string $type type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
