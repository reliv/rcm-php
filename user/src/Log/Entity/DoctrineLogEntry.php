<?php

namespace RcmUser\Log\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DoctrineLogEntry
 *
 * DoctrineLogEntry
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
 * @ORM\Entity
 * @ORM\Table(name="rcm_user_log")
 */
class DoctrineLogEntry extends LogEntry
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $type = 'INFO';

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    protected $message = '';

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $extra = '';

    /**
     * @var string $dateTimeUtc dateTimeUtc
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $dateTimeUtc = '';
}
