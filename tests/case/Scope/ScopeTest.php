<?php
/**
 * スコープのテスト
 */
namespace Nora\Core\Scope;

/**
 * スコープのテストケース
 *
 * @coversDefaultClass \Nora\Core\Scope\Scope
 */
class ScopeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * スコープの作成
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::initScope
     */
    public function testConstruct ( )
    {
        $this->assertInstanceOf(
            'Nora\Core\Scope\Scope',
            $scope = Scope::create( )
        );
        return $scope;
    }

    /**
     * @covers ::__construct
     * @covers ::create
     * @covers ::getName
     * @covers ::setParent
     * @covers ::hasParent
     * @covers ::getParent
     * @covers ::rootScope
     * @covers ::initScope
     * @covers ::newScope
     */
    public function testTree ( )
    {
        $scope = Scope::create(null, 'parent');
        $child = Scope::create($scope, 'child');

        $this->assertFalse($scope->hasParent());
        $this->assertTrue($child->hasParent());
        $this->assertEquals($scope, $child->getParent());

        $child2 = $child->newScope("child2");

        $this->assertEquals($child, $child2->getParent());
        $this->assertEquals($scope, $child2->rootScope());

        $this->assertEquals('parent.child.child2', $child2->getName());
    }

    /**
     * コンポーネント絡み
     *
     * @covers ::initScopeComponents
     * @covers ::setComponent
     * @covers ::getComponent
     * @covers ::makeClosure
     */
    public function testComponents ( )
    {
        $scope = Scope::create(null, 'parent');

        $scope->setComponent([
            'hoge' => function ( ) {
                return new \StdClass( );
            },
            'fuga' => ['hoge', function(\StdClass $v) {
                $v->aaa = 'bbb';
                return $v;
            }]
        ])->setHelper([
            'getFuga' => ['fuga', function($fuga) {
                return $fuga;
            }]
        ]);

        $this->assertInstanceOf('StdClass', $scope->getComponent('hoge') );
        $this->assertInstanceOf('StdClass', $scope->newScope()->newScope()->getComponent('hoge') );

        $this->assertEquals('bbb', $scope->newScope()->fuga()->aaa);

        $c = $scope->makeClosure(['phpunit', function ($unit) {
            return $unit;
        }], ['phpunit' => $this]);

        $this->assertEquals($this, call_user_func($c));
        $this->assertEquals($scope->getComponent('fuga'), $scope->getFuga());

        return $scope;
    }

    /**
     * @depends testComponents
     * @covers Nora\Core\Scope\Exception\ComponentNotFound::__construct
     * @covers ::getComponent
     * @expectedException Nora\Core\Scope\Exception\ComponentNotFound
     */
    public function testComponentsNotFound ($scope)
    {
        $scope->getComponent('hugahuga');
    }

    /**
     * ヘルパ絡み
     *
     * @covers ::initScopeHelpers
     * @covers ::invokeHelper
     * @covers ::invokeHelperArray
     * @covers ::setHelper
     * @covers ::getHelper
     */
    public function testHelpers ( )
    {
        $scope = Scope::create(null, 'parent');

        $scope->setHelper([
            'hoge' => function ( ) {
                return "hello";
            }
        ]);
        $this->assertEquals(
            $scope->getHelper('hoge'),
            $scope->newScope()->getHelper('hoge')
        );


        // オーバーライドできる
        $scope->on('scope.helper.get', function ($ev) {
            if ($ev->name === 'hoge')
            {
                $ev->stopPropagation();

                $ev->helper = function ($name) {
                    return "hello world, from ".$name;
                };
            }
        });

        $this->assertEquals(
            'hello world, from hajime',
            $scope->invokeHelper('hoge', 'hajime')
        );


        $this->assertTrue($scope->hasHelper('hoge'));
        $this->assertFalse($scope->newScope()->hasHelper('hogefuga'));
        return $scope;
    }

    /**
     * @depends testHelpers
     * @covers ::gethelper
     * @covers Nora\Core\Scope\Exception\HelperNotFound::__construct
     * @expectedException Nora\Core\Scope\Exception\HelperNotFound
     */
    public function testHelperNotFound ($scope)
    {
        var_Dump(
            $scope->getHelper('hugahughogea')
        );
    }

    /**
     * マジック
     *
     * @covers ::__call
     * @covers ::hasComponent
     * @covers ::hasHelper
     * @covers ::initScopeComponents
     * @covers ::initScopeHelpers
     * @covers Nora\Core\Scope\Exception\InvalidMethodCalled::__construct
     * @expectedException Nora\Core\Scope\Exception\InvalidMethodCalled
     */
    public function testCall ( )
    {
        $s = Scope::create(null, 'parent')->setComponent('hoge', function ( ) {
            return new \StdClass();
        })->setHelper('fuga', function ( ) {
            return 'a';
        });

        $this->assertInstanceOf('StdClass', $s->newScope()->hoge());
        $this->assertEquals('a', $s->newScope()->fuga());

        $s->hogeho();
    }
}
