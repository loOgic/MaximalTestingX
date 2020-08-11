<?php

declare(strict_types=1);

namespace unit;

use Codeception\Test\Unit as CodeceptionUnit;
use MaximalTestingX\Cache\FileCache;
use UnitTester;

class FileCacheTest extends CodeceptionUnit
{
    protected UnitTester $tester;

    private function getFileCache(): FileCache
    {
        $path = realpath(__DIR__.'/../_runtime/');

        return new FileCache($path);
    }

    public function testGetEmpty()
    {
        $cache = $this->getFileCache();

        $data = $cache->get('key');

        $this->assertSame($data, null);
    }

    public function testSetWithoutTTL()
    {
        $cache = $this->getFileCache();
        $cache->set('key', 'value');

        $data = $cache->get('key');

        $this->assertSame($data, 'value');
    }

    public function testSetWithTTL()
    {
        $cache = $this->getFileCache();
        $cache->set('key', 'value', 10);

        $data = $cache->get('key');

        $this->assertSame($data, 'value');
    }

    public function testSetWithExpiredTTL()
    {
        $cache = $this->getFileCache();
        $cache->set('key', 'value', 1);

        sleep(2);
        $data = $cache->get('key');

        $this->assertSame($data, null);
    }

    public function testDelete()
    {
        $cache = $this->getFileCache();
        $cache->set('key', 'value');

        $cache->delete('key');

        $data = $cache->get('key');
        $this->assertSame($data, null);
    }


}