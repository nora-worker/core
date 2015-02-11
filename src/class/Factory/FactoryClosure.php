<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Factory;

/**
 * ファクトリクラス
 */
class FactoryClosure extends Factory {

    private $_handlers = [];

    public function __construct($spec = [])
    {
        if (is_array($spec))
        {
            foreach ($spec as $k=>$v)
            {
                $this->register($k, $v);
            }
        }
    }

    public function register($name, $cb)
    {
        $this->_handlers[strtolower($name)] = $cb;
    }

    public function registerByObject($object, $prefix = 'boot')
    {
        $rc = new \ReflectionClass($object);
        foreach($rc->getMethods() as $m)
        {
            if(0 === stripos($m->getName(), $prefix))
            {
                $name = substr($m->getName(), strlen($prefix));

                $this->register($name, $m->getClosure($object));
            }
        }
    }

    protected function canCreateImpl($spec)
    {
        return array_key_exists(strtolower($spec), $this->_handlers);
    }

    protected function createImpl($spec)
    {
        return call_user_func($this->getHandler($spec));
    }

    protected function getListImpl( )
    {
        return array_keys($this->_handlers);
    }

    protected function getHandler($spec)
    {
        $spec = strtolower($spec);
        return $this->_handlers[$spec];
    }
}
