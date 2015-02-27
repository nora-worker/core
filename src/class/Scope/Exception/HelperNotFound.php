<?php
/**
 * のらライブラリのファイル
 */
namespace Nora\Core\Scope\Exception;

/**
 * ヘルパが見つからなかった時の処理
 */
class HelperNotFound extends \RuntimeException
{
    public function __construct ($name)
    {
        parent::__construct (
            sprintf ( "%s は存在しません", $name)
        );
    }
}

