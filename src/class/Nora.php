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
use Nora\Component\Application\Application;

/**
 * Noraのメインクラス
 *
 * 基本的な処理はself::$_applicationに保存された
 * オブジェクトに引き渡す
 */
class Nora 
{
    const LIB = __DIR__;

    /**
     * オートローダ
     */
    static private $_autoloader;

    /**
     * メインアプリケーション
     */
    static private $_application;


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
                        'Nora\Core' => self::LIB
                    ]
                );
    }


    /**
     * アプリケーションの初期化
     *
     * @param string $dir
     * @param array $options
     */
    static public function initialize ($dir, $options = [])
    {
        self::$_application = Application::create($dir, $options);
    }

    /**
     * アプリケーションに伝搬させる
     *
     * @param string $name 呼びだされたメソッド名
     * @param string $args 呼びだされたメソッドの引数
     * @return mixed
     */
    static public function __callStatic ($name, $args)
    {
        return call_user_func_array(
            [self::$_application, $name],
            $args
        );
    }
}
