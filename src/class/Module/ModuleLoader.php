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

use Nora\Core\Component\Component;
use Nora\Core\Util\Collection\Hash;

/**
 * モジュールローダクラス
 */
class ModuleLoader extends Component {
    const FACADE_CLASS="Facade";
    const LOADER_PATH="loader.php";

    private $_module_path_list = [];
    private $_module_ns_list = [];
    private $_load_log = [];

    /**
     * モジュール名のノーマライズ
     */
    private function _name_normalize ($name)
    {
        return ucfirst($name);
    }


    /**
     * モジュールロードパスの追加
     */
    public function addModulePath($path) {
        if ($path.'/autoload.php') {
            $this->invoke(include $path.'/autoload.php');
        }
        array_unshift($this->_module_path_list, $path);
    }

    /**
     * モジュールロードネームスペースの追加
     */
    public function addModuleNS($ns) {
        array_unshift($this->_module_ns_list, $ns);
    }

    /**
     * モジュールのファクトリメソッドを取得する
     *
     * @param string $name
     */
    public function getModuleFactory($name)
    {
        $name = $this->_name_normalize($name);

        // ネームスペースから読み込みをトライ
        foreach($this->_module_ns_list as $ns)
        {
            if (class_exists($class = $ns.'\\'.$name.'\\'.self::FACADE_CLASS))
            {
                return $class::facade();
            }
        }

        // ファイルシステムから読み込みをトライ
        foreach ($this->_module_path_list as $path)
        {
            $loader = $path.'/'.$name.'/'.self::LOADER_PATH;

            if (file_exists($loader))
            {
                return $spec = include $loader;
            }
        }

        // FactoryNotFound
        throw new Exception/ModuleNotFound($name, $this);
    }

    /**
     * モジュールを読み込む
     *
     * 初回ロード時だけ moduleloader.loadmodule イベントを実行する
     *
     * @param string $name
     */
    public function loadModule($name)
    {
        $name = $this->_name_normalize($name);

        if(!$this->hasComponent('___'.$name))
        {
            $this->setComponent('___'.$name, function ( ) use ($name){
                $module = $this->invoke($this->getModuleFactory($name));
                return $this->fire('moduleloader.loadmodule', [
                    'module' => $module,
                    'name' => strtolower($name)
                ])->module;
            });
        }

        return $this->getComponent('___'.$name);
    }

    public function load($name)
    {
        return $this->loadModule($name);
    }
}
