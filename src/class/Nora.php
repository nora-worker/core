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
use Nora\Core\App\App;
use Nora\Core\Module\ModuleLoader;

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
     * アプリ
     */
    static private $_app;


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
     * プロジェクトの初期化
     */
    static public function initialize ($path, $env, $cb = null)
    {
        $scope =  Scope::create(null, 'NoraScope')
            ->setComponent('Autoloader', function ( ) {
                return Nora::Autoloader();
            })
            # App設定前に実行される
            ->on('app.pre_configure', function ($e) {

                # モジュールロードパスを追加
                $e
                    ->app
                    ->ModuleLoader( )
                    ->addModulePath(realpath(__DIR__.'/../modules'));

                # コンフィグロードパスを追加
                $e
                    ->app
                    ->Config( )
                    ->addConfigDir(realpath(__DIR__.'/../..').'/config');
            })
            # App設定後に実行される
            ->on('app.post_configure', function ($e) use ($cb) {

                // TODO まとめる

                $app = $e->app;

                # PHPの設定
                mb_language($app->config( )->get('php.mb_language', 'ja'));

                mb_internal_encoding(
                    $app->config( )->get('php.mb_internal_encoding', 'utf8')
                );

                # チェック
                $app->check();

                # 開発ツールのロード
                self::ModuleLoader( )->load('devel')->enable(
                    !$app->isDevel()
                );

                // のらの初期化イベントを発行する
                self::fire('nora.init', [
                    'nora' => $app
                ]);

                if ($cb !== null) self::invoke($cb);
            });

        self::$_app = new App($scope);
        self::configure($path, $env);
        return self::$_app;
    }


    /**
     * @param string $name 呼びだされたメソッド名
     * @param string $args 呼びだされたメソッドの引数
     * @return mixed
     */
    static public function __callStatic ($name, $args)
    {
        if (!self::$_app) {
            throw new \Exception('Nora: Not Initialized');
        }
        return call_user_func_array(
            [self::$_app, $name],
            $args
        );
    }
}
