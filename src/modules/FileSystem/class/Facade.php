<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\FileSystem;

use Nora\Core\Component\Component;
use Nora\Core\Util\Collection\Hash;
use Nora\Core\Scope\ScopeIF;
use Nora\Core\Module\Module;

/**
 * ファイルシステムモジュール
 */
class Facade extends Module
{

    /**
     * ファイルシステムを作成する
     */
    public function newFileSystem($path)
    {
        return new FileSystem($path);
    }

    protected function initModuleImpl( )
    {
    }
}
