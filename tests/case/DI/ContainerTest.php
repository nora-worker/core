<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\DI;

/**
 * @coversDefaultClass Nora\Core\DI\Container
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::initContainer
     * @covers ::register
     * @covers ::has
     * @covers ::get
     * @covers Nora\Core\DI\Exception\InstanceNotFound::__construct
     * @expectedException Nora\Core\DI\Exception\InstanceNotFound
     */
    public function testCreate ( )
    {
        $c = new Container();
        $c->register([
            'hOge' => function ( ) {
                return new \StdClass();
            }
        ]);

        $this->assertTrue($c->has('Hoge'));


        $this->assertInstanceOf('StdClass', $c->get('hogE'));

        $c->get('Hoge')->cnt = 0;
        $c->get('hoGe')->cnt++;

        $c->on('di.container.pre_get', function($e) {
            if (strtolower($e->name) === 'phpunit')
            {
                $e->instance = $this;
            }
        });

        $this->assertEquals(
            $this,
            $c->get('phpUnit')
        );

        $c->get('InvalidName');

    }


}
