<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Event;

/**
 * @coversDefaultClass Nora\Core\Event\EventObserver
 */
class EventObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::create
     * @expectedException Nora\Core\Exception\InvalidSpec
     */
    public function testCreate ()
    {
        $listener = EventObserver::create();
        $closure = EventObserver::create(function ( ) { });
        $this->assertEquals($closure, EventObserver::create($closure));

        EventObserver::create(['a']);
    }

    /**
     * @covers ::create
     * @covers Nora\Core\Event\EventListener::_fetchListener
     * @covers Nora\Core\Event\EventListener::on
     * @covers Nora\Core\Event\EventListener::notify
     */
    public function testListener ()
    {
        $listener = EventObserver::create();
        $this->assertInstanceOf(
            __namespace__.'\EventObserverIF',
            $listener
        );
        $this->assertInstanceOf(
            __namespace__.'\EventListener',
            $listener
        );

        $listener->on('test', function ( ) use (&$cnt) {
            $cnt++;
        });
        $listener->on('test1', function ( ) use (&$cnt) {
            $cnt++;
        });

        $ev = Event::create('test');
        $ev->addTag('test1');

        $listener->notify($ev);
    }

    /**
     * @covers ::create
     * @covers Nora\Core\Event\EventListener::_fetchListener
     * @covers Nora\Core\Event\EventListener::on
     * @covers Nora\Core\Event\EventListener::notify
     */
    public function testEventListener ()
    {
        $listener = EventObserver::create();
        $this->assertInstanceOf(
            __namespace__.'\EventObserverIF',
            $listener
        );
        $this->assertInstanceOf(
            __namespace__.'\EventListener',
            $listener
        );

        $cnt = 0;

        $listener->on('test', function ( ) use (&$cnt) {
            $cnt++;
        });
        $listener->on('test1', function ( ) use (&$cnt) {
            $cnt++;
        });

        $ev = Event::create('test');
        $ev->addTag('test1');

        $listener->notify($ev);

        $this->assertEquals(2,$cnt);
    }

    /**
     * @covers ::create
     * @covers Nora\Core\Event\EventObserverClosure::__construct
     * @covers Nora\Core\Event\EventObserverClosure::notify
     */
    public function testEventObserverClosure ()
    {
        $observer = EventObserver::create(function (EventIF $event) {
            $this->assertTrue($event->match('test'));
            $this->assertTrue($event->match('test1'));

        });
        $ev = Event::create('test');
        $ev->addTag('test1');

        $observer->notify($ev);
    }

}
