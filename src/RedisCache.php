<?php

declare(strict_types=1);

namespace MaximalTestingX\Cache;

use Redis;

class RedisCache implements CacheInterface
{
    private Redis $client;

    public function __construct(Redis $client)
    {
        $this->client = $client;
    }

    public function set(string $key, string $value, int $ttl = 0): void
    {
        if (!$ttl) {
            $this->client->set($key, $value);
        } else {
            $this->client->set($key, $value, ['ex' => $ttl]);
        }
    }

    public function get(string $key): ?string
    {
        $data = $this->client->get($key);

        if ($data === false) {
            return null;
        }

        return $data;
    }

    public function delete(string $key): void
    {
        $this->client->del($key);
    }
}