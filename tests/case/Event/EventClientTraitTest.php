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
     * @covers ::_getDefaultTag
     * @covers ::filterEventTag
     */
    public function testAll ()
    {
        $client = $this->getMockForTrait('Nora\Core\Event\EventClientTrait');
        $this->assertEquals($client->EventManager(), $client->EventManager());

        $this->cnt = 0;
        $this->cnt2 = 0;

        $client->on('hoge', function( ) {
            $this->cnt++;
        });

        $client->observe(function($ev) {
            if ($ev->match('hoge'))
            {
                $this->cnt++;
            }
        });

        $client->observe(function($ev) use ($client){
            if ($ev->match(get_class($client)))
            {
                $this->cnt2++;
            }
        });
        $client->dispatch('hoge');
        $client->dispatch('hogehoge', []);
        $this->assertEquals(2, $this->cnt);
        $this->assertEquals(2, $this->cnt2);

    }


}
