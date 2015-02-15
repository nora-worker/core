<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */

use Nora\Core\Autoloader;
use Nora\Core\Scope\Scope;

/**
 * Noraのメインクラス
 *
 * 基本的な処理はself::$_applicationに保存された
 * オブジェクトに引き渡す
 */
class Nora 
{
    const LIB = __DIR__;
    const MODULE_PATH = __DIR__.'/../modules';

    /**
     * オートローダ
     */
    static private $_autoloader;

    /**
     * メインスコープ
     */
    static private $_scope;


    /**
     * オートローダの取得
     *
     * 初期化されていなければ、初期化する
     *
     * @return Autoloader
     * @codeCoverageIgnore
     */
    static public function Autoloader( ) 
    {
        if (self::$_autoloader) 
        {
            return self::$_autoloader;
        }

        require_once self::LIB.'/Autoloader.php';

        return 
            self::$_autoloader = 
                Autoloader::create(
                    [
                        'Nora\Core' => self::LIB,
                        'Nora\Module\Application' => self::MODULE_PATH.'/Application/class'
                    ]
                );
    }


    /**
     * スコープの初期化
     */
    static public function initialize ( )
    {
        self::$_scope = Scope::create(null, 'NoraScope');

        self::setComponent('Autoloader', function ( ) {
            return Nora::Autoloader();
        });

        self::addModulePath(realpath(__DIR__.'/../modules/'));
        self::addModuleNS('Nora\Module');
    }

    /**
     * @param string $name 呼びだされたメソッド名
     * @param string $args 呼びだされたメソッドの引数
     * @return mixed
     */
    static public function __callStatic ($name, $args)
    {
        return call_user_func_array(
            [self::$_scope, $name],
            $args
        );
    }
}
