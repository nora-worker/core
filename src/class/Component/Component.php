<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Component;

use Nora\Core\Util\Collection\Hash;
use Nora\Core\Scope\Scope;

/**
 * コンポーネント
 */
class Component 
{
    protected $_scope = null;

    public function __construct(Scope $scope)
    {
        $scope->accept($this);
        $this->_scope = $scope;
        $this->initComponent();
    }

    protected function initComponent( )
    {
        $this->_scope->setComponent('scope', function ( ) {
            return $this->_scope;
        });

        $this->_scope->setComponent('component', function ( ) {
            return $this;
        });

        // メソッドベースのコンポーネントを取り込む
        foreach(get_class_methods($this) as $m)
        {
            if (0 === strpos($m,'boot'))
            {
                $this->_scope->setComponent(
                    substr($m,4),
                    function ( ) use ($m) {
                        return $this->{$m}( );
                    }
                );
            }
        }

        $this->initComponentImpl( );
    }

    protected function initComponentImpl( )
    {
    }

    public function scope ( )
    {
        return $this->_scope;
    }

    /**
     * 処理をスコープに任せる
     */
    public function __call ($name, $args)
    {
        if ($this->scope() === null) return false;

        $res = call_user_func_array([$this->scope(), $name], $args);

        if ($res === $this->scope())
        {
            return $this;
        }
        return $res;
    }

    public function __set($name, $value)
    {
        $this->scope()->$name = $value;
    }

    public function &__get($name)
    {
        $data = $this->scope()->$name;
        return $data;
    }

    /**
     * デバッグログイベントを発生させる
     *
     * @param string $msg
     */
    public function debug($msg, $options = [])
    {
        if (!is_string($msg))
        {
            $msg = var_export($msg, true);
        }
        $options['msg'] = $msg;
        $this->rootScope()->fire('log.debug',$options);
    }

    /**
     * アラートログイベントを発生させる
     *
     * @param string $msg
     */
    public function alert($msg, $options = [])
    {
        $options['msg'] = $msg;

        $this->rootScope()->fire('log.alert',$options, $this);
    }

    /**
     * 通知ログイベントを発生させる
     *
     * @param string $msg
     */
    public function notice($msg, $options = [])
    {
        $options['msg'] = $msg;

        $this->rootScope()->fire('log.notice', $options, $this);
    }

    /**
     * INFOログイベントを発生させる
     *
     * @param string $msg
     */
    public function info($msg, $options = [])
    {
        $options['msg'] = $msg;

        $this->rootScope()->fire('log.info',$options, $this);
    }

    /**
     * 警告ログイベントを発生させる
     *
     * @param string $msg
     */
    public function warn($msg, $options = [])
    {
        $options['msg'] = $msg;

        $this->rootScope()->fire('log.warning',$options, $this);
    }
}
