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
    public function dispatch($tag, $params = [])
    {
        if (!($tag instanceof EventIF))
        {
            $ev = Event::Create(
                $this->filterEventTag($tag),
                $params,
                $this
            );
            return $this->dispatch($ev);
        }

        $ev = $tag;
        return $this->EventManager( )->dispatch($ev);
    }

    protected function filterEventTag($tag)
    {
        return [
            $tag,
            $this->_getDefaultTag()
        ];
    }

    private function _getDefaultTag( )
    {
        return str_replace('\\', '.', get_class($this));
    }

}
