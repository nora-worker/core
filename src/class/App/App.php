<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\App;

use Nora\Core\Autoloader;
use Nora\Core\Scope\Scope;
use Nora\Core\Module\ModuleLoader;

use Nora\Core\Component\Component;

/**
 * アプリケーションクラス
 */
class App extends Component
{

    protected function initComponentImpl ( )
    {
        $this->scope()->setComponent( [
            'app' => $this
        ])->setHelper( [
            'getPath' => ['FileSystem', function ($fs, $path = '') {
                return $fs->getPAth($path);
            }]
        ]);

        $this->initApp();
    }

    public function initApp ( )
    {
    }


    /**
     * モジュールローダを取得する
     */
    protected function bootModuleLoader( )
    {
        $loader = new ModuleLoader($this->newScope());

        // 設定
        $loader->on('moduleloader.loadmodule', function ($e) {

            if($this->config( )->has('module.'.$e->name))
            {
                $e->module->configure(
                    $this->config()->get('module.'.$e->name));
            }
        });
        return $loader;
    }

    /**
     * アプリケーション用ファイルシステム
     */
    protected function bootFileSystem( )
    {
        $fs = $this->ModuleLoader( )->load('FileSystem')
            ->newFileSystem($this->root_path);
        $fs->alias([
            '@var'       => './var',
            '@tmp'       => '@var/tmp',
            '@log'       => '@var/log',
            '@cache'     => '@var/cache',
            '@config'    => 'config',
            '@component' => 'component',
            '@app'       => 'aapp',
        ]);
        return $fs;
    }

    /**
     * コンフィグを作成する
     */
    protected function bootConfig( )
    {
        return new Config($this->newScope());
    }

    public function status ( )
    {
        return [
            'root_path' => $this->root_path,
            'env' => $this->env,
            'configs' => $this->config()->loaded,
        ];
    }

    public function configure($path = null, $env = null)
    {
        // ルートパスを設定
        $this->root_path = $path === null ? __DIR__: $path;

        // 環境名を設定
        $this->env = $env === null ? 'prod': $env;

        $this->fire('app.pre_configure', [
            'app' => $this
        ]);

        // 設定ファイル置場を指定した後に
        // default $envをロードする
        $this->Config( )->addConfigDir(
            $this->FileSystem()->getPath('@config')
        )->load('default')->load($this->env);

        // 環境をセットアップ
        $this->module('environment');

        // オートロード処理
        $this->AutoLoader()->addLibrary(
            $this->Config()->get(
                'ns.autoload',
                []
            )
        );

        $this->fire('app.post_configure', [
            'app' => $this
        ]);

    }

    public function setupPHP( )
    {
        # PHPの設定
        mb_language($this->config( )->get('php.mb_language', 'ja'));

        mb_internal_encoding(
            $this->config( )->get('php.mb_internal_encoding', 'utf8')
        );
    }

    public function check ( )
    {
        // ディレクトリの権限チェック
        foreach(['@cache', '@log'] as $dir)
        {
            $this->FileSystem( )->ensureWritableDir($dir);
        }

        return true;
    }

    public function isDevel( )
    {
        return $this->Config( )->get('is_devel', false);
    }


    /**
     * モジュールの初期化
     *
     * @param string $name
     * @param ModuleIF $module
     */
    static public function moduleConfigure($name, $module)
    {
        // TODO モジュールの設定値
    }

}

