<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */

namespace Nora\Core\Module;

use Nora\Core\Component\Component;
use Nora\Core\Util\Collection\Hash;

/**
 * モジュール:設定値を保持するクラス
 */
class Config extends Hash
{
    public function __construct($fields = [])
    {
        foreach($fields as $k=>$v)
        {
            parent::set($k, $v);
        }
    }

    public function set ($k, $v)
    {
        if (!$this->has($k))
        {
            throw new UndefinedParam($k);
        }
        parent::set($k, $v);
    }

    public function &get ($k, $v =null)
    {
        if (!$this->has($k))
        {
            throw new UndefinedParam($k);
        }
        return parent::get($k);
    }
}
