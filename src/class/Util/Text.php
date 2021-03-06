<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Util;

class Text
{
    public function toCamel ($text)
    {
        return implode('', array_map(function($v) {
            $v = ucfirst($v);
            return $v;
        },explode('_', $text)));
    }
}
