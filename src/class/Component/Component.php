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
        $this->setComponent('scope', function ( ) {
            return $this->_scope;
        });
        $this->setComponent('component', function ( ) {
            return $this;
        });
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
        return $this->scope()->$name;
    }
}
