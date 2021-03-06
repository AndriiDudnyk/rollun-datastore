<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

namespace rollun\test\intagration\DataStore;

use rollun\datastore\DataStore\DataStoreAbstract;
use rollun\datastore\DataStore\DbTable;
use rollun\datastore\TableGateway\DbSql\MultiInsertSql;
use rollun\datastore\TableGateway\SqlQueryBuilder;
use rollun\datastore\TableGateway\TableManagerMysql;
use Zend\Db\TableGateway\TableGateway;

class MultiplyInsertDbTable extends DbTableTest
{
    public function setUp()
    {
        parent::setUp();
        $adapter = $this->getContainer()->get('db');
        $this->mysqlManager = new TableManagerMysql($adapter);

        if ($this->mysqlManager->hasTable($this->tableName)) {
            $this->mysqlManager->deleteTable($this->tableName);
        }

        $this->mysqlManager->createTable($this->tableName, $this->tableConfig);
        $sql = new MultiInsertSql($adapter, $this->tableName);

        $this->tableGateway = new TableGateway(
            $this->tableName,
            $adapter,
            null,
            null,
            $sql
        );
    }

    public function createObject(): DataStoreAbstract
    {
        $adapter = $this->container->get('db');
        $sqlQueryBuilder = new SqlQueryBuilder($adapter, $this->tableName);

        return new DbTable($this->tableGateway, $sqlQueryBuilder);
    }

    public function testCreateMultiRow()
    {
        $object = $this->createObject();
        $data = [];
        $range = range(1, 20000);

        foreach ($range as $i) {
            $data[] = [
                $object->getIdentifier() => $this->identifierToType($i),
                'name' => "name{$i}",
                'surname' => "surname{$i}",
            ];
        }

        $object->create($data);
        $this->assertEquals(count($range), $object->count());
    }
}
