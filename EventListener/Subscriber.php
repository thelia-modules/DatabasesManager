<?php
/*************************************************************************************/
/*                                                                                   */
/*    DatabasesManager - A Thelia 2 databases manager module                         */
/*    Copyright (C) 2015 Jérôme BILLIRAS                                             */
/*                                                                                   */
/*    This file is part of DatabasesManager.                                         */
/*                                                                                   */
/*    DatabasesManager is free software: you can redistribute it and/or modify       */
/*    it under the terms of the GNU Lesser General Public License as published by    */
/*    the Free Software Foundation, either version 3 of the License, or              */
/*    any later version.                                                             */
/*                                                                                   */
/*    DatabasesManager is distributed in the hope that it will be useful,            */
/*    but WITHOUT ANY WARRANTY; without even the implied warranty of                 */
/*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                  */
/*    GNU Lesser General Public License for more details.                            */
/*                                                                                   */
/*    You should have received a copy of the GNU Lesser General Public License       */
/*    along with this program. If not, see <http://www.gnu.org/licenses/>.           */
/*                                                                                   */
/*************************************************************************************/

namespace DatabasesManager\EventListener;

use DatabasesManager\Handler\ConfigurationHandler;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Config\DefinePropel;
use Thelia\Config\DatabaseConfiguration;

/**
 * Class Subscriber
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class Subscriber implements EventSubscriberInterface
{
    /**
     * Class constructor
     *
     * @param \DatabasesManager\Handler\ConfigurationHandler $configurationHandler DatabasesManager config handler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configHandler = $configurationHandler;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::BOOT => [
                ['connectAllDatabases', 100]
            ]
        ];
    }

    /**
     * Initialize Propel connections to databases
     */
    public function connectAllDatabases()
    {
        /** @var \Propel\Runtime\ServiceContainer\StandardServiceContainer $serviceContainer */
        $serviceContainer = Propel::getServiceContainer();

        foreach ($this->configHandler->parseBoth() as $label => $databaseConfig) {
            if (empty($databaseConfig['host']) || empty($databaseConfig['user']) || empty($databaseConfig['db_name'])) {
                continue;
            }

            $manager = new ConnectionManagerSingle;
						$propelConf = [
							'database' => [
								'connection' => [
									'driver' => 'mysql',
									'dsn' => 'mysql:host=' . $databaseConfig['host'] . ';dbname=' . $databaseConfig['db_name'],
									'user' => $databaseConfig['user'],
									'password' => $databaseConfig['pass']
								]
							]
						];
            $definePropel = new DefinePropel(
            	new DatabaseConfiguration(),
            	$propelConf,
            	array()
            	);
            $config = $definePropel->getConfig();
            $manager->setConfiguration($config);
            $manager->setName($label);

            $serviceContainer->setConnectionManager($label, $manager);
            $serviceContainer->setAdapterClass($label, 'mysql');
        }
    }
}
