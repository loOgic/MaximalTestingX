<?php

declare(strict_types=1);

namespace unit;

use Codeception\Test\Unit as CodeceptionUnit;
use MaximalTestingX\Cache\MemoryCache;
use UnitTester;

class MemoryCacheTest extends CodeceptionUnit
{
    protected UnitTester $tester;

    public function testGetEmpty()
    {
        $cache = new MemoryCache();

        $data = $cache->get('key');

        $this->assertSame($data, null);
    }

    public function testSetWithoutTTL()
    {
        $cache = new MemoryCache();
        $cache->set('key', 'value');

        $data = $cache->get('key');

        $this->assertSame($data, 'value');
    }

    public function testSetWithTTL()
    {
        $cache = new MemoryCache();
        $cache->set('key', 'value', 10);

        $data = $cache->get('key');

        $this->assertSame($data, 'value');
    }

    public function testSetWithExpiredTTL()
    {
        $cache = new MemoryCache();
        $cache->set('key', 'value', 1);

        sleep(2);
        $data = $cache->get('key');

        $this->assertSame($data, null);
    }

    public function testDelete()
    {
        $cache = new MemoryCache();
        $cache->set('key', 'value');

        $cache->delete('key');

        $data = $cache->get('key');
        $this->assertSame($data, null);
    }


}