<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Storage\MysqlPdoStorage;
use PHPUnit\Framework\TestCase;

class MysqlPdoStorageTest extends TestCase
{
    public function testConnection()
    {
        $mysqlPdoAdapter = new MysqlPdoStorage(
            $GLOBALS['pdo_mysql_dsn'],
            $GLOBALS['pdo_mysql_user'],
            $GLOBALS['pdo_mysql_password'],
            [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]
        );

        $this->assertInstanceOf(PDO::class, $mysqlPdoAdapter->getResource());
    }

    public function connectionDataProvider()
    {
        return [
            ['0', $GLOBALS['pdo_mysql_user'], $GLOBALS['pdo_mysql_password']],
            [$GLOBALS['pdo_mysql_dsn'], '', $GLOBALS['pdo_mysql_password']],
            [$GLOBALS['pdo_mysql_dsn'], $GLOBALS['pdo_mysql_user'], 'bad_password'],
        ];
    }

    /**
     * @dataProvider connectionDataProvider
     * @expectedException Exception
     */
    public function testFailConnection($dsn, $user, $password)
    {
        //$this->expectException(\Exception::class);

        /*$mysqlPdoAdapter = */(new MysqlPdoStorage($dsn, $user, $password, []))->getResource();

        //$resource = $mysqlPdoAdapter->getResource();
    }
}
