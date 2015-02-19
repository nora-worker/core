<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Environment;

use Nora\Core\Module\Module;

/**
 * Environment モジュール
 */
class Facade extends Module
{
    protected function initModuleImple( )
    {
    }

    public function register ( )
    {
        // ハンドラ系
        set_error_handler([$this, 'phpErrorHandler']);
        set_exception_handler([$this, 'phpExceptionHandler']);
        register_shutdown_function([$this, 'phpShutdownHandler']);

        return $this;
    }

    /**
     *  int $errno , string $errstr [, string $errfile [, int $errline [, array $errcontext ]
     */
    public function phpErrorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->fire('php.error', [
            'errno'      => $errno,
            'errstr'     => $errstr,
            'errfile'    => $errfile,
            'errline'    => $errline,
            'errcontext' => $errcontext
        ]);
    }

    /**
     */
    public function phpExceptionHandler($exception)
    {
        $this->fire('php.exception', [
            'exception' => $exception
        ]);
    }

    /**
     */
    public function phpShutdownHandler( )
    {
        $error = error_get_last();
        if ($error['type'] == 1) {
            $this->phpErrorHandler(
                $error['errno'],
                $error['errstr'],
                $error['errfile'],
                $error['errline'],
                $error['errcontext']
            );
        }
        $this->fire('php.shutdown');
    }

    protected function afterConfigure( )
    {
    }

    /** 
     * コンフィグオブジェクトを作成する
     */
    protected function bootConfig( )
    {
        return  parent::bootConfig([
            'logger' => '_default'
        ]);
    }
}
