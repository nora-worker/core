<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */

namespace Nora\Core\Module;

use Nora\Core\Component\Component;
use Nora\Core\Util\Collection\Hash;

/**
 * モジュールクラス
 */
class Module extends Component implements ModuleIF
{
    /**
     * Facade取得
     */
    static public function facade ( )
    {
        $class = get_called_class();
        return ['scope', function($scope) use ($class){
            return new $class($scope->newScope());
        }];
    }

    protected function initComponent( )
    {
        parent::initComponent( );
        $this->initModule();
    }

    protected function initModule( )
    {
        foreach(get_class_methods($this) as $m)
        {
            if (0 === strpos($m,'boot'))
            {
                $this->setComponent(
                    substr($m,4),
                    function ( ) use ($m) {
                        return $this->{$m}( );
                    }
                );
            }
        }
    }

    public function configure($array)
    {
        // コンフィグをマージする
        $this->config( )->merge($array);

        $this->afterConfigure();
    }

    protected function afterConfigure( )
    {
    }

    /** 
     * コンフィグオブジェクトを作成する
     */
    protected function bootConfig($settings = [])
    {
        return  new Config($settings);
    }
}
