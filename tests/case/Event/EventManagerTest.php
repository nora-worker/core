<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Event;

/**
 * @coversDefaultClass Nora\Core\Event\EventManager
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    private $cnt;

    /**
     * @covers ::create
     * @covers ::observe
     */
    public function testCreate ()
    {
        $this->cnt = 0;
        $em = EventManager::create();
        return $em;
    }

    /**
     * @depends testCreate
     * @covers ::on
     * @covers ::dispatch
     */
    public function testOn ($em)
    {
        $em->on('test', function ( ) {
            $this->assertTrue(false);
            $this->cnt++;
        });
        $em->on('test', function ($ev) {
            $this->cnt++;
            $ev->stopPropagation();
        });
        $em->on('test', function ( ) {
            $this->cnt++;
        });
        $em->dispatch(Event::Create('test'));

        $this->assertEquals(2,$this->cnt);

        $em->dispatch(Event::Create('test', [], $this));
        return $em;
    }





}
