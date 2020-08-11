<?php

declare(strict_types=1);

namespace unit;

use Codeception\Test\Unit as CodeceptionUnit;
use MaximalTestingX\Cache\RedisCache;
use Redis;
use UnitTester;

class RedisCacheTest extends CodeceptionUnit
{
    protected UnitTester $tester;

    private function getRedisCache(): RedisCache
    {
        $client = new Redis();
        $client->connect('127.0.0.1');

        return new RedisCache($client);
    }

    public function testGetEmpty()
    {
        $cache = $this->getRedisCache();

        $data = $cache->get('key');

        $this->assertSame($data, null);
    }

    public function testSetWithoutTTL()
    {
        $cache = $this->getRedisCache();
        $cache->set('key', 'value');

        $data = $cache->get('key');

        $this->assertSame($data, 'value');
    }

    public function testSetWithTTL()
    {
        $cache = $this->getRedisCache();
        $cache->set('key', 'value', 10);

        $data = $cache->get('key');

        $this->assertSame($data, 'value');
    }

    public function testSetWithExpiredTTL()
    {
        $cache = $this->getRedisCache();
        $cache->set('key', 'value', 1);

        sleep(2);
        $data = $cache->get('key');

        $this->assertSame($data, null);
    }

    public function testDelete()
    {
        $cache = $this->getRedisCache();
        $cache->set('key', 'value');

        $cache->delete('key');

        $data = $cache->get('key');
        $this->assertSame($data, null);
    }


}