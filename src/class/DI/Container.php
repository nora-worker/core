<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\DI;

use Nora\Core\Util\Collection\Hash;
use Nora\Core\Event\EventClientTrait;
use Nora\Core\Event\Event;
use Nora\Core\Factory\FactoryClosure;

/**
 * DIContainerパターン
 *
 * ファクトリを登録
 */
class Container extends FactoryClosure implements ContainerIF
{
    use EventClientTrait;

    /**
     * インスタンス保持用
     */
    private $_instances;

    /**
     * コンストラクタ
     */
    public function __construct ( )
    {
        $this->initContainer( );
    }

    /**
     * コンテナの初期化
     */
    protected function initContainer( )
    {
        $this->_instance_store    = new Hash(Hash::NO_CASE_SENSITIVE);
    }

    /**
     * ファクトリの登録
     *
     * @param string $name
     * @param callable $spec 
     */
    public function register ($name, $spec = null)
    {
        if (is_array($name) && $spec === null)
        {
            foreach ($name as $k=>$v) $this->register($k, $v);
            return $this;
        }

        // ログイベントをディスパッチする
        $this->dispatch('log.notice', [
            'message' =>  "$name is registered"
        ]);

        parent::register($name, $spec);
        return $this;
    }

    /**
     * インスタンスが取得可能か
     *
     * @param string $name
     * @return bool
     */
    public function has ($name)
    {
        return $this->canCreate($name) || $this->_instance_store->has($name);
    }

    /**
     * インスタンスの取得
     *
     * 第二引数にfalseを渡すと、新しくインスタンスを作成する。
     * 初期は作成済みインスタンスを共有する。
     *
     * @param string $name
     * @param bool $nocache
     * @return mixed 
     */
    public function get ($name, $share = true)
    {
        // 共有インスタンスを取得
        if ($share === true && $this->_instance_store->has($name))
        {
            return $this->_instance_store->get($name);
        }

        // イベントに処理を委ねる
        $event = 
            $this->dispatch(
                'di.container.pre_get', [
                    'name' => $name,
                    'share' => $share,
                    'instance' => null
                ]
            );

        $instance = null;

        // イベントがインスタンスを持ってくればそれを使う
        if (!is_null($event->instance)) {
            $instance = $event->instance;
        }
        elseif ($this->canCreate($name))
        {
            $instance = $this->create($name);
        }
        else
        {
            throw new Exception\InstanceNotFound($name, $this);
        }

        $this->_instance_store->set($name, $instance);
        return $instance;
    }
}

