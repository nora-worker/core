<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Environment;

use Nora\Core\Util\Collection\Hash;

class PHPFunctionWrapper extends Hash
{
    public function __call($name, $params)
    {
        if ($this->has($name))
        {
            return call_user_func_array(
                $this->get($name),
                $params
            );
        }

        if (function_exists($name))
        {
            return call_user_func_array($name, $params);
        }

        if ($name === 'die')
        {
            die($params[0]);
        }
        elseif ($name === 'exit')
        {
            exit($params[0]);
        }
    }
}
