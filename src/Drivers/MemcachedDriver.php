<?php

namespace daweb\ResourceAccessManager\Drivers;
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( info@progresscode.pl )
 */
class MemcachedDriver implements DriverInterface
{
    /**
     * @var \Memcached
     */
    private $memcached;

    public function __construct(\Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    public function tryLock(string $id): bool
    {
        return $this->memcached->add($id, 1);
    }

    public function unlock(string $id): bool
    {
        return $this->memcached->delete($id);
    }

}