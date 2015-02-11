<?php
/**
 * のらライブラリのファイル
 */
namespace Nora\Core\Scope\Exception;

/**
 * コンポーネントが見つからなかった時の処理
 */
class ComponentNotFound extends \RuntimeException
{
    public function __construct ($name)
    {
        parent::__construct (
            sprintf ( "%s は存在しません", $name)
        );
    }
}

