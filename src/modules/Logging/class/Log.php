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


/**
 * ログオブジェクト
 */
class Log
{
    /**
     * タグ
     * @var string
     */
    private $_tag;

    /**
     * ログメッセージ
     * @var string
     */
    private $_msg;

    /**
     * ログデータ
     * @var array
     */
    private $_args;

    /**
     * ログレベル
     * 
     * @see Nora\Log\LogLevel
     * @var array
     */
    private $_level;

    /**
     * ログタイム
     * 
     * @var int
     */
    private $_time;

    /**
     * 新しいログを作成
     *
     * @param string $tag
     * @param string $msg
     * @param array $args
     * @param int $level
     * @return Log
     */
    static public function create ($msg = '', $args = [], $level = LogLevel::NOTICE, $tag)
    {
        $log = new Log();
        $log->_tag   = is_array($tag) ? $tag: [$tag];
        $log->_args  = $args;
        $log->_msg   = $msg;
        $log->_level = $level;
        $log->_time  = time();
        return $log;
    }

    /**
     * ログオブジェクトを配列にする
     *
     * @return array
     */
    public function toArray ( )
    {
        return [
            '_tag'   => $this->getTag(),
            '_msg'   => $this->getMsg(),
            '_args'  => $this->getArgs(),
            '_level' => $this->getLevel(),
            '_time'  => $this->getTime( )
        ];
    }


    /**
     * タグを取得
     *
     * @return string
     */
    public function getTag( )
    {
        return implode(",", $this->_tag);
    }

    /**
     * メッセージを取得
     *
     * @return string
     */
    public function getMsg( )
    {
        return $this->_msg;
    }

    /**
     * データを取得
     *
     * @return array
     */
    public function getArgs( )
    {
        return $this->_args;
    }

    /**
     * ログレベルを取得
     *
     * @return int
     */
    public function getLevel( )
    {
        return (int) $this->_level;
    }

    /**
     * ログ時刻を取得
     *
     * @return int
     */
    public function getTime( )
    {
        return (int) $this->_time;
    }
}

