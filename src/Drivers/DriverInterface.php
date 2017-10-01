<?php

namespace daweb\ResourceAccessManager\Drivers;
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( info@progresscode.pl )
 */
interface DriverInterface
{
    public function tryLock(string $id): bool;

    public function unlock(string $id): bool;
}