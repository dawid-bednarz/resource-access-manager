<?php

namespace daweb\ResourceAccessManager;

use daweb\ResourceAccessManager\Drivers\DriverInterface;

/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( info@progresscode.pl )
 * Facade for management process locking access to resource
 *
 * WARNING:
 * before use in your main script at the very top place make paste below definition
 * register_shutdown_function(['daweb\ResourceAccessManager\Manager','clear'])
 */
class Manager
{
    /**
     * @var DriverInterface
     */
    private static $driver;
    /**
     * simple cache for blocked resources
     * @var array
     */
    private static $bussy = [];

    public function __construct(DriverInterface $driver)
    {
        if (!is_null(self::$driver)) {

            throw new ManagerException('Before repeated initialize clear manager "Manager::clear()"');
        }
        self::$driver = $driver;
    }

    private function prepareSafeId(string $id)
    {
        return md5($id);
    }

    public function tryLock(string $id): bool
    {
        $safeID = $this->prepareSafeId($id);

        if (in_array($safeID, self::$bussy)) {

            return false;
        }
        self::$bussy[] = $safeID;

        return self::$driver->tryLock($safeID);
    }

    public function unlock(string $id): bool
    {
        $safeID = $this->prepareSafeId($id);

        unset(self::$bussy[array_search($safeID, self::$bussy)]);

        return self::$driver->unlock($safeID);
    }
    /**
     *
     * This method make unlocking all resource
     * Register this method in your main script at the top place as:
     * register_shutdown_function(['daweb\ResourceAccessManager\Manager','clear'])
     *
     */
    public static function clear()
    {
        foreach (self::$bussy as $id) {

            self::$driver->unlock($id);
        }
        self::$driver = null;
        self::$bussy = [];
    }
}