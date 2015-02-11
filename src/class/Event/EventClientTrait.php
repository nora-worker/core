<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Event;


trait EventClientTrait
{
    private $_event_manager;

    /**
     * イベントマネージャー
     */
    public function EventManager ( )
    {
        return ($this->_event_manager) ?
            $this->_event_manager:
            $this->_event_manager = EventManager::create($this);
    }

    /**
     * イベントを購読する
     */
    public function on ($tag, $spec)
    {
        $this->EventManager( )->on($tag, $spec);
        return $this;
    }

    /**
     * イベントを監視する
     */
    public function observe ($spec)
    {
        $this->EventManager( )->observe($spec);
    }

    /**
     * イベントをディスパッチする
     */
    public function dispatch($tag, EventIF $event = null)
    {
        return $this->EventManager( )->dispatch($tag, $event);
    }
}
