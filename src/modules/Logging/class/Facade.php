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

    private $_logger_list = [];

    public function initModuleImpl( )
    {
        $this->rootScope( )->setHelper('getLogger', function ($name = null) {
            return $this->getLogger($name);
        });
    }

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
            $conf = $this->config()->get('loggers');

            if (isset($conf[$name]))
            {
                $this->addLogger($name, $this->config()->get('loggers')[$name]);
            }
            else
            {
                throw new Exception\LoggerNotFound($name);
            }
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

    /**
     * ロギングを開始する
     */
    public function start($spec = null, $level = 'debug')
    {
        if ($spec === null)
        {
            $spec = [
                /*
                [
                    'type' => 'file',
                    'file' => $path = $this->FileSystem()->getPath('@log/nora-debug.log.%(user).%(date)'),
                    'level'=> $level
                ],
                 */
                [
                    'type' => 'stdout',
                    'level'=> $level
                ]
            ];
        }

        $this->addLogger("_default", $spec);

        return $this->getLogger()->apply($this->rootScope());
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
    protected function bootConfig($setting = [])
    {
        return  parent::bootConfig([
            'loggers' => [
                '_default' => [
                    [
                        'type' => 'stdout',
                        'level' => 'debug'
                    ]
                ]
            ]
        ]);
    }
}
