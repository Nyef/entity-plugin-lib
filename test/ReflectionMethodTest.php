<?php
namespace Hostnet\Component\EntityPlugin;

use phpunit\framework\TestCase;

/**
 * @covers Hostnet\Component\EntityPlugin\ReflectionMethod
 */
class ReflectionMethodTest extends TestCase
{
    private $method;

    /**
     * Blaah Blaah Blaaaah Cloud...
     *
     * @param Generated\Foo $foo
     * @param unknown $empty
     * @return Generated\Blyp
     */
    public function docBlock($param_1, array $param_2)
    {
        return 'quite useless, we only need the docblock...';
    }

    protected function setUp()
    {
        $this->method = new ReflectionMethod(new \ReflectionMethod(__CLASS__, 'docBlock'));
    }

    public function testGetName()
    {
        $this->assertEquals('docBlock', $this->method->getName());
    }

    public function testIsStatic()
    {
        $this->assertFalse($this->method->isStatic());
    }

    public function testIsPublic()
    {
        $this->assertTrue($this->method->isPublic());
    }

    public function testGetParameters()
    {
        $this->assertEquals(2, count($this->method->getParameters()));
    }

    public function testGetDocComment()
    {
        $expected = <<<'EOS'
/**
     * Blaah Blaah Blaaaah Cloud...
     *
     * @param Foo $foo
     * @param unknown $empty
     * @return Blyp
     */
EOS;
        $this->assertEquals($expected, $this->method->getDocComment());
    }
}
