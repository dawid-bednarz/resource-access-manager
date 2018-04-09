<?php

namespace daweb\ResourceAccessManager\Drivers;

class SocketDriver implements DriverInterface
{
    private $socket;

    public function __construct(string $ip = null, int $port = 1025)
    {
        if (is_null($ip))
            $this->ip = getHostByName(getHostName());
        else
            $this->ip = $ip;

        $this->port = $port;
    }

    public function tryLock(string $id): bool
    {
        $this->socket = socket_create(\AF_INET, \SOCK_STREAM, \SOL_TCP);

        if ($this->socket === false) {

            throw new SocketDriverException("socket_create() failed: reason: " . socket_strerror(socket_last_error()));

            return false;
        }
        if (socket_bind($this->socket, $this->ip, $this->port) === false) {

            throw new SocketDriverException("socket_bind() failed: reason: " . socket_strerror(socket_last_error($this->socket)));

            return false;
        }
        return true;
    }

    public function unlock(string $id): bool
    {
        socket_close($this->socket);

        return true;
    }
}

class SocketDriverException extends \Exception
{
}