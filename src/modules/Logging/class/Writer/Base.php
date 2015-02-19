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

use Nora\Core\Component\Component;
use Nora\Core\Util\Collection\Hash;
use Nora\Module\Logging\Log;
use Nora\Module\Logging\Formatter;

/**
 * ログライター
 */
abstract class Base
{
    private $_spec;
    private $_formatter;

    public function __construct(Hash $spec)
    {
        $this->_spec = $spec;
    }

    protected function spec( )
    {
        return $this->_spec;
    }

    public function getFormatter( )
    {
        if ($this->_formatter)
            return $this->_formatter;

        $format = $this->spec()->get('format', '%(time) %(tag) %(level) %(msg) %(args)');

        $this->_formatter = Formatter::build([
            'type' => 'string',
            'format' => $format
        ]);

        return $this->getFormatter();
    }

    public function write(Log $log)
    {
        $this->writeImpl($log);
    }

    abstract protected function writeImpl(Log $log);
}

