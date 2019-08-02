<?php

namespace RcmUser\Log;

use Doctrine\ORM\EntityManager;
use RcmUser\Log\Entity\DoctrineLogEntry;
use Zend\Log\LoggerInterface;

/**
 * Class DoctrineLogger
 *
 * DoctrineLogger
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Log
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class DoctrineLogger extends AbstractLogger implements Logger
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * __construct
     *
     * @param EntityManager $entityManager entityManager
     * @param int           $logLevel      logLevel
     */
    public function __construct(
        EntityManager $entityManager,
        $logLevel = \Zend\Log\Logger::ERR
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($logLevel);
    }

    /**
     * log
     *
     * @param string $type    type
     * @param string $message message
     * @param array  $extra   extra
     *
     * @return LoggerInterface
     */
    public function log(
        $type,
        $message,
        $extra = []
    ) {
        if (!$this->canLog($type)) {
            return $this;
        }
        $tz = new \DateTimeZone('UTC');
        $dateTimeUtc = new \DateTime('now', $tz);
        $type = strtoupper($type);
        $extra = json_encode($extra);

        $logEntry = new DoctrineLogEntry($dateTimeUtc, $type, $message, $extra);

        $this->entityManager->persist($logEntry);
        $this->entityManager->flush();

        return $this;
    }
}
