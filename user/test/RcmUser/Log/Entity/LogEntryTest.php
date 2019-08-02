<?php
/**
 * LogEntryTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Log\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Log\Entity;

use RcmUser\Log\Entity\LogEntry;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class LogEntryTest
 *
 * LogEntryTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Log\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Log\Entity\LogEntry
 */
class LogEntryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $tz = new \DateTimeZone('UTC');
        $dateTimeUtc = new \DateTime('now', $tz);
        $type = 'ERR';
        $message = 'testmessage';
        $extra = json_encode(['test', 'extra']);


        $entry = new LogEntry($dateTimeUtc, $type, $message, $extra);

        $this->assertEquals(
            $dateTimeUtc,
            $entry->getDateTimeUtc()
        );

        $this->assertEquals(
            $type,
            $entry->getType()
        );

        $this->assertEquals(
            $message,
            $entry->getMessage()
        );

        $this->assertEquals(
            $extra,
            $entry->getExtra()
        );

        $entry->setExtra(null);

        $this->assertEquals(
            '',
            $entry->getExtra()
        );
    }
}
