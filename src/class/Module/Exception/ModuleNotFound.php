<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Module\Exception;
use Nora\Core\Exception\Exception;

/**
 * プロパティ系の例外
 */
class ModuleNotFound extends Exception
{
    public function __construct ($name)
    {
        parent::__construct(
            sprintf("Module %s is Not Found", $name)
        );
    }
}
