<?php
use Composer\IO\NullIO;
use Hostnet\Component\EntityPlugin\PackageClass;
use Hostnet\Component\EntityPlugin\PackageIOInterface;
use Hostnet\Component\EntityPlugin\ReflectionGenerator;

/**
 * More a functiononal test then a unit-test
 *
 * Tests (minimized versions of) cases that we've found in real-life
 * @author Nico Schoenmaker <nschoenmaker@hostnet.nl>
 */
class ReflectionGeneratorTest extends PHPUnit_Framework_TestCase
{
  /**
   * @dataProvider generateProvider
   * @param PackageClass $package_class
   */
  public function testGenerate(PackageClass $package_class)
  {
    require_once(__DIR__ . '/EdgeCases/'.$package_class->getShortName().'.php');
    $io = new NullIO();
    $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../src/Resources/templates/');
    $environment = new \Twig_Environment($loader);

    $package_io = $this->getMock('Hostnet\Component\EntityPlugin\PackageIOInterface');

    $that = $this;
    $package_io->expects($this->exactly(2))->method('writeGeneratedFile')->will($this->returnCallback(
        function($directory, $file, $data) use($that, $package_class) {
          $that->assertEquals($package_class->getGeneratedDirectory(), $directory);
          $short_name = $package_class->getShortName();
          if($file === $short_name.'TraitInterface.php') {
            $contents = file_get_contents(__DIR__ . '/EdgeCases/'.$short_name.'TraitInterface.expected.php');
          } else if($file === 'Abstract'.$short_name.'Trait.php') {
            $contents = file_get_contents(__DIR__ . '/EdgeCases/Abstract'.$short_name.'Trait.expected.php');
          } else {
            $this->fail('Unexpected file '. $file);
          }
          $that->assertEquals($contents, $data);
    }));

    $generator = new ReflectionGenerator($io, $environment, $package_io, $package_class);
    $this->assertNull($generator->generate());
  }

  public function generateProvider()
  {
    return array(
        array(new PackageClass('Hostnet\EdgeCases\Entity\ConstructShouldNotBePresent', __DIR__ . '/EdgeCases/ConstructShouldNotBePresent.php')),
        array(new PackageClass('Hostnet\EdgeCases\Entity\MultipleArguments',  __DIR__ . '/EdgeCases/MultipleArguments.php')),
        array(new PackageClass('Hostnet\EdgeCases\Entity\TypedParameters', __DIR__ . '/EdgeCases/TypedParameters.php'))
    );
  }

}