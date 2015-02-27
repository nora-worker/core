<?php
/**
 * のらライブラリのファイル
 */
namespace Nora\Core\Scope\Exception;

/**
 * 未定義のメソッドコール時
 */
class InvalidMethodCalled extends \RuntimeException
{
    public function __construct ($name)
    {
        parent::__construct (
            sprintf ( "%s は存在しません", $name)
        );
    }
}

