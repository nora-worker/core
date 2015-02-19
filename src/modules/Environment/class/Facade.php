<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Environment;

use Nora\Core\Module\Module;

/**
 * Environment モジュール
 */
class Facade extends Module
{
    protected function initModuleImpl( )
    {
        $this->rootScope( )->setComponent('Environment', function ( ) {
            return new Environment($this->scope());
        });
    }

    public function register ( )
    {
        return $this->Environment()->register();
    }

    protected function afterConfigure( )
    {
    }

    /** 
     * コンフィグオブジェクトを作成する
     */
    protected function bootConfig( )
    {
        return  parent::bootConfig([
            'logger' => '_default'
        ]);
    }
}
