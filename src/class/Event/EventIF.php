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

use Nora\Collection\ObjectList;
use Closure;

/**
 * イベント
 */
interface EventIF
{
    public function stopPropagation ( );

    public function isStopPropagation ( );
}
