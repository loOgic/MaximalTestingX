<?php

declare(strict_types=1);

namespace MaximalTestingX\Cache;

class MemoryCache implements CacheInterface
{
    private array $cache = [];
    private array $expiredAfter = [];

    public function set(string $key, string $value, int $ttl = 0): void
    {
        $this->cache[$key] = $value;

        if ($ttl) {
            $this->expiredAfter[$key] = time() + $ttl;
        }
    }

    public function get(string $key): ?string
    {
        if (!isset($this->cache[$key])) {
            return null;
        }

        if (isset($this->expiredAfter[$key]) && $this->expiredAfter[$key] < time()) {
            $this->delete($key);
            return null;
        }

        return $this->cache[$key];
    }

    public function delete(string $key): void
    {
        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
        }

        if (isset($this->expiredAfter[$key])) {
            unset($this->expiredAfter[$key]);
        }
    }
}