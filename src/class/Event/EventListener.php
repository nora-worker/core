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

use Nora\Collection\ObjectList;
use Closure;

/**
 * イベントリスナー
 */
class EventListener extends EventObserver
{
    private $_store = [];

    public function on ($tag, $spec)
    {
        if (!array_key_exists($tag, $this->_store))
        {
            $this->_store[$tag] = [];
        }

        array_unshift($this->_store[$tag], $spec);
    }

    private function _fetchListener (EventIF $ev)
    {
        foreach($this->_store as $k=>$list)
        {
            if ($ev->match($k))
            {
                foreach($list as $handler)
                {
                    yield $handler;
                }
            }
        }
    }

    public function notify (EventIF $ev)
    {
        foreach($this->_fetchListener($ev) as $listener)
        {
            if ($ev->isStopPropagation()) continue;
            call_user_func($listener, $ev);
        }
    }
}

