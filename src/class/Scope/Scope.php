<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Scope;

use Nora\Core\Event\EventClientTrait;
use Nora\Core\Util\Collection\Hash;

/**
 * 動的な多重継承を実現するオブジェクト
 *
 * 変数、関数、コンポーネントなどを保存する
 */
class Scope implements ScopeIF
{
    use EventClientTrait;
    use ScopeTreeTrait;
    use ScopeComponentsTrait;
    use ScopeHelpersTrait;

    private $_name;
    private $_di_container;

    /**
     * コンストラクタ
     */
    public function __construct ( )
    {
    }


    /**
     * 新しいスコープを作成する
     *
     * @param ScopeIF $parent
     * @param string $name
     */
    static public function create (ScopeIF $parent = null, $name = 'scope')
    {
        $s = new Scope( );

        // スコープに名前を入れる
        $s->_name = $name;

        $s->initScope();

        // 親スコープを指定
        if ($parent !== null)
        {
            $s->setParent($parent);
        }

        return $s;
    }

    /**
     * スコープ名を取得
     *
     * @return string
     */
    public function getName ( )
    {
        if (!$this->hasParent())
        {
            return $this->_name;
        }
        return $this->getParent()->getName().".".$this->_name;
    }

    /**
     * スコープを初期化
     */
    protected function initScope ( )
    {
        $this->initScopeComponents( );
        $this->initScopeHelpers( );
    }

    /**
     * ヘルパーが登録されていればヘルパを実行
     * ヘルパが無い、かつコンポーネントが存在すれば、それを取得
     */
    public function __call ($name, $args)
    {
        static $prev_name;
        static $cnt;

        if ($prev_name === $name) $cnt++;
        else $cnt = 0;

        $prev_name = $name;

        if ($cnt > 20) throw new \RuntimeException("ループしています ".get_class($this)."::".$name);


        if ($this->hasHelper($name))
        {
            return $this->invokeHelperArray($name, $args);
        }

        if ($this->hasComponent($name))
        {
            return $this->getComponent($name, $args[0]);
        }

        throw new Exception\InvalidMethodCalled($name, $this);
    }

    /**
     * クロージャを作成する
     *
     * @param array|closure
     * @return callable
     */
    public function  makeClosure ($spec, $over = [])
    {
        if ($spec instanceof \Closure) return $spec;

        return function () use ($spec, $over) {
            $over = Hash::create(0, $over);
            $function = array_pop($spec);
            $depends = $spec;

            $args = [];
            foreach($depends as $d)
            {
                if ($over->has($d))
                {
                    $args[] =$over->get($d);
                    continue;
                }
                $args[] = $this->getComponent($d);
            }

            foreach(func_get_args() as $a) $args[] = $a;

            return call_user_func_array($function,$args);
        };
    }

    public function __set ($name, $value)
    {
        $this->registry()->set($name, $value);
    }
    public function &__get ($name)
    {
        return $this->registry()->get($name);
    }

    public function __isset( $name)
    {
        return $this->registry()->has($name);
    }

    public function addModulePath($path)
    {
        $reg = $this->rootScope()->registry();

        if(!$this->rootScope()->registry()->has('module_path'))
        {
            $this->rootScope()->registry()->set('module_path', []);
        }
        $paths = $this->rootScope()->registry()->get('module_path');
        array_unshift($paths, $path);
        $this->rootScope()->registry()->set('module_path', $paths);
    }

    public function addModuleNS($ns)
    {
        $reg = $this->rootScope()->registry();

        if(!$reg->has('module_ns'))
        {
            $reg->set('module_ns', []);
        }
        $ns_list = $reg->get('module_ns');
        array_unshift($ns_list, $ns);
        $this->rootScope()->registry()->set('module_ns', $ns_list);
    }


    public function loadModule($name, $settings = [])
    {
        if ($this->hasParent())
        {
            return $this->rootScope()->loadModule($name, $settings);
        }

        if (!$this->hasComponent($name))
        {
            $settings = Hash::create(Hash::NO_CASE_SENSITIVE,$settings);

            foreach ($this->registry()->get('module_ns', []) as $ns)
            {
                if (class_exists($class = $ns.'\\'.$name.'\\Facade'))
                {
                    $class::register($this, $settings);
                }
                return true;
            }

            // モジュールを読み込む
            foreach ($this->registry()->get('module_path', []) as $path)
            {
                $loader = $path.'/'.$name.'/loader.php';

                if (file_exists($loader))
                {
                    $spec = include $loader;
                    $this->setComponent($name, $this->makeClosure($spec, [
                        'settings' => Hash::create(Hash::NO_CASE_SENSITIVE,$settings)
                    ]));
                    return true;
                }
            }
        }
    }

    public function checkModule($name)
    {
        if ($this->hasParent()) return call_user_func_array([$this->rootScope(),__method__], func_get_args());

        $args = func_get_args();
        foreach ($args as $name)
        {
            $this->loadModule($name);
            if (!$this->hasComponent($name))
            {
                throw new Exception\ModuleDependency($name);
            }
        }
    }

}
