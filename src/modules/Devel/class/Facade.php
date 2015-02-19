<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Devel;

use Nora\Core\Component\Component;
use Nora\Core\Util\Collection\Hash;
use Nora\Core\Scope\ScopeIF;
use Nora\Core\Module\Module;

/**
 * DEVELOPモジュール
 */
class Facade extends Module
{
    protected function initModuleImpl( )
    {

    }

    public function enable($bool)
    {
        $this->rootScope()->setHelper([
            'd' => function ($var) use ($bool) {
                if ($bool === true) var_dump($var);
            }
        ]); 
    }
}
