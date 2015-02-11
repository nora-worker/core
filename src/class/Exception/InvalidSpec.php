<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Exception;

/**
 * スペック系の例外
 */
class InvalidSpec extends Exception
{
    public function __construct ($class, $function, $spec, $format = "%sは%sでは使えません")
    {
        parent::__construct(
            sprintf($format, $class."::".$function, gettype($spec))
        );
    }
}
