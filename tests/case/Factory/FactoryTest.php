<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Factory;

/**
 * @coversDefaultClass Nora\Core\Factory\Factory
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::createFactory
     */
    public function testCreate ()
    {
        $f = Factory::createFactory();
        return $f;
    }

    /**
     * @covers ::createFactory
     * @expectedException Nora\Core\Exception\InvalidSpec
     */
    public function testCreate2 ()
    {
        $f = Factory::createFactory(0);
        return $f;
    }

    /**
     * @depends testCreate
     * @covers ::addFactory
     * @covers ::_factories
     * @covers ::canCreate
     * @covers ::create
     * @covers Nora\Core\Factory\FactoryClosure::canCreateImpl
     * @covers Nora\Core\Factory\FactoryClosure::createImpl
     * @covers Nora\Core\Factory\FactoryClosure::__construct
     * @covers Nora\Core\Factory\FactoryClosure::register
     */
    public function testChaine($f)
    {
        $this->assertFalse($f->canCreate('hoge'));

        $f->addFactory($child = Factory::createFactory(['hoge' => function ( ) {
            return new \StdClass();
        }]));

        $this->assertTrue($f->canCreate('hoge'));
        $this->assertFalse($f->canCreate('hogehoge'));

        $this->assertInstanceOf('StdClass', $f->create('hoge'));
        $this->assertFalse($f->create('hogehoge'));
        $this->assertFalse($child->create('notExists'));
    }

    /**
     * @depends testCreate
     * @covers ::createFactory
     * @covers ::getList
     * @covers Nora\Core\Factory\FactoryClosure::getListImpl
     * @covers Nora\Core\Factory\FactoryClosure::registerByObject
     */
    public function testBootMethods($f)
    {
        $subject = $this->getMockBuilder('Subject')
            ->setMethods(array('bootHogehoge','bootHoge'))
            ->getMock();
        $f->addFactory($child = Factory::createFactory($subject));

        $child->addFactory(
            Factory::createFactory(
                $subject = $this->getMockBuilder('Subject')
                ->setMethods(array('bootHogehoge','bootHoge'))
                ->getMock()));

        $this->assertTrue(!!array_search('hogehoge', $f->getList()));

        $this->assertTrue($f->canCreate('hogehoge'));

    }

}
