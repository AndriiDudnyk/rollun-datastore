<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

namespace rollun\datastore\DataStore\Aspect\Factory;

use Interop\Container\ContainerInterface;
use rollun\datastore\DataStore\Aspect\AspectAbstract;
use rollun\datastore\DataStore\DataStoreException;
use rollun\datastore\DataStore\Factory\DataStoreAbstractFactory;

/**
 * Create and return an instance of the DataStore which based on AspectAbstract
 *
 * The configuration can contain:
 * <code>
 * 'dataStore' => [
 *     'real_service_name_for_aspect_datastore' => [
 *         'class' => 'rollun\datastore\DataStore\Aspect\AspectAbstract',
 *         'dataStore' => 'real_service_name_of_any_type_of_datastore'  // this service must be exist
 *     ]
 * ]
 * </code>
 *
 * Class AspectAbstractFactory
 * @package rollun\datastore\DataStore\Aspect\Factory
 */
class AspectAbstractFactory extends DataStoreAbstractFactory
{
    protected static $KEY_DATASTORE_CLASS = AspectAbstract::class;

    protected static $KEY_IN_CREATE = 0;

    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $serviceConfig = $config[static::KEY_DATASTORE][$requestedName];
        $requestedClassName = $serviceConfig[static::KEY_CLASS];

        if (!isset($serviceConfig['dataStore'])) {
            throw new DataStoreException(
                "The dataStore type for '$requestedName' is not specified in the config " . static::KEY_DATASTORE
            );
        }

        $dataStore = $container->get($serviceConfig['dataStore']);

        return new $requestedClassName($dataStore);
    }
}
