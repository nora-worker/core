<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Component;

use Nora\Core\Scope\Scope;

/**
 * @coversDefaultClass Nora\Core\Component\Component
 */
class ComponentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::initComponent
     * @covers ::__call
     * @covers ::scope
     */
    public function testComponent ()
    {
        $scope = Scope::create(null, 'ComponentScope');
        $scope->observe(function($ev) {
            //var_dump($ev->toString());
            if ($ev->match("component.pre_initcomponent"))
            {
                $this->assertInstanceOf(__namespace__.'\\Component', $ev->getSubject());
            }

        });
        $comp = new Component($scope);
        $this->assertEquals($scope, $comp->scope());

        $comp->setHelper('test', ['scope','component', function ($s, $c) {
            return $s;
        }]);

        $this->assertEquals($comp, $comp->test());
    }


}
