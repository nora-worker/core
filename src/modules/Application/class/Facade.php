<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Application;

use Nora\Core\Component\Component;
use Nora\Core\Util\Collection\Hash;
use Nora\Core\Scope\ScopeIF;

/**
 * コンポーネント
 */
class Facade extends Component
{
    /**
     * レジスター
     */
    static public function register(ScopeIF $scope, Hash $settings)
    {
        $scope->setComponent('Application', ['scope', function ( ) use ($settings) {
            return $settings;
        }]);
    }

    public function __construct (ScopeIF $scope, Hash $settings)
    {
        $scope->env  = $settings->get('env', 'prod');
        $scope->path = $settings->get('path', __DIR__);
        parent::__construct($scope);
    }

    protected function initComponent( )
    {
        parent::initComponent( );
        var_Dump(
            $this->FileSystem()
        );

        $this->setComponent([
            'Facade' => function ( ) {
                return $this;
            },
            'FileSystem' => function ( ) {
                return new \StdClass();
            }
        ]);

        var_Dump(
            $this->FileSystem()
        );

    }

    /**
     * @component environment
     */
    public function bootEnvironment( )
    {
    }
}
