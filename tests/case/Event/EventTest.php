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
 * 基礎的なテスト
 *
 * @coversDefaultClass Nora\Core\Event\Event
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::create
     * @covers ::setSubject
     */
    public function testCreate ( )
    {
        $this->assertInstanceOf(
            __namespace__.'\EventIF',
            $e = Event::create('test', ['a'=>'b'], $this)
        );
        return $e;
    }

    /**
     * @depends testCreate
     * @covers ::match
     * @covers ::addTag
     */
    public function testTagMatch ($e)
    {
        $this->assertTrue( $e->match('test') );
        $this->assertFalse( $e->match('tag2') );
        $e->addTag('tag2');
        $this->assertTrue( $e->match('tag2') );
    }

    /**
     * @depends testCreate
     * @covers ::hasSubject
     * @covers ::getSubject
     */
    public function testSubject ($e)
    {
        $this->assertTrue( $e->hasSubject() );
        $this->assertInstanceOf(get_class($this), $e->getSubject());
    }

    /**
     * @depends testCreate
     * @covers ::getParamNames
     * @covers ::__set
     * @covers ::__get
     * @covers ::__isset
     */
    public function testParams ($e)
    {
        $this->assertEquals(['a'], $e->getParamNames() );
        foreach($e->getParamNames() as $name)
        {
            $this->assertTrue(isset($e->a));
        }
        $this->assertEquals('b', $e->a);
        $e->a = 'c';
        $this->assertEquals('c', $e->a);

        try {
            $b = $e->b;
        } catch (\Exception $ex) {
            $this->assertInstanceOf(
                'Nora\Core\Exception\InvalidProperty',
                $ex
            );
        } 

        try {
            $e->hoge = 'hoge';
        } catch (\Exception $ex) {
            $this->assertInstanceOf(
                'Nora\Core\Exception\InvalidProperty',
                $ex
            );
        }
    }

    /**
     * @depends testCreate
     * @covers ::stopPropagation
     * @covers ::isStopPropagation
     */
    public function testPropagation ($e)
    {
        $this->assertFalse($e->isStopPropagation());
        $e->stopPropagation();
        $this->assertTrue($e->isStopPropagation());
    }


    /**
     * @depends testCreate
     * @covers ::trace
     * @covers ::getTrace
     */
    public function testTrace ($e)
    {
        $e->trace(__FILE__, __LINE__);
        $this->assertCount(1,$e->getTrace());
    }

    /**
     * @depends testCreate
     * @covers ::toString
     */
    public function testToString ($e)
    {
        $this->assertTrue(is_string($e->toString()));

        //echo $e->toString();
    }

}
