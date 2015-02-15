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

/**
 * モジュールクラス
 */
class Module extends Component implements ModuleIF
{
    static public function create(Scope $scope = null)
    {
        $class = get_called_class();

        $module = new $class();
        $module->_scope = $scope;

        // スコープに所有オブジェクトの変更を伝える
        $scope->accept($module);

        // イニシャライズ前イベントをディスパッチ
        $module->dispatch('module.pre_init');

        $module->initModule( );

        // イニシャライズ後イベントをディスパッチ
        $module->dispatch('component.post_init');
        return $module;
    }

    protected function initModule( )
    {
        parent::initComponent( );
    }

}
