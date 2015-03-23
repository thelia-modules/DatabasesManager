<?php
/**********************************************************************************/
/*                                                                                */
/*    DatabasesManager - A Thelia 2 databases manager module                      */
/*    Copyright (C) 2015 Jérôme BILLIRAS                                          */
/*                                                                                */
/*    This file is part of DatabasesManager.                                      */
/*                                                                                */
/*    DatabasesManager is free software: you can redistribute it and/or modify    */
/*    it under the terms of the GNU General Public License as published by        */
/*    the Free Software Foundation, either version 3 of the License, or           */
/*    any later version.                                                          */
/*                                                                                */
/*    DatabasesManager is distributed in the hope that it will be useful,         */
/*    but WITHOUT ANY WARRANTY; without even the implied warranty of              */
/*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               */
/*    GNU General Public License for more details.                                */
/*                                                                                */
/*    You should have received a copy of the GNU General Public License           */
/*    along with this program. If not, see <http://www.gnu.org/licenses/>.        */
/*                                                                                */
/**********************************************************************************/

namespace DatabasesManager\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigurationHandler
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class ConfigurationHandler
{
    /** @var string Path to databases configuration file */
    protected $configurationPath;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->configurationPath = THELIA_MODULE_DIR . 'DatabasesManager' . DS . 'Config' . DS . 'databases.yml';

        $fileSystem = new Filesystem;
        if (!$fileSystem->exists($this->configurationPath)) {
            $fileSystem->touch($this->configurationPath);
        }
    }

    /**
     * Parse configuration file and return content
     *
     * @return array
     */
    public function parse()
    {
        return (array) Yaml::parse($this->configurationPath);
    }

    /**
     * Dump databases configuration to file
     *
     * @param array $databasesConfig
     */
    public function dump(array $databasesConfig)
    {
        $yml = Yaml::dump($databasesConfig);

        $fileSystem = new Filesystem;
        $fileSystem->dumpFile($this->configurationPath, $yml);
    }

    /**
     * Add an empty database configuration
     *
     * @param string $configurationLabel The database configuration label
     */
    public function addEmptyConfiguration($configurationLabel)
    {
        $databasesConfiguration = $this->parse();

        if (strtolower($configurationLabel) !== 'thelia'
            && !array_key_exists($configurationLabel, $databasesConfiguration)
        ) {
            $databasesConfiguration[$configurationLabel] = [
                'host' => '',
                'user' => '',
                'pass' => '',
                'db_name' => ''
            ];

            $this->dump($databasesConfiguration);
        }
    }
}
