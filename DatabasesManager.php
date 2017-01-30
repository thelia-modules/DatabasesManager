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

namespace DatabasesManager;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Thelia\Module\BaseModule;

/**
 * Class DatabasesManager
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class DatabasesManager extends BaseModule
{
    /** @var string */
    const MODULE_CODE = 'DatabasesManager';

    /** @var string */
    const DOMAIN_NAME = 'databasesmanager';

    public function update($currentVersion, $newVersion, ConnectionInterface $con = null)
    {
        // Move configuration files
        if (version_compare($currentVersion, '1.2.2', '<')) {
            $fileSystem = new Filesystem;
            $finder = (new Finder)
                ->name('#^databases(?:_.*?)?\.yml$#')
                ->in(THELIA_MODULE_DIR . self::MODULE_CODE . DS . 'Config')
            ;

            /** @var \Symfony\Component\Finder\SplFileInfo $configFile */
            foreach ($finder as $configFile) {
                $fileSystem->rename(
                    $configFile->getPathname(),
                    THELIA_LOCAL_DIR . self::MODULE_CODE . DS . $configFile->getFilename(),
                    true
                );
            }
        }
    }
}
