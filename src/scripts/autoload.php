<?php
/**
 * Nora Project
 *
 * クラスのローディング
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */

require_once realpath(__DIR__.'/../').'/class/Nora.php';


// オートローダを登録する
Nora::Autoloader( )->register();
