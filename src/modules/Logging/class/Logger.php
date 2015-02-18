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

use Nora\Core\Component\Component;

/**
 * ロガー
 */
class Logger
{
    private $_writer_list;

    /**
     * ロガーを作成する
     *
     * 設定にはライターのスペックが複数個入る
     */
    static public function build ($writer_specs)
    {
        $logger = new Logger( );
        foreach($writer_specs as $spec)
        {
            $logger->addWriter($spec);
        }
        return $logger;
    }

    /**
     * ログライター
     */
    public function addWriter($spec)
    {
        $this->_writer_list[] = Writer::build($spec);
    }

    /**
     * ログを送信する
     */
    public function post(Log $log)
    {
        foreach($this->_writer_list as $w)
        {
            $w->write($log);
        }
    }

    /**
     * ログ出力
     *
     * @param string $msg
     * @param array $args
     * @param string $tag
     */
    public function info ($msg= '', $args = [], $tag = null)
    {
        $this->post(Log::create(
            $msg,
            $args,
            LogLevel::INFO,
            $tag
        ));
    }

    /**
     * ログ出力
     */
    public function __call($name, $args)
    {
        if (false !== $level = LogLevel::toInt($name))
        {
            $this->post(Log::create(
                isset($args[0]) ? $args[0]: 'no message',
                isset($args[1]) ? $args[1]: [],
                $level,
                isset($args[2]) ? $args[2]: null
            ));
            return true;
        }

        throw new Exception\InvalidMethod($name, $this);
    }
}
