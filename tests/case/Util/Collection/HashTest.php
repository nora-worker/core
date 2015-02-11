<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Util\Collection;

/**
 * 基礎的なテスト
 *
 * @coversDefaultClass Nora\Core\Util\Collection\Hash
 */
class HashTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::create
     * @covers ::set
     * @covers ::has
     * @covers ::get
     * @covers ::_filterName
     */
    public function testCreate ( )
    {
        $this->assertInstanceOf(
            __namespace__.'\Hash',
            $hash = Hash::create(Hash::NO_CASE_SENSITIVE, ['a'=>'b'], ['c'=>'d'], ['hoge'=>'XYZ'], ['hOgE'=>'ABC'])
        );

        $this->assertTrue(
            $hash->has('HoGe')
        );
        $this->assertEquals(
            'ABC',
            $hash->get('HoGe')
        );
        $this->assertEquals(
            'not_exists',
            $hash->get('NotExists','not_exists')
        );
        return $hash;
    }


}
