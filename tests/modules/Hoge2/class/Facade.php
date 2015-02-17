<?php
namespace Nora\Test\Module\Hoge2;

use Nora\Core\Module\Module;

class Facade extends Module
{

    protected function bootConfig ( )
    {
        $config = parent::bootConfig(['a'=>'hoge', 'b'=>'fuga']);
        return $config;
    }

    protected function afterConfigure ( )
    {
        $a = $this->config()->a;
        $b = $this->config()->b;

        $this->a = $a;
        $this->b = $b;
    }

    public function sayValueOfA( )
    {
        return $this->a;
    }

    public function sayValueOfB( )
    {
        return $this->b;
    }
}
