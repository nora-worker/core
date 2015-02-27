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
 * ファイルシステム
 */
class FileSystem extends DirectoryNode
{
    private $_aliase_list = [];

    public function __construct($path)
    {
        parent::__construct($path);
    }

    public function alias($name, $path = null)
    {
        if (is_array($name)) {
            foreach($name as $k=>$v)
            {
                $this->alias($k, $v);
            }
            return $this;
        }

        $this->_alias_list[$name] = $path;
    }

    public function getPath($path)
    {
        if ($path{0} === '@')
        {
            if (false !== strpos($path,'/'))
            {
                list($alias,$path) = explode("/", $path, 2);
            }else{
                $alias = $path;
                $path = "";
            }

            return $this->getPath(
                $this->_alias_list[$alias].(is_null($path) ? '': "/$path")
            );
        }

        return parent::getPath($path);
    }

}
 
