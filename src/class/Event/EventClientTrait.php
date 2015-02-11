<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Event;


trait EventClientTrait
{
    private $_event_manager;
    private $_event_default_tag;
    private $_event_subject;

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
                $this->_event_subject
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
        return ($this->_event_default_tag) ?
            $this->_event_default_tag:
            str_replace('\\', '.', get_class($this));
    }

    private function _setDefaultTag($tag)
    {
        $this->_event_default_tag = str_replace('\\', '.', get_class($tag));
    }

    public function accept($subject)
    {
        $this->_event_subject = $subject;
        $this->_setDefaultTag($subject);
    }
}
