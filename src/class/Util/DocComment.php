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
 * DocComment
 */
class DocComment
{
    private $_comment;
    private $_attrs;

    static function parseMethods($class)
    {
        $rc = new \ReflectionClass($class);

        foreach($rc->getMethods() as $m)
        {
            yield $m=>DocComment::parse($m->getDocComment());
        }
    }

    static public function parse ($comment)
    {
        $lines   = explode("\n", $comment);
        $comment = [];
        $attrs   = [];

        foreach($lines as $line)
        {
            $line = trim($line);
            $line = ltrim($line, '* ');

            if ($line === '/**' || $line === '/') continue;

            if ($line{0} !== '@')
            {
                $comment[] = $line;
                continue;
            }

            $tok = ltrim(strtok($line, ' '), ' @');

            if ($value = strtok('_____'))
            {
                $attrs[$tok][] = $value;
            }else{
                $attrs[$tok][] = true;
            }
        }
        return new DocComment(trim(implode("\n",$comment)), $attrs);
    }

    public function __construct ($comment, $attrs)
    {
        $this->_comment = $comment;
        $this->_attrs = $attrs;
    }

    public function get($name) 
    {
        return $this->_attrs[$name][0];
    }

    public function has($name) 
    {
        return isset($this->_attrs[$name]);
    }

    public function which($name)
    {
        foreach(func_get_args() as $name)
        {
            if($this->has($name))
            {
                return $name;
            }
        }
        return false;
    }

    public function gets($name) 
    {
        foreach ($this->_attrs[$name] as $v) 
        {
            yield $v;
        }
    }
}
