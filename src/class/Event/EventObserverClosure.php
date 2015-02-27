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


/**
 * イベントオブザーバ
 */
class EventObserverClosure extends EventObserver
{
    private $_event_handler = null;

    /**
     * コンストラクタ
     */
    public function __construct($handler)
    {
        $this->_event_handler = $handler;
    }

    /**
     * イベント受信
     *
     * @param Event $event
     */
    public function notify (EventIF $ev)
    {
        if ($this->_event_handler)
        {
            call_user_func($this->_event_handler, $ev);
        }
    }
}
