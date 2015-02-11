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

use Nora\Core\Exception;
use Closure;

/**
 * イベントオブザーバ
 */
abstract class EventObserver implements EventObserverIF
{
    public function create ($spec = null)
    {
        if ($spec instanceof EventObserver)
        {
            return $spec;
        }

        if ($spec === null)
        {
            return new EventListener( );
        }

        if (is_callable($spec))
        {
            return new EventObserverClosure($spec);
        }


        throw new Exception\InvalidSpec(__CLASS__,__FUNCTION__,$spec);
    }

    abstract public function notify (EventIF $event);
}
