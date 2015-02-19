<?php
/**
 * モジュール用のローダ
 */
return ['Autoloader', function ($al) {

    $this->addModuleNS('Nora\Module');

    $al->addLibrary([
        'Nora\Module\FileSystem'  => __DIR__.'/FileSystem/class',
        'Nora\Module\Devel'       => __DIR__.'/Devel/class',
        'Nora\Module\Logging'     => __DIR__.'/Logging/class',
        'Nora\Module\Environment' => __DIR__.'/Environment/class'
    ]);
}];

