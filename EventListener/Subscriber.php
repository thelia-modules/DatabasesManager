<?php
/*************************************************************************************/
/*                                                                                   */
/*    DatabasesManager - A Thelia 2 databases manager module                         */
/*    Copyright (C) 2015-2017 Jérôme BILLIRAS                                        */
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

/**
 * Class Subscriber
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class Subscriber implements EventSubscriberInterface
{
    /**
     * @var \DatabasesManager\Handler\ConfigurationHandler Configuration handler
     */
    protected $configHandler;

    /**
     * Class constructor
     *
     * @param \DatabasesManager\Handler\ConfigurationHandler $configurationHandler Configuration handler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configHandler = $configurationHandler;
    }

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

            $configuration = [
                'dsn' => 'mysql:host=' . $databaseConfig['host'] . ';dbname=' . $databaseConfig['db_name'],
                'user' => $databaseConfig['user'],
                'password' => $databaseConfig['pass'],
                'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
            ];
            if (!empty($databaseConfig['db_charset'])) {
                $configuration['options'] =  [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => [
                        'value' => 'SET NAMES \'' . $databaseConfig['db_charset'] . '\''
                    ]
                ];
            }

            $manager = new ConnectionManagerSingle;
            $manager->setConfiguration($configuration);
            $manager->setName($label);

            $serviceContainer->setConnectionManager($label, $manager);
            $serviceContainer->setAdapterClass($label, 'mysql');
        }
    }
}
