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

use Nora\Core\DI\Container as DIContainer;
use Nora\Core\Event\Event;

/**
 * スコープのコンポーネントに関する機能
 */
trait ScopeComponentsTrait
{
    /**
     * スコープのコンポーネントを初期化
     */
    protected function initScopeComponents( )
    {
        $this->_di_container = new DIContainer( );

        // コンポーネント取得イベントを定義する
        $this->on("scope.component.get", function ($ev) {

            // コンポーネントマネージャから取得を試みる
            if ($this->_di_container->has($ev->name))
            {
                $ev->component = $this->_di_container->get($ev->name);
                $ev->stopPropagation();
                return;
            }

            // 自分で見つからなかったら親へ
            if ($this->hasParent( ))
            {
                $this->getParent( )->dispatch($ev);
            }
        })->on("scope.component.has", function ($ev) {

            if ($this->_di_container->has($ev->name))
            {
                $ev->found = true;
                $ev->stopPropagation();
                return;
            }

            // 自分で見つからなかったら親へ
            if ($this->hasParent( ))
            {
                $this->getParent( )->dispatch($ev);
            }
        });
    }

    /**
     * コンポーネントの読み込み
     */
    public function getComponent($name)
    {
        // コンポーネントの取得要求を出す
        $event = $this->dispatch('scope.component.get', [
            'name' => $name,
            'component' => null
        ]);

        if (!is_null($event->component))
        {
            return $event->component;
        }

        throw new Exception\ComponentNotFound($name, $this);
    }

    /**
     * コンポーネントがあるか
     */
    public function hasComponent($name)
    {
        // コンポーネントの取得要求を出す
        return $event = $this->dispatch('scope.component.has', [
            'name' => $name,
            'found' => false
        ])->found;
    }

    /**
     * コンポーネントの登録
     */
    public function setComponent($name, $spec = null)
    {
        if (is_array($name))
        {
            foreach ($name as $k=>$v)
            {
                $this->setComponent($k, $v);
            }
            return $this;
        }

        $this->_di_container->register($name, $this->makeClosure($spec));

        return $this;
    }

}

