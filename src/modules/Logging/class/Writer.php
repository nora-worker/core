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
use Nora\Core\Util\Collection\Hash;

/**
 * ログライター
 */
class Writer
{
    const WRITER_CLASS_FORMAT=__namespace__.'\Writer\%s';

    /**
     * ログライタを作成する
     */
    static public function build ($spec)
    {
        $spec = Hash::create(Hash::NO_CASE_SENSITIVE, $spec);

        $class = sprintf(
            self::WRITER_CLASS_FORMAT,
            ucfirst($spec->get('type', 'stdout'))
        );

        $writer = new $class($spec);

        return $writer;
    }

}
