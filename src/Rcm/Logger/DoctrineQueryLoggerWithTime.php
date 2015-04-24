<?php


namespace Rcm\Logger;

use Doctrine\DBAL\Logging\SQLLogger;

/**
 * DoctrineQueryLoggerWithTime
 *
 * Prints how long a query took and its SQL. Useful for profiling performance.
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Logger
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 *
 *
 * Example Usage:
 * $serviceLocator->get('Doctrine\ORM\EntityManager')
 * ->getConnection()
 * ->getConfiguration()
 * ->setSQLLogger(new DoctrineQueryLoggerWithTime());
 */
class DoctrineQueryLoggerWithTime implements SQLLogger
{
    protected $queryCount;
    protected $lastStartTime;
    protected $lastSql;
    protected $showTimeAndCount = true;

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->lastStartTime = microtime(true);
        $this->lastSql = $sql;
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {
        $this->queryCount++;
        $echo = '';
        if ($this->showTimeAndCount) {
            $echo .= number_format(
                    (microtime(true) - $this->lastStartTime)
                    * 1000,
                    1
                ) . 'ms #' . $this->queryCount . ' ';
        }
        $echo .= $this->lastSql . "\n\n";
        echo $echo;
    }
}
