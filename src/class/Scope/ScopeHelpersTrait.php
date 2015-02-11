<?php
/**
 * のらライブラリのファイル
 */

namespace Nora\Core\Scope;

use Nora\Core\DI\Container as DIContainer;
use Nora\Core\Event\Event;

use Nora\Core\Util\Collection\Hash;

/**
 * スコープのヘルパに関する機能
 */
trait ScopeHelpersTrait
{
    private $_helper_container;
    /**
     * スコープのヘルパーを初期化
     */
    protected function initScopeHelpers( )
    {
        $this->_helper_container = new Hash(Hash::NO_CASE_SENSITIVE);

        // コンポーネント取得イベントを定義する
        $this->on("scope.helper.get", function ($ev) {

            // コンポーネントマネージャから取得を試みる
            if ($this->_helper_container->has($ev->name))
            {
                $ev->helper = $this->_helper_container->get($ev->name);
                $ev->stopPropagation();
                return;
            }

            // 自分で見つからなかったら親へ
            if ($this->hasParent( ))
            {
                $this->getParent( )->dispatch($ev);
            }

        })->on("scope.helper.has", function ($ev) {

            if ($this->_helper_container->has($ev->name))
            {
                $ev->found = true;
                $ev->stopPropagation();
            }

            // 自分で見つからなかったら親へ
            if ($this->hasParent( ))
            {
                $this->getParent( )->dispatch($ev);
            }
        });
    }

    /**
     * ヘルパの読み込み
     */
    public function getHelper($name)
    {
        // ヘルパの取得要求を出す
        $event = $this->dispatch('scope.helper.get', [
            'name' => $name,
            'helper' => null
        ]);

        if (!is_null($event->helper))
        {
            return $event->helper;
        }

        throw new Exception\HelperNotFound($name, $this);
    }

    /**
     * ヘルパの存在確認
     */
    public function hasHelper($name)
    {
        return $event = $this->dispatch('scope.helper.has', [
            'name' => $name,
            'found' => false
        ])->found;
    }

    /**
     * ヘルパの登録
     */
    public function setHelper($name, $spec = null)
    {
        if (is_array($name))
        {
            foreach ($name as $k=>$v)
            {
                $this->setHelper($k, $v);
            }
            return $this;
        }

        $this->_helper_container->set($name, $spec);

        return $this;
    }

    /**
     * ヘルパの実行
     */
    public function invokeHelper($name)
    {
        $args = array_slice(func_get_args(), 1);

        return $this->invokeHelperArray($name, $args);
    }

    /**
     * ヘルパの実行
     */
    public function invokeHelperArray($name, $args)
    {
        return call_user_func_array(
            $this->makeClosure(
                $this->getHelper($name)
            ),
            $args
        );
    }


}

