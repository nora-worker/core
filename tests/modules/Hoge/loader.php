<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Test\Module\Hoge;

use Nora;

/**
 * module loader
 */
return ['scope', function ($scope) {

    // オートロードの設定
    Nora::AutoLoader()->addLibrary(__dir__.'/class', __namespace__ );

    return new Hoge($scope->newScope());
}];

