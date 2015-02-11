<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */

namespace Nora\Core\Scope;

/**
 * スコープ用インターフェイス
 */
interface ScopeIF
{
    /**
     * 親スコープを取得する
     *
     * @return ScopeIF
     */
    public function getParent( );

    /**
     * 親スコープがあるか
     *
     * @return bool
     */
    public function hasParent( );

    /**
     * 最上位のスコープを取得する
     *
     * @return bool
     */
    public function rootScope( );

    /**
     * 新しいチャイルドスコープを取得する
     */
    public function newScope($name = "child");

    /**
     * コンポーネントの読み込み
     */
    public function getComponent($name);

    /**
     * コンポーネントがあるか
     */
    public function hasComponent($name);

    /**
     * コンポーネントの登録
     */
    public function setComponent($name, $spec = null);

    /**
     * ヘルパの読み込み
     */
    public function getHelper($name);

    /**
     * ヘルパの存在確認
     */
    public function hasHelper($name);

    /**
     * ヘルパの登録
     */
    public function setHelper($name, $spec = null);

    /**
     * ヘルパの実行
     */
    public function invokeHelper($name);

    /**
     * ヘルパの実行
     */
    public function invokeHelperArray($name, $args);
}

