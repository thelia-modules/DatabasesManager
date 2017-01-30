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

namespace DatabasesManager\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class ConfigurationLoop
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class ConfigurationLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createBooleanTypeArgument('current_env')
        );
    }

    public function buildArray()
    {
        /** @var \DatabasesManager\Handler\ConfigurationHandler $configHandler */
        $configHandler = $this->container->get('databases.manager.config.handler');

        return $configHandler->parse($this->getArgValue('current_env'));
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var \DatabasesManager\Handler\ConfigurationHandler $configHandler */
        $configHandler = $this->container->get('databases.manager.config.handler');
        $charsets = $configHandler->getCharsets();

        foreach ($loopResult->getResultDataCollection() as $label => $databaseConfig) {
            $loopResultRow = new LoopResultRow;

            $flatConfig = [
                'original_label' => $label,
                'label' => $label,
                'host' => $databaseConfig['host'],
                'user' => $databaseConfig['user'],
                'pass' => $databaseConfig['pass'],
                'db_name' => $databaseConfig['db_name'],
                'db_charset' => $databaseConfig['db_charset']
            ];

            $loopResultRow
                ->set('LABEL', $label)
                ->set('HOST', $databaseConfig['host'])
                ->set('USER', $databaseConfig['user'])
                ->set('PASS', $databaseConfig['pass'])
                ->set('DB_NAME', $databaseConfig['db_name'])
                ->set('DB_CHARSET', $databaseConfig['db_charset'])
                ->set('DB_CHARSET_LABEL', $charsets[$databaseConfig['db_charset']])
                ->set('JSON', json_encode($flatConfig))
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
