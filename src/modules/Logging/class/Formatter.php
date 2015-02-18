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

use Nora\Core\Util\Collection\Hash;

/**
 * ログフォーマッタ
 */
class Formatter
{
    const CLASS_FORMAT=__namespace__.'\Formatter\%s';

    /**
     * ロガフォーマッタを作成
     */
    static public function build ($spec)
    {
        $spec = Hash::create(Hash::NO_CASE_SENSITIVE, $spec);

        $class = sprintf(
            self::CLASS_FORMAT,
            ucfirst($spec->get('type', 'string'))
        );


        $formatter = new $class($spec);

        return $formatter;
    }

}
