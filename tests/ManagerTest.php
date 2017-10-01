<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( info@progresscode.pl )
 */

namespace daweb\ResourceAccessManager\tests;

use daweb\ResourceAccessManager\Drivers\MemcachedDriver;
use daweb\ResourceAccessManager\Drivers\FileDriver;
use daweb\ResourceAccessManager\Manager;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{

    private function getManagerWithFileDriver()
    {
        Manager::clear();

        return new Manager(new FileDriver());
    }

    private function getManagerWithMemcachedDriver()
    {
        Manager::clear();

        $memcached = new \Memcached();
        $memcached->addServer('127.0.0.1', 11211);
        return new Manager(new MemcachedDriver($memcached));
    }

    public function test_file_driver_try_lock()
    {
        $manager = $this->getManagerWithFileDriver();

        $uidForResource = '123';

        $this->assertTrue($manager->tryLock($uidForResource));

    }

    public function test_memcached_driver_try_lock()
    {
        $manager = $this->getManagerWithMemcachedDriver();

        $uidForResource = '123';

        $this->assertTrue($manager->tryLock($uidForResource));

    }

    public function test_file_driver_two_proccess_try_access_to_the_same_resource()
    {
        $manager = $this->getManagerWithFileDriver();

        $uidForResource = '123';

        $manager->tryLock($uidForResource);

        $this->assertFalse($manager->tryLock($uidForResource));

    }
    public function test_memcached_driver_two_proccess_try_access_to_the_same_resource()
    {
        $manager = $this->getManagerWithMemcachedDriver();

        $uidForResource = '123';

        $manager->tryLock($uidForResource);

        $this->assertFalse($manager->tryLock($uidForResource));

    }
    public function test_file_driver_unlock()
    {
        $manager = $this->getManagerWithFileDriver();

        $uidForResource = '123';

        $manager->tryLock($uidForResource);

        $this->assertTrue($manager->unlock($uidForResource));

    }
    public function test_memcached_driver_unlock()
    {
        $manager = $this->getManagerWithMemcachedDriver();

        $uidForResource = '123';

        $manager->tryLock($uidForResource);

        $this->assertTrue($manager->unlock($uidForResource));

    }
}