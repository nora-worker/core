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

/**
 * 検知クラス
 *
 * @author     Hajime MATSUMOTO <hajime.matsumoto@avap.co.jp>
 * @copyright  Since 2015 Nora Project
 * @license    http://nora.avap.co.jp/license.txt
 */
interface DetectorIF
{

    /**
     * 環境検知を実行する
     *
     * @param string $name 種別
     * @return bool 結果
     */
    public function is($name);

    /**
     * 環境検知ロジックを追加する
     *
     * @param string $name
     * @param mixed $value
     */
    public function addDetector($name, $value = null);
}
