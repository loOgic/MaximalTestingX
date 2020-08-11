<?php

declare(strict_types=1);

namespace MaximalTestingX\Cache;

interface CacheInterface
{
    public function set(string $key, string $value, int $ttl): void;
    public function get(string $key): ?string;
    public function delete(string $key): void;
}