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
interface FactoryIF {

    public function addFactory(FactoryIF $factory);
    public function create($spec);
    public function canCreate($name);
}
