<?php
require 'vendor/autoload.php';
use daweb\ResourceAccessManager\Manager;
use daweb\ResourceAccessManager\Drivers\FileDriver;

/**
 *
 * this is very important definition must by in the very top place in script
 *
 */
register_shutdown_function(['daweb\ResourceAccessManager\Manager', 'clear']);

/**
 * prefers be a singleton
 */
$GLOBALS['resourceAccessManager'] = new Manager(new FileDriver);


/* for better preformace use memcached driver
$memcached = new \Memcached();
$memcached->addServer('127.0.0.1', 11211);
$GLOBALS['resourceAccessManager'] = new Manager(new MemcachedDriver($memcached));
*/
$mtime = microtime(true);

function getFreeProxy()
{
    /**
     * proxies from database
     */
    $proxies = [
        [
            'id' => '1',
            'proxy' => '11.11.11.11'
        ],
    ];
    foreach ($proxies as $proxy) {

        if ($GLOBALS['resourceAccessManager']->tryLock($proxy['id'])) {

            sleep(1);

            return $proxy;

        }
    }

    return 'there is no free proxies';
}

/**
 * when you run many this script at the same time only one of him return proxy
 */

var_dump(getFreeProxy());
echo $mtime . "\n";
