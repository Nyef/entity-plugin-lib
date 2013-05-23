<?php
use Composer\Repository\ArrayRepository;

use Composer\Repository\RepositoryManager;

use Composer\Config;

use Composer\IO\NullIO;

use Composer\Composer;

use Hostnet\Entities\Installer\Installer;

class InstallerTest extends PHPUnit_Framework_TestCase
{
  public function testSupports()
  {
    $installer = new Installer($this->mockIO(), $this->mockComposer());
    $this->assertFalse($installer->supports('library'));
    $this->assertTrue($installer->supports('hostnet-entity'));
  }

  private function mockComposer()
  {
    $composer = new Composer();
    $composer->setConfig($this->mockConfig());
    $composer->setRepositoryManager($this->mockRepositoryManager());
    return $composer;
  }

  private function mockRepositoryManager()
  {
    $repository_manager = new RepositoryManager($this->mockIO(), $this->mockConfig());
    $repository_manager->setLocalRepository(new ArrayRepository());
    $repository_manager->setLocalDevRepository(new ArrayRepository());
    return $repository_manager;
  }

  private function mockIO()
  {
    return new NullIO();
  }

  private function mockConfig()
  {
    return new Config();
  }
}