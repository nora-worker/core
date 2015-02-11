<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Factory;

use Nora\Core\Exception;

/**
 * ファクトリクラス
 */
abstract class Factory implements FactoryIF {

    private $_factories = [];

    static public function createFactory ($spec = null) {

        if (is_null($spec) || is_array($spec))
        {
            return new FactoryClosure($spec);
        }
        if (is_object($spec))
        {
            $f = new FactoryClosure([]);
            $f->registerByObject($spec);
            return $f;
        }
        throw new Exception\InvalidSpec(__CLASS__, __METHOD__, $spec);
    }

    /**
     * ファクトリを追加する
     *
     * @param FactoryIF $factory
     */
    public function addFactory (FactoryIF $factory) {

        $this->_factories[] = $factory;
        return $this;
    }

    /**
     * オブジェクトを生成する
     *
     */
    public function create($spec) {

        if ($this->canCreateImpl($spec))
        {
            return $this->createImpl($spec);
        }

        foreach($this->_factories() as $factory)
        {
            if ($factory->canCreate($spec))
            {
                return $factory->create($spec);
            }
        }
        return false;
    }

    /**
     * ファクトリイテレータ
     */
    private function _factories() {
        foreach($this->_factories as $f)
        {
            yield $f;
        }
    }

    /**
     * オブジェクトが生成可能かチェック
     */
    public function canCreate($spec)
    {
        if ($this->canCreateImpl($spec))
        {
            return true;
        }

        foreach($this->_factories() as $factory)
        {
            if ($factory->canCreate($spec))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * 生成可能なオブジェクトリスト
     */
    public function getList( )
    {
        $list = $this->getListImpl();
        foreach($this->_factories() as $factory)
        {
            $child_list = $factory->getList();
            foreach($child_list as $k)
            {
                if (array_search($k, $list))
                {
                    continue;
                }
                $list[] = $k;
            }
        }
        return $list;
    }

    abstract protected function canCreateImpl($name);
    abstract protected function createImpl($name);
    abstract protected function getListImpl( );
}
