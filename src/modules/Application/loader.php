<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Application;

/**
 * module loader
 */
return ['AutoLoader', 'scope', 'settings', function ($al, $scope, $settings) {

    // オートロードの設定
    $al->addLibrary(__dir__.'/class', __namespace__ );

    // 依存モジュールのチェック
    $scope->checkModule( 'FileSystem' );

    return new Application($scope->newScope(), $settings);
}];
