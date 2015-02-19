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

use Closure;

/**
 * イベントマネージャ
 */
class EventManager {

    private $_listener_store = [];
    private $_observer_store = [];
    private $_subject;
    private $_default_observer;

    static public function create ($subject = null) {

        $em = new EventManager();
        $em->_subject = $subject;
        $em->_default_observer = EventObserver::create();
        $em->observe($em->_default_observer);
        return $em;
    }

    public function dispatch (EventIF $event) {

        if (!$event->hasSubject( )) $event->setSubject($this->_subject);


        foreach($this->_observer_store as  $observer)
        {
            // 伝搬チェック
            if ($event->isStopPropagation()) break;
            $observer->notify($event);
        }

        if (!$event->match('event.dispatched'))
        {
            $this->dispatch(Event::create('event.dispatched', ['event' => $event]));
        }

        return $event;
    }

    public function observe ($spec) {

        array_unshift($this->_observer_store, EventObserver::create($spec));
    }

    public function on ($tag, $spec) {

        $this->_default_observer->on($tag, $spec);
    }
}
