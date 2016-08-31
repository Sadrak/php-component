<?php
/**
 * This file is part of the PHP components.
 *
 * For the full copyright and license information, please view the LICENSE.md file distributed with this source code.
 *
 * @license MIT License
 * @link    https://github.com/ansas/php-component
 */

namespace Ansas\Slim\Provider;

use Pimple\Container;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;

class PropelProvider extends AbstractProvider
{
    /**
     * Get default settings.
     *
     * @return array
     */
    public static function getDefaultSettings()
    {
        return [
            'adapter'    => 'mysql',
            'classname'  => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
            'connection' => 'default',
            'version'    => '2.0.0-dev',
        ];
    }

    /**
     * Register provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $settings = array_merge([], self::getDefaultSettings(), $container['settings']['database']);

        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration([
            'classname' => $settings['classname'],
            'dsn'       => $settings['dsn'],
            'user'      => $settings['user'],
            'password'  => $settings['password'],
        ]);
        $manager->setName($settings['connection']);

        /** @var StandardServiceContainer $serviceContainer */
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->checkVersion($settings['version']);
        $serviceContainer->setAdapterClass($settings['connection'], $settings['adapter']);
        $serviceContainer->setConnectionManager($settings['connection'], $manager);
        $serviceContainer->setDefaultDatasource($settings['connection']);
    }
}
