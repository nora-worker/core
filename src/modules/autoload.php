<?php
/**
 * モジュール用のローダ
 */
return ['Autoloader', function ($al) {

    $this->addModuleNS('Nora\Module');

    $al->addLibrary(
        __DIR__.'/FileSystem/class',
        'Nora\Module\FileSystem'
    );
    $al->addLibrary(
        __DIR__.'/Devel/class',
        'Nora\Module\Devel'
    );
    $al->addLibrary(
        __DIR__.'/Logging/class',
        'Nora\Module\Logging'
    );
}];

