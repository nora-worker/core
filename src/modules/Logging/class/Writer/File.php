<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Logging\Writer;

use Nora\Module\Logging\Log;
use Nora\Module\Logging\Formatter;

/**
 * ログライター For File
 */
class File extends Base
{
    /**
     * フォーマットオーダー
     * @var array
     */
    private $_format_order = [
        1 => 'date',
        2 => 'user',
    ];

    /**
     * ログファイル
     */
    private $_file;

    /**
     * 書き込みモード
     */
    private $_mode;


    /**
     * 初期処理
     */
    protected function initWriterImpl ( )
    {
        $file = $this->spec()->get('file');

        $file = preg_replace_callback('/%\((.+)\)/U', function ($m) {
            $num = array_search($m[1], $this->_format_order);

            if ($num === false)
            {
                throw new \RuntimeException(
                    $m[1]." is invalid for LogFormat Enable Pramas are ".
                    var_export($this->_format_order, true). " Given ".$format
                );
            }

            return '%'.$num.'$s';
            
        }, $file);

        $this->_file = vsprintf($file, [
            date('Y-m-d'),
            posix_getpwuid(posix_getuid())['name']
        ]);

        $this->_mode = $this->spec()->get('mode', 'a');
    }

    public function writeImpl(Log $log)
    {
        $line = $this->getFormatter( )->format($log);

        $fp = fopen($this->_file, $this->_mode);
        flock($fp, LOCK_EX);
        fwrite($fp, $line."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
