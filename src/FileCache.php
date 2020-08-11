<?php

declare(strict_types=1);

namespace MaximalTestingX\Cache;

class FileCache implements CacheInterface
{
    private string $path = '';

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    private function getDataFileName(string $key)
    {
        return $this->path.'/data/'.$key;
    }

    private function getExpiredAfterFileName(string $key)
    {
        return $this->path.'/expiredAfter/'.$key;
    }

    public function set(string $key, string $value, int $ttl = 0): void
    {
        file_put_contents($this->getDataFileName($key), $value);

        if ($ttl) {
            file_put_contents($this->getExpiredAfterFileName($key), time() + $ttl);
        }
    }

    public function get(string $key): ?string
    {
        if (!file_exists($this->getDataFileName($key))) {
            return null;
        }

        if (file_exists($this->getExpiredAfterFileName($key))) {
            if (file_get_contents($this->getExpiredAfterFileName($key)) < time()) {
                $this->delete($key);
                return null;
            }
        }

        return file_get_contents($this->getDataFileName($key));
    }

    public function delete(string $key): void
    {
        if (file_exists($this->getDataFileName($key))) {
            unlink($this->getDataFileName($key));
        }

        if (file_exists($this->getExpiredAfterFileName($key))) {
            unlink($this->getExpiredAfterFileName($key));
        }
    }
}