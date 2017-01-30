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

namespace DatabasesManager\Handler;

use DatabasesManager\DatabasesManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Thelia\Core\Translation\Translator;

/**
 * Class ConfigurationHandler
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class ConfigurationHandler
{
    /**
     * @var \Thelia\Core\Translation\Translator Translator service
     */
    protected $translator;

    /**
     * @var string Path to databases configuration file
     */
    protected $configurationPath;

    /**
     * @var string Path to databases environment dependent configuration file
     */
    protected $envConfigurationPath;

    /**
     * Class constructor
     *
     * @param \Thelia\Core\Translation\Translator $translator  Translator service
     * @param string                              $environment Current environment
     */
    public function __construct(Translator $translator, $environment = null)
    {
        $this->translator = $translator;

        $this->configurationPath = THELIA_LOCAL_DIR . DatabasesManager::MODULE_CODE . DS. 'databases.yml';
        $this->envConfigurationPath = THELIA_LOCAL_DIR . DatabasesManager::MODULE_CODE . DS
            . 'databases_' . $environment . '.yml'
        ;

        $fileSystem = new Filesystem;
        $fileSystem->mkdir(THELIA_LOCAL_DIR . DatabasesManager::MODULE_CODE);
        if (!$fileSystem->exists($this->configurationPath)) {
            $fileSystem->touch($this->configurationPath);
        }
        if (!$fileSystem->exists($this->envConfigurationPath)) {
            $fileSystem->touch($this->envConfigurationPath);
        }
    }

    /**
     * Parse configuration file and return content
     *
     * @param boolean $useEnvironment Use current environment
     *
     * @return array
     */
    public function parse($useEnvironment = false)
    {
        if ($useEnvironment) {
            $configuration = Yaml::parse($this->envConfigurationPath);
        } else {
            $configuration = Yaml::parse($this->configurationPath);
        }

        return (array) $configuration;
    }

    /**
     * Parse shared and for current environment configuration files and return content
     *
     * @return array
     */
    public function parseBoth()
    {
        $sharedConfig = (array) Yaml::parse($this->configurationPath);
        $envConfig = (array) Yaml::parse($this->envConfigurationPath);

        $mergedConfig = array_merge($sharedConfig, $envConfig);

        return $mergedConfig;
    }

    /**
     * Dump databases configuration to file
     *
     * @param array $databasesConfig
     * @param boolean $useEnvironment Use current environment
     */
    public function dump(array $databasesConfig, $useEnvironment = false)
    {
        $yml = Yaml::dump($databasesConfig);

        if ($useEnvironment) {
            $configurationPath = $this->envConfigurationPath;
        } else {
            $configurationPath = $this->configurationPath;
        }

        $fileSystem = new Filesystem;
        $fileSystem->dumpFile($configurationPath, $yml);
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
                'db_name' => '',
                'db_charset' => ''
            ];

            $this->dump($databasesConfiguration);
        }
    }

    /**
     * Get standard mysql charsets
     *
     * @return array Charsets associative array with charset name as key and charset description as value
     */
    public function getCharsets()
    {
        return [
            '' => $this->translator->trans('FORM_DB_CHARSET_VALUE_DEFAULT', [], DatabasesManager::DOMAIN_NAME),
            'armscii8' => 'ARMSCII-8 Armenian',
            'ascii' => 'US ASCII',
            'big5' => 'Big5 Traditional Chinese',
            'binary' => 'Binary pseudo charset',
            'cp1250' => 'Windows Central European',
            'cp1251' => 'Windows Cyrillic',
            'cp1256' => 'Windows Arabic',
            'cp1257' => 'Windows Baltic',
            'cp850' => 'DOS West European',
            'cp852' => 'DOS Central European',
            'cp866' => 'DOS Russian',
            'cp932' => 'SJIS for Windows Japanese',
            'dec8' => 'DEC West European',
            'eucjpms' => 'UJIS for Windows Japanese',
            'euckr' => 'EUC-KR Korean',
            'gb2312' => 'GB2312 Simplified Chinese',
            'gbk' => 'GBK Simplified Chinese',
            'geostd8' => 'GEOSTD8 Georgian',
            'greek' => 'ISO 8859-7 Greek',
            'hebrew' => 'ISO 8859-8 Hebrew',
            'hp8' => 'HP West European',
            'keybcs2' => 'DOS Kamenicky Czech-Slovak',
            'koi8r' => 'KOI8-R Relcom Russian',
            'koi8u' => 'KOI8-U Ukrainian',
            'latin1' => 'cp1252 West European',
            'latin2' => 'ISO 8859-2 Central European',
            'latin5' => 'ISO 8859-9 Turkish',
            'latin7' => 'ISO 8859-13 Baltic',
            'macce' => 'Mac Central European',
            'macroman' => 'Mac West European',
            'sjis' => 'Shift-JIS Japanese',
            'swe7' => '7bit Swedish',
            'tis620' => 'TIS620 Thai',
            'ucs2' => 'UCS-2 Unicode',
            'ujis' => 'EUC-JP Japanese',
            'utf16' => 'UTF-16 Unicode',
            'utf16le' => 'UTF-16LE Unicode',
            'utf32' => 'UTF-32 Unicode',
            'utf8' => 'UTF-8 Unicode',
            'utf8mb4' => 'UTF-8 Unicode'
        ];
    }
}
