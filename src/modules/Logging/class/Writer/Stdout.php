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
 * ログライター
 */
class Stdout extends Base
{
    public function writeImpl(Log $log)
    {

        echo
            $this->getFormatter( )->format($log)."\n";
    }
}

