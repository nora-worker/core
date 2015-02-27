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
 * ディレクトリ
 */
class DirectoryNode
{
    private $_path = '';

    public function __construct($path)
    {
        $this->_path = $path;
    }

    public function getPath($path = null)
    {
        if ($path === null) return $this->_path;

        if ($path{0}.$path{1} === './') {
            return $this->getPath($this->_path.substr($path,1));
        }

        if ($path{0} != '/')
        {
            return $this->_path.'/'.$path;
        }

        return $path;
    }

    public function ensureWritableDir($path, $mode = 0777)
    {
        $dir = $this->getPath($path);

        if (!is_dir($dir))
        {
            if (!mkdir($dir, $mode, true))
            {
                throw \RuntimeException ('Cant Create '.$dir);
            }
        }

        if (!is_writable($dir))
        {
            if (!chmod($dir, $mode))
            {
                throw \RuntimeException ('Cant Change Permission '.$dir);
            }
        }
        return true;
    }
}
 
