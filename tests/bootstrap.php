<?php
/**
 * Nora Project
 *
 * テスト起動時に読み込ませる
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.org>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.org/LICENCE
 * @version 1.0.0
 */

define('TEST_PROJECT_PATH', __DIR__);

require_once realpath(TEST_PROJECT_PATH.'/..').'/src/scripts/autoload.php';
