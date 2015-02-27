<?php
/**
 * モジュール用のローダ
 */
return ['Autoloader', function ($al) {

    $this->addModuleNS('Nora\Test\Module');

    $al->addLibrary(
        __DIR__.'/Hoge2/class',
        'Nora\Test\Module\Hoge2'
    );
}];

