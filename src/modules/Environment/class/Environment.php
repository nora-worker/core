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

use Nora\Core\Component\Component;
use Nora\Core\Util\Collection\Hash;

/**
 * Environmentクラス
 */
class Environment extends Component
{
    private $_SERVER;
    private $_ENV;

    public function initComponentImpl ( )
    {
        $this->_SERVER = Hash::create(
            Hash::NO_CASE_SENSITIVE, $_SERVER
        );
        $this->_ENV = Hash::create(
            Hash::NO_CASE_SENSITIVE, $_ENV
        );
    }

    public function bootPHP( )
    {
        return new PHPFunctionWrapper( );
    }

    public function bootDetector( )
    {
        $detector = new Detector($this);
        return $detector;
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

    /**
     * サーバ変数を取得
     */
    public function _SERVER( )
    {
        return $this->_SERVER;
    }

    /**
     * サーバ変数を取得
     */
    public function _ENV( )
    {
        return $this->_ENV;
    }

    public function get($name, $value = null)
    {
        if ($this->_SERVER()->has($name))
        {
            return $this->_SERVER()->get($name);
        }
        elseif ($this->_ENV()->has($name))
        {
            return $this->_ENV()->get($name);
        }

        return $value;
    }
}
