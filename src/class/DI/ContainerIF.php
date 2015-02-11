<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\DI;

/**
 * DIContainerパターンのインターフェイス
 */
interface ContainerIF
{
    /**
     * ファクトリの登録
     *
     * @param string $name
     * @param callable $spec 
     */
    public function register ($name, $spec = null);

    /**
     * インスタンスの取得
     *
     * @param string $name
     * @param bool $nocache
     * @return mixed 
     */
    public function get ($name, $nocache = false);
}
