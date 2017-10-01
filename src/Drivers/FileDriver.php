<?php

namespace daweb\ResourceAccessManager\Drivers;
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( info@progresscode.pl )
 */
class FileDriver implements DriverInterface
{
    private $tmpDir;
    private $bussy = [];

    public function __construct(string $tmpDir = '/tmp')
    {
        $this->setTmpDir($tmpDir);
    }

    public function setTmpDir(string $tmpDir)
    {
        if (substr($tmpDir, strlen($tmpDir) - 1, 1) !== DIRECTORY_SEPARATOR) {

            $tmpDir = $tmpDir . DIRECTORY_SEPARATOR;
        }
        $this->tmpDir = $tmpDir;
    }

    public function tryLock(string $id): bool
    {
        $pathFile = $this->tmpDir . $id;

        if (!file_exists($pathFile)) {

            touch($pathFile);
        }
        $file = fopen($pathFile, 'r');

        if (!flock($file, LOCK_EX | LOCK_NB)) {

            fclose($file);

            return false;
        }
        $this->bussy[$id] = $file;

        return true;
    }

    public function unlock(string $id): bool
    {
        if (!isset($this->bussy[$id])) {

            return false;
        }
        if (!fclose($this->bussy[$id])) {

            return false;
        }

        unset($this->bussy[$id]);

        return true;
    }

}