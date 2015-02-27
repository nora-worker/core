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

/**
 * スコープをツリー階層にするメソッド達
 */
trait ScopeTreeTrait
{
    /**
     * 親スコープ
     *
     * @var Scope
     */
    private $_parent_scope = false;

    /**
     * 親スコープをセットする
     *
     * @param ScopeIF $scope
     */
    private function setParent(ScopeIF $scope)
    {
        // 親を保持
        $this->_parent_scope = $scope;
    }

    /**
     * 親スコープを取得する
     *
     * @return ScopeIF
     */
    public function getParent( )
    {
        return $this->_parent_scope;
    }

    /**
     * 親スコープがあるか
     *
     * @return bool
     */
    public function hasParent( )
    {
        return $this->_parent_scope !== false ? true: false;
    }

    /**
     * 最上位のスコープを取得する
     *
     * @return bool
     */
    public function rootScope( )
    {
        return $this->hasParent() ?
            $this->getParent()->rootScope():
            $this;
    }

    /**
     * 新しいチャイルドスコープを取得する
     */
    public function newScope($name = "child")
    {
        return Scope::create($this, $name);
    }

}

