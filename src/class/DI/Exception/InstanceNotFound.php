<?php
/**
 * のらライブラリのファイル
 */
namespace Nora\Core\DI\Exception;
use Nora\Core\Exception\Exception;


/**
 * DIコンテナ用のエラー
 */
class InstanceNotFound extends Exception
{
    public function __construct ($name, $container)
    {
        parent::__construct("$name is not found");
    }
}

