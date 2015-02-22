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

use Nora\Core\Component\Component;
use Nora\Core\Util\Collection\Hash;

/**
 * アプリケーションコンフィグ用のコンポーネント
 */
class Config extends Component
{
    private $_dirs = [];

    // 読み込んだファイルのリスト
    public $loaded = [];

    // コンフィグデータ
    private $_data = [];


    /**
     * 初期化処理
     */
    protected function initComponentImpl (  )
    {
        $this->_data = Hash::create(
            Hash::NO_CASE_SENSITIVE
        );
    }

    /**
     * コンフィグディレクトリ
     */
    public function addConfigDir($dir)
    {
        $this->_dirs[] = $dir;
        return $this;
    }

    /**
     * コンフィグをロードする
     */
    public function load($name)
    {
        foreach ($this->_dirs as $dir)
        {
            if (is_dir($file = $dir.'/'.$name))
            {
                $d = dir($file);
                while($e = $d->read())
                {
                    if ($e{0} === '.') continue;

                    $section = substr($e,0,strrpos($e, '.'));
                    $this->loadFile($file.'/'.$e, $section);
                }
            }

            if (file_exists($file = $dir.'/'.$name.'.php'))
            {
                $this->loadFile($file);
            }

        }
        return $this;
    }

    /**
     * ファイルをロードする
     */
    public function loadFile($file, $section = null)
    {
        $this->loaded[] = [
            $file,
            $section
        ];

        if ($section === null)
        {
            $this->set(include $file);
            return $this;
        }

        $datas = $this->get($section, []);
        $datas = array_merge($datas, include $file);
        $this->set($section, $datas);
    }

    // コンフィグデータの操作
    
    public function has ($name)
    {
        $name = strtok($name, '.');

        if (!$this->_data->has($name)) return false;

        $data = $this->_data->get($name);

        while($sub = strtok('.'))
        {
            if (!isset($data[$sub])) return false;

            if (!is_array($data[$sub])) return false;

            if (array_key_exists($sub, $data))
            {
                $data = $data[$sub];
            }else{
                return false;
            }
        }
        return true;
    }

    /**
     * コンフィグデータのセット
     */
    public function set ($name, $value = null)
    {
        if (is_array($name)) {
            foreach($name as $k=>$v) {
                $this->set($k, $v);
            }
            return $this;
        }

        $this->_data->set($name, $value);
        return $this;
    }


    /**
     * コンフィグデータの取得
     */
    public function get($name, $default = false)
    {
        $name = strtok($name, '.');

        if (!$this->_data->has($name)) {
            return $default;
        }

        $datas = $this->_data->get($name);

        while($sub = strtok('.'))
        {
            if (!is_array($datas)) return $default;

            if (array_key_exists($sub, $datas))
            {
                $datas = $datas[$sub];
            }
        }

        return $datas;
    }

}
