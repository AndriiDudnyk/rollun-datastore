<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

namespace rollun\datastore\DataStore\Interfaces;

use rollun\datastore\DataStore\BaseDto;
use Xiag\Rql\Parser\Query;

/**
 * This interface support all CRUD (create, update, ) action on data store.
 * The application suppose that data store do not have any side affects (like autoincrement ids, default values, etc.).
 * All records in data store should have identifiers (field with unique value).
 * Expect that any record, that retrieved from data store has identifier.
 *
 * Interface DataStoresInterface
 * @package rollun\datastore\DataStore\Interfaces
 */
interface DataStoreInterface extends ReadInterface
{
    /**
     * Create new record:
     * - it can't overwrite existing record, so if item exist it should throw exception;
     * - if identifier is not specified, it should be autogenerated on application level;
     * - method should return created record;
     * - if record can't be created, it should throw exception.
     *
     * P.S. We return created item, because we can have default value on application level.
     *
     * @param array|\ArrayObject|BaseDto $record
     * @return array|\ArrayObject|BaseDto
     */
    public function create($record);

    /**
     * Create new records:
     * - if identifiers is not specified, its should be autogenerated on application level;
     * - if regular record exist or can't be created it should be pass it and start create next one;
     * - method should return list of created identifiers.
     *
     * P.S. Method expect list of records.
     *
     * @param  array[]|\ArrayObject[]|BaseDto[] $records
     * @return array
     */
    public function multiCreate($records);

    /**
     * Update existing record:
     * - it can't update not existing record, so in this case it should throw exception;
     * - if record can't be update in other cases it should throw exception too;
     * - identifier is required, in other key it should throw exception;
     * - method should return updated record.
     *
     * P.S. We return updated item, because we can have default value on application level.
     * Of course, those fields that are not listed in the record should not be changed in data store.
     *
     * @param array|\ArrayObject|BaseDto $record
     * @return array|\ArrayObject|BaseDto
     */
    public function update($record);

    /**
     * Update existing records:
     * - if regular record is not exist or record can't be updated it should pass it and start update next one;
     * - identifiers are required, in other case it should throw exception;
     * - method should return list of updated records.
     *
     * @param  array[]|\ArrayObject[]|BaseDto[] $records
     * @return array
     */
    public function multiUpdate($records);

    /**
     * Update records by query:
     * - if identifier specified in record it should throw exception
     * - it should return list of updated record
     * - if regular record is not exist or record can't be updated it should pass it and start update next one
     *
     * @param array|\ArrayObject|BaseDto $record
     * @param Query $query
     * @return array
     */
    public function queriedUpdate($record, Query $query);

    /**
     * Create record id it does not exist or update if it is exist:
     * - if identifier is not specified, it should be autogenerated on application level;
     * - if record can't be rewrote, it should throw exception.
     * - it should return rewrote record
     *
     * @param array|\ArrayObject|BaseDto $record
     * @return array|\ArrayObject|BaseDto
     */
    public function rewrite($record);

    /**
     * Delete record by identifier in data store.
     * Method should return deleted record.
     *
     * @param int|string $id
     * @return array|\ArrayObject|BaseDto
     */
    public function delete($id);

    /**
     * Delete records by query:
     * - it should return list of deleted record
     * - if regular record is not exist or record can't be deleted it should pass it and start delete next one
     *
     * @param Query $query
     * @return array
     */
    public function queriedDelete(Query $query);
}
