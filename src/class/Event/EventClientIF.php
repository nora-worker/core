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


interface EventClientIF
{
    /**
     * イベントマネージャー
     */
    public function EventManager ( );

    /**
     * イベントを購読する
     */
    public function on ($tag, $spec);

    /**
     * イベントを監視する
     */
    public function observe ($spec);

    /**
     * イベントをディスパッチする
     */
    public function dispatch($tag, EventIF $event = null);
}
