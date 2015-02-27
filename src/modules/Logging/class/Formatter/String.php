<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Logging\Formatter;

use Nora\Module\Logging\Log;
use Nora\Module\Logging\LogLevel;

/**
 * ベタなテキスト形式にフォーマットする
 */
class String 
{
    /**
     * ログフォーマット
     *
     * @var string
     */
    private $_format = "%(time) %(tag) %(level) %(msg) %(args)";

    /**
     * 日時フォーマット
     *
     * @var string
     */
    private $_date_format = "Y-m-d G:i:s";

    /**
     * 日時フォーマットオーダー
     *
     * @var array
     */
    private $_format_order = [
        1 => 'tag',
        2 => 'msg',
        3 => 'level',
        4 => 'args',
        5 => 'time',
    ];

    /**
     * ログフォーマットを設定する
     *
     * @param array config
     */
    public function __construct($config = [])
    {
        $this->_compiled_format      = $this->compileFormat(
            $config->get('format', $this->_format)
        );

        if ($config->has('date_format')) {
            $this->_date_format = $config->get('date_format');
        }
    }

    /**
     * フォーマットをコンパイルしておく
     */
    private function compileFormat($format)
    {
        return preg_replace_callback('/%\((.+)\)/U', function ($m) use ($format){
            $num = array_search($m[1], $this->_format_order);

            if ($num === false)
            {
                throw new \RuntimeException(
                    $m[1]." is invalid for LogFormat Enable Pramas are ".
                    var_export($this->_format_order, true). " Given ".$format
                );
            }

            return '%'.$num.'$s';
            
        }, $format);
    }

    /**
     * ログを整形する
     *
     * @param Log $log
     * @return string
     */
    public function format (Log $log)
    {
        return vsprintf(
            $this->_compiled_format,
            [
                '['. $log->getTag().']',
                $log->getMsg( ),
                LogLevel::toString($log->getLevel( )),
                json_encode($log->getArgs( ), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),
                date($this->_date_format, $log->getTime())
            ]
        );
    }
}


