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

    static public function create(Scope $scope = null)
    {
        $comp = new self();
        $comp->_scope = $scope;

        // スコープに所有オブジェクトの変更を伝える
        if ($scope != null)
        {
            $scope->accept($comp);
        }

        // イニシャライズ前イベントをディスパッチ
        $comp->dispatch('component.pre_initcomponent');

        $comp->initComponent( );

        // イニシャライズ後イベントをディスパッチ
        $comp->dispatch('component.post_initcomponent');
        return $comp;
    }

    protected function __construct( )
    {
    }


    public function initComponent( )
    {
        if ($this->_scope === null)
        {
            $this->_scope = Scope::create(null, 'ComponentScope');
        }

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
}
