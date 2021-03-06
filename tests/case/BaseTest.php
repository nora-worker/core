<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */

/**
 * 基礎的なテスト
 *
 * @coversDefaultClass Nora
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * オートローダが取得できるか
     *
     * @covers ::Autoloader
     */
    public function testAutoloader ( )
    {
        $this->assertInstanceOf(
            'Nora\Core\Autoloader',
            Nora::Autoloader()
        );
    }

    /**
     * Initialize
     *
     * @covers ::initialize
     */
    public function testInitialize ( )
    {
        Nora::initialize(TEST_PROJECT_PATH, 'devel');
    }
}
