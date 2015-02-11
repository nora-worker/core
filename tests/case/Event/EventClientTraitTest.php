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
 * @coversDefaultClass Nora\Core\Event\EventClientTrait
 */
class EventClientTraitTest extends \PHPUnit_Framework_TestCase
{
    private $cnt;

    /**
     * @covers ::EventManager
     * @covers ::on
     * @covers ::observe
     * @covers ::dispatch
     */
    public function testAll ()
    {
        $client = $this->getMockForTrait('Nora\Core\Event\EventClientTrait');
        $this->assertEquals($client->EventManager(), $client->EventManager());

        $this->cnt = 0;

        $client->on('hoge', function( ) {
            $this->cnt++;
        });

        $client->observe(function($ev) {
            if (!$ev->match('event.dispatched'))
            {
                $this->cnt++;
            }
        });
        $client->dispatch(Event::create('hoge'));

        $this->assertEquals(2, $this->cnt);
    }


}
