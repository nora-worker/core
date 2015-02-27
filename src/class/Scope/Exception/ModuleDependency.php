<?php
/**
 * のらライブラリのファイル
 */
namespace Nora\Core\Scope\Exception;

class ModuleDependency extends \RuntimeException
{
    public function __construct ($name)
    {
        parent::__construct (
            sprintf ( "モジュール依存エラー %s", $name)
        );
    }
}

