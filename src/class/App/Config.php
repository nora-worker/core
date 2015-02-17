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
 * モジュール:設定値を保持するクラス
 */
class Config extends Hash
{
    private $_dirs = [];

    // 読み込んだファイルのリスト
    public $loaded = [];

    public function addConfigDir($dir)
    {
        $this->_dirs[] = $dir;
        return $this;
    }

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

    public function loadFile($file, $section = null)
    {
        $this->loaded[] = [
            $file,
            $section
        ];

        if ($section === null)
        {
            $this->merge(include $file);
            return $this;
        }

        $datas = $this->get($section, []);
        $datas = array_merge($datas, include $file);
        $this->set($section, $datas);
    }

    public function &get($name, $default = false)
    {
        $name = strtok($name, '.');

        if (!$this->has($name)) {
            return $default;
        }

        $datas = parent::get($name);
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
