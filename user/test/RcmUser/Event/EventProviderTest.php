<?php
/**
 * EventProviderTest.php
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Event;

use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../Zf2TestCase.php';

/**
 * Class EventProviderTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @covers \RcmUser\Event\EventProvider
 */
class EventProviderTest extends Zf2TestCase
{

    public $eventProvider;
    public $eventManager;

    public function getEventProvider()
    {
        if (!isset($this->eventProvider)) {
            $this->buildEventProvider();
        }

        return $this->eventProvider;
    }

    public function buildEventProvider()
    {
        $this->eventManager = $this->getMockBuilder(
            \Zend\EventManager\EventManagerInterface::class
        )
            ->disableOriginalConstructor()
            ->getMock();
        $stub = $this->getMockForAbstractClass(
            \RcmUser\Event\EventProvider::class,
            [$this->eventManager]
        );
        $this->eventProvider = $stub;
    }

    public function testSetEventManager()
    {
        $result = $this->getEventProvider()->setEventManager(
            $this->eventManager
        );

        $this->assertEquals(
            $this->getEventProvider(),
            $result,
            'Did not return proper value.'
        );
    }

    public function testGetEventManager()
    {
        $result = $this->getEventProvider()->getEventManager();

        $this->assertInstanceOf(
            '\Zend\EventManager\EventManagerInterface',
            $result,
            'Did not return EventManagerInterface'
        );
    }
}
