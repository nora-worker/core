<?php
/**
 * のらライブラリのファイル
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
