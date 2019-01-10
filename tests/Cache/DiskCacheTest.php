<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use Linna\Cache\DiskCache;
use PHPUnit\Framework\TestCase;

/**
 * Disk Cache Driver test.
 */
class DiskCacheTest extends TestCase
{
    /**
     * @var DiskCache Disk Cache resource
     */
    protected static $cache;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$cache = new DiskCache();
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$cache = null;
    }

    /**
     * Setup.
     *
     * @return void
     */
    public function setUp(): void
    {
        self::$cache->clear();
    }

    /**
     * Invalid key provider.
     *
     * @return array
     */
    public function invalidKeyProvider(): array
    {
        return [
            [1],
            [[0, 1]],
            [(object) [0, 1]],
            [1.5],
            [true]
        ];
    }

    /**
     * Test set with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     *
     * @return void
     */
    public function testSetWithInvalidKey($key): void
    {
        self::$cache->set($key, [0, 1, 2, 3, 4]);
    }

    /**
     * Test set.
     *
     * @return void
     */
    public function testSet(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4]));

        usleep(2000100);

        $this->assertTrue(self::$cache->has('foo'));
    }

    /**
     * Test set with ttl null.
     *
     * @return void
     */
    public function testSetWithTtlAtZero(): void
    {
        $this->assertTrue(self::$cache->set('foo_ttl', [0, 1, 2, 3, 4], 0));

        usleep(1000100);

        $this->assertTrue(self::$cache->has('foo_ttl'));
    }

    /**
     * Test set with ttl value.
     *
     * @return void
     */
    public function testSetWithTtl(): void
    {
        $this->assertTrue(self::$cache->set('foo_ttl', [0, 1, 2, 3, 4], 1));

        usleep(1000100);

        $this->assertNull(self::$cache->get('foo_ttl'));
    }

    /**
     * Test get with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     *
     * @return void
     */
    public function testGetWithInvalidKey($key): void
    {
        self::$cache->get($key);
    }

    /**
     * Test get.
     *
     * @return void
     */
    public function testGet(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4]));

        $this->assertEquals([0, 1, 2, 3, 4], self::$cache->get('foo'));
    }

    /**
     * Test get with default value.
     *
     * @return void
     */
    public function testGetWithDefault(): void
    {
        $this->assertNull(self::$cache->get('foo_not_exist'));
    }

    /**
     * Test get with expired element.
     *
     * @return void
     */
    public function testGetWithExpiredElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4], -10));

        $this->assertNull(self::$cache->get('foo'));
    }

    /**
     * Test delete with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     *
     * @return void
     */
    public function testDeleteWithInvalidKey($key): void
    {
        self::$cache->delete($key);
    }

    /**
     * Test delete an existing element.
     *
     * @return void
     */
    public function testDeleteExistingElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4]));

        $this->assertTrue(self::$cache->delete('foo'));
    }

    /**
     * Test delete not existing element.
     *
     * @return void
     */
    public function testDeleteNotExistingElement(): void
    {
        $this->assertFalse(self::$cache->delete('foo'));
    }

    /**
     * Test clear all cache.
     *
     * @return void
     */
    public function testClear(): void
    {
        $this->assertTrue(self::$cache->set('foo_0', [0]));
        $this->assertTrue(self::$cache->set('foo_1', [1]));
        $this->assertTrue(self::$cache->set('foo_2', [2]));
        $this->assertTrue(self::$cache->set('foo_3', [3]));
        $this->assertTrue(self::$cache->set('foo_4', [4]));
        $this->assertTrue(self::$cache->set('foo_5', [5]));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        $this->assertTrue(self::$cache->clear());

        $this->assertFalse(self::$cache->has('foo_0'));
        $this->assertFalse(self::$cache->has('foo_1'));
        $this->assertFalse(self::$cache->has('foo_2'));
        $this->assertFalse(self::$cache->has('foo_3'));
        $this->assertFalse(self::$cache->has('foo_4'));
        $this->assertFalse(self::$cache->has('foo_5'));
    }

    /**
     * Test get multiple elements with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     *
     * @return void
     */
    public function testGetMultipleWithInvalidKey($key): void
    {
        self::$cache->getMultiple($key);
    }

    /**
     * Test get multiple elements.
     *
     * @return void
     */
    public function testGetMultiple(): void
    {
        $this->assertTrue(self::$cache->set('foo_0', [0]));
        $this->assertTrue(self::$cache->set('foo_1', [1]));
        $this->assertTrue(self::$cache->set('foo_2', [2]));
        $this->assertTrue(self::$cache->set('foo_3', [3]));
        $this->assertTrue(self::$cache->set('foo_4', [4]));
        $this->assertTrue(self::$cache->set('foo_5', [5]));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        $this->assertEquals([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], self::$cache->getMultiple([
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ]));
    }

    /**
     * Test set multiple elements with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     *
     * @return void
     */
    public function testSetMultipleWithInvalidKey($key): void
    {
        self::$cache->setMultiple($key);
    }

    /**
     * Test set multiple elements.
     *
     * @return void
     */
    public function testSetMultiple(): void
    {
        $this->assertTrue(self::$cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        $this->assertEquals([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], self::$cache->getMultiple([
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ]));
    }

    /**
     * Test set multiple elements with ttl.
     *
     * @return void
     */
    public function testSetMultipleTtl(): void
    {
        $this->assertTrue(self::$cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ], 1));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        usleep(1000100);

        $this->assertNull(self::$cache->get('foo_0'));
        $this->assertNull(self::$cache->get('foo_1'));
        $this->assertNull(self::$cache->get('foo_2'));
        $this->assertNull(self::$cache->get('foo_3'));
        $this->assertNull(self::$cache->get('foo_4'));
        $this->assertNull(self::$cache->get('foo_5'));
    }

    /**
     * Teset delete multiple elements with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     *
     * @return void
     */
    public function testDeleteMultipleWithInvalidKey($key): void
    {
        self::$cache->deleteMultiple($key);
    }

    /**
     * Test delete multiple elements.
     *
     * @return void
     */
    public function testDeleteMultiple(): void
    {
        $this->assertTrue(self::$cache->setMultiple([
            'foo_0' => [0],
            'foo_1' => [1],
            'foo_2' => [2],
            'foo_3' => [3],
            'foo_4' => [4],
            'foo_5' => [5],
        ]));

        $this->assertTrue(self::$cache->has('foo_0'));
        $this->assertTrue(self::$cache->has('foo_1'));
        $this->assertTrue(self::$cache->has('foo_2'));
        $this->assertTrue(self::$cache->has('foo_3'));
        $this->assertTrue(self::$cache->has('foo_4'));
        $this->assertTrue(self::$cache->has('foo_5'));

        $this->assertTrue(self::$cache->deleteMultiple([
            'foo_0',
            'foo_1',
            'foo_2',
            'foo_3',
            'foo_4',
            'foo_5',
        ]));

        $this->assertFalse(self::$cache->has('foo_0'));
        $this->assertFalse(self::$cache->has('foo_1'));
        $this->assertFalse(self::$cache->has('foo_2'));
        $this->assertFalse(self::$cache->has('foo_3'));
        $this->assertFalse(self::$cache->has('foo_4'));
        $this->assertFalse(self::$cache->has('foo_5'));
    }

    /**
     * Test has with invalid key.
     *
     * @dataProvider invalidKeyProvider
     * @expectedException TypeError
     *
     * @return void
     */
    public function testHasWithInvalidKey($key): void
    {
        self::$cache->has($key);
    }

    /**
     * Test has with existing element.
     *
     * @return void
     */
    public function testHasExistingElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4]));

        $this->assertTrue(self::$cache->has('foo'));
    }

    /**
     * Test has with not existing element.
     *
     * @return void
     */
    public function testHasNotExistingElement(): void
    {
        $this->assertFalse(self::$cache->has('foo_false'));
    }

    /**
     * Test has with expired element.
     *
     * @return void
     */
    public function testHasWithExpiredElement(): void
    {
        $this->assertTrue(self::$cache->set('foo', [0, 1, 2, 3, 4], -10));

        $this->assertFalse(self::$cache->has('foo'));
    }
}
