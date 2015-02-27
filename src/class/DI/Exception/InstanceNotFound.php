<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\DI\Exception;
use Nora\Core\Exception\Exception;


/**
 * DIコンテナ用のエラー
 */
class InstanceNotFound extends Exception
{
    public function __construct ($name, $container)
    {
        parent::__construct("$name is not found");
    }
}

