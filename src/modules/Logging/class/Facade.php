<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Logging;

use Nora\Core\Module\Module;

/**
 * Loggingモジュール
 */
class Facade extends Module
{
    const DEFAULT_LOGGER="_default";

    private $_logger_list;

    /**
     * ロガーを作成する
     */
    public function newLogger( )
    {
        return new Logger($this->newScope());
    }

    /**
     * ロガーを取得する
     */
    public function getLogger($name = null)
    {
        if ($name === null) $name = self::DEFAULT_LOGGER;

        if (!array_key_exists($name, $this->_logger_list))
        {
            throw new Exception\LoggerNotFound($name);
        }

        return $this->_logger_list[$name];
    }

    /**
     * ロガーをセットする
     */
    public function addLogger($name, $v)
    {
        $this->_logger_list[$name] = Logger::build($v);
    }

    protected function afterConfigure( )
    {
        // ロガーのセットアップ
        foreach($this->config()->get('loggers') as $k =>$v)
        {
            $this->addLogger($k, $v);
        }
    }

    /** 
     * コンフィグオブジェクトを作成する
     */
    protected function bootConfig( )
    {
        return  parent::bootConfig([
            'loggers' => []
        ]);
    }
}
