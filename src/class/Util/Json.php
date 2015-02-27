<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Util;

/**
 * Json Util
 *
 * 
 *   JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE
 */
class Json
{
    const FLG_PRINT=JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES;

    /**
     * Prety Print
     */
    public function pp($var)
    {
        if (is_string($var))
        {
            return self::pp(
                self::decode($var)
            );
        }
        echo self::encode($var, self::FLG_PRINT);
    }

    public function decode($var, $assoc = true, $depth = 512, $options = JSON_BIGINT_AS_STRING)
    {
        return json_decode($var, $assoc, $depth, $options);
    }

    public function encode($var, $flag = null)
    {
        if ($flag === null)
        {
            $flag = 0;
        }
        return json_encode($var, $flag);
    }
}
