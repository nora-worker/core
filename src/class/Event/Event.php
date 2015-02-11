<?php
/**
 * のらライブラリのファイル
 */
namespace Nora\Core\Event;

use Nora\Core\Exception;
use Closure;

/**
 * イベント
 */
class Event implements EventIF
{
    private $_propagation = true;
    private $_params = [];
    private $_trace = [];
    private $_subject = null;
    private $_tags = [];

    /**
     * コンストラクタ
     *
     * @param array $params
     * @param mixed $subject
     */
    public function __construct ( )
    {
    }

    /**
     * イベントの作成
     *
     * @param array|string $tag
     * @param array $params
     * @param object|null $subject
     */
    static public function create ($tag, $params = [], $subject = null)
    {
        $e = new Event( );
        if (!is_array($tag)) {
            $tag = [$tag];
        }
        $e->_tags = $tag;
        $e->_params = $params;
        $e->setSubject($subject);
        return $e;
    }

    /**
     * タグを取得する
     */
    public function addTag($tag)
    {
        return $this->_tags[] = $tag;
    }

    /**
     * タグとマッチするか
     */
    public function match($tag)
    {
        return in_array($tag, $this->_tags);
    }

    /**
     * サブジェクトをセット
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    /**
     * サブジェクトの有無
     */
    public function hasSubject( )
    {
        return isset($this->_subject) && !is_null($this->_subject);
    }

    /**
     * サブジェクトを取得する
     */
    public function getSubject( )
    {
        return $this->_subject;
    }

    /**
     * パラム名を取得
     */
    public function getParamNames( )
    {
        return array_keys($this->_params);
    }

    /**
     * パラメタ操作
     */
    public function &__get($name)
    {
        if (!array_key_exists($name,$this->_params))
        {
            throw new Exception\InvalidProperty($name, $this);
        }
        return $this->_params[$name];
    }

    /**
     * パラメタ操作
     */
    public function __set($name, $value)
    {
        if (!array_key_exists($name,$this->_params))
        {
            throw new Exception\InvalidProperty($name, $this);
        }
        return $this->_params[$name] = $value;
    }

    /**
     * パラメタ操作
     */
    public function __isset($name)
    {
        return array_key_exists($name,$this->_params);
    }

    /**
     * 伝搬
     */
    public function stopPropagation ( )
    {
        $this->_propagation =  false;
    }

    /**
     * 伝搬が止まっているか
     */
    public function isStopPropagation ( )
    {
        return !$this->_propagation;
    }

    /**
     * トレースログをつける
     */
    public function trace ($file, $line)
    {
        $this->_trace[] = $file.' '.$line;
    }

    /**
     * トレースを取得する
     */
    public function getTrace ( )
    {
        return $this->_trace;
    }

    /**
     * 文字としてイベント情報を取得する
     */
    public function toString ( )
    {
        return json_encode(
            [
                'Event' => $this->_tags,
                'Params' => $this->_params,
                'Subject' => $this->hasSubject() ? get_class($this->getSubject()): 'NULL',
                'Trace' => $this->getTrace()
            ],

            JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
