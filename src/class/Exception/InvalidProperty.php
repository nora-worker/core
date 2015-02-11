<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Exception;

/**
 * プロパティ系の例外
 */
class InvalidProperty extends Exception
{
    public function __construct ($name, $class)
    {
        $class = get_class($class);

        parent::__construct(
            sprintf("Invalid Property %s::%s", $class, $name)
        );
    }
}
