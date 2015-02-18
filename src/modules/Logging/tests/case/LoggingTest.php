<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Logging;

use Nora;

/**
 * ロガーのテスト
 *
 */
class LoggingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * モジュールがロードできるか
     *
     * ロード時にロガーの設定を行う
     *
     */
    public function testLoadModule ( )
    {
        Nora::initialize(__DIR__.'/..', 'devel');

        Nora::ModuleLoader( )->on('moduleloader.loadmodule',function($e) {

            if ($e->name === 'logging')
            {
                // ロガーの設定をする
                $e->module->configure([
                    'loggers' => [
                        '_default' => [
                            [
                                'type' => 'stdout',
                                'filter' => [
                                    'level' => 'ALL'
                                ],
                                'format' => '%(time) %(tag) %(level) %(msg) %(args)'
                            ]
                        ]
                    ]
                ]);
            }
        });
        return Nora::ModuleLoader( )->load('logging');
    }

    /**
     * ロガーの登録
     *
     * @depends testLoadModule
     */
    public function testLogger ($module)
    {
        $logger = $module->getLogger( );

        $logger->post(
            Log::create(
                'ログテスト(INFO)',
                [
                    'method' => __METHOD__
                ],
                LogLevel::INFO,
                'tag1'
            )
        );

        $logger->emerg( "MESSAGE" );
        $logger->alert( "MESSAGE" );
        $logger->crit( "MESSAGE" );
        $logger->err( "MESSAGE" );
        $logger->warning( "MESSAGE" );
        $logger->notice( "MESSAGE" );
        $logger->info( "MESSAGE" );
        $logger->debug( "MESSAGE" );


        $logger->debug(
            "テスト",
            [
                'file' => __file__,
                'line' => __line__
            ],
            ['phpunit', 'nora']
        );


    }
}
