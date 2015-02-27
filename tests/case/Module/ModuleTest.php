<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Module;

use Nora;
use Nora\Core\Scope\Scope;

/**
 * モジュールのテスト
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * モジュールテスト
     */
    public function testModule ( )
    {
        $loader = new ModuleLoader(Scope::create()->setComponent(
            'autoloader', function( ) {
                return Nora::Autoloader();
            }));

        // モジュールのロードディレクトリを作成
        $loader->addModulePath(
            TEST_PROJECT_PATH.'/modules'
        );

        // モジュール生成時の処理
        $loader->on('moduleloader.loadmodule', function($e) {

            if ($e->name === 'hoge')
            {
                $e->module->configure(['a'=>'1234']);
            }
            if ($e->name === 'hoge2')
            {
                $e->module->configure(['a'=>'5678']);
            }

        });

        $hoge = $loader->loadModule('hoge');

        $this->assertEquals('1234', $hoge->sayValueOfA());
        $this->assertEquals('fuga', $hoge->sayValueOfB());
        $this->assertEquals('5678', $loader->loadModule('hoge2')->sayValueOfA());
    }

}
