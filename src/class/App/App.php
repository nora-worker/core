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

    protected function initComponent ( )
    {
        parent::initComponent();
        $this->initApp();
    }

    public function initApp ( )
    {
    }

    protected function bootModuleLoader( )
    {
        $loader = new ModuleLoader($this->newScope());
        $loader->on('moduleloader.loadmodule', function ($e) {
            return $this->moduleConfigure($e->name, $e->module);
        });
        return $loader;
    }

    protected function bootFileSystem( )
    {
        $fs = $this->ModuleLoader( )->load('FileSystem')
            ->newFileSystem($this->root_path);
        $fs->alias([
            '@var'    => './var',
            '@tmp'    => '@var/tmp',
            '@log'    => '@var/log',
            '@cache'  => '@var/cache',
            '@config' => 'config',
        ]);
        return $fs;
    }

    protected function bootConfig( )
    {
        return new Config();
    }

    public function configure($path, $env)
    {
        // ルートパスを設定
        $this->root_path = $path;

        // 環境名を設定
        $this->env = $env;

        $this->fire('app.pre_configure', [
            'app' => $this
        ]);

        // 設定ファイル置場を指定
        $this->Config( )->addConfigDir(
            $this->FileSystem()->getPath('@config')
        );

        // コンフィグのロード
        $this->Config( )
            ->load('default')
            ->load($this->env);

        $this->fire('log.notice', [
            'msg' => 'Loaded Files ' . var_export($this->config()->loaded,1)
        ]);


        $this->fire('app.post_configure', [
            'app' => $this
        ]);
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

