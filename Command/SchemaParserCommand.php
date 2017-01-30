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

namespace DatabasesManager\Command;

use Propel\Generator\Command\ModelBuildCommand;
use Propel\Generator\Command\SqlBuildCommand;
use Symfony\Component\Config\Util\XmlUtils;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Thelia\Command\BaseModuleGenerate;

/**
 * Base class for databases manager model generators
 *
 * Class SchemaParserCommand
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
abstract class SchemaParserCommand extends BaseModuleGenerate
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @var string schema.xml path
     */
    protected $schemaFilePath;

    /**
     * @var \DOMDocument schema.xml object model
     */
    protected $domSchema;

    /**
     * @var bool schema.xml is splitable into multiple files with "database" node
     */
    protected $splitableSchema;

    /**
     * @var int Highest split index
     */
    protected $maxSplitIdx = 0;

    /**
     * @var string temporary name for schema.xml file
     */
    protected $tmpSchemaName;

    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->fileSystem = new Filesystem;
    }

    /**
     * Before command execution statements
     *
     * @param \Symfony\Component\Console\Input\InputInterface  $input  An InputInterface instance
     *
     * @throws \RuntimeException
     */
    protected function preExecute(InputInterface $input)
    {
        $this->module = $this->formatModuleName($input->getArgument('name'));
        $this->moduleDirectory = THELIA_MODULE_DIR . $this->module;

        if ($this->fileSystem->exists($this->moduleDirectory) === false) {
            throw new \RuntimeException(sprintf('%s module does not exists', $this->module));
        }

        $this->schemaFilePath = $this->moduleDirectory . DS . 'Config' . DS . 'schema.xml';
        if ($this->fileSystem->exists($this->schemaFilePath) === false) {
            throw new \RuntimeException('schema.xml not found in Config directory. Needed file for generating model');
        }

        $this->checkDatabases();
        if ($this->splitableSchema) {
            $this->splitSchema();
        }
    }

    /**
     * After command execution statements
     */
    protected function postExecute()
    {
        if ($this->splitableSchema) {
            $this->mergeSchema();
        }
    }

    /**
     * Call propel command to build model files
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function generateModel(OutputInterface $output)
    {
        $moduleBuildPropel = new ModelBuildCommand;
        $moduleBuildPropel->setApplication($this->getApplication());

        $moduleBuildPropel->run(
            new ArrayInput([
                'command' => $moduleBuildPropel->getName(),
                '--output-dir' => THELIA_MODULE_DIR,
                '--input-dir' => $this->moduleDirectory . DS . 'Config'
            ]),
            $output
        );

        $verifyDirectories = [
            THELIA_MODULE_DIR . DS . 'Thelia',
            $this->moduleDirectory . DS . 'Model' . DS . 'Thelia'
        ];

        foreach ($verifyDirectories as $directory) {
            if ($this->fileSystem->exists($directory)) {
                $this->fileSystem->remove($directory);
            }
        }

        $output->renderBlock([
            '',
            'Model generated successfully',
            ''
        ], 'bg=green;fg=black');
    }

    /**
     * Call propel command to build sql files
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function generateSql(OutputInterface $output)
    {
        $sqlBuild = new SqlBuildCommand;
        $sqlBuild->setApplication($this->getApplication());

        $sqlBuild->run(
            new ArrayInput([
                'command' => $sqlBuild->getName(),
                '--output-dir' => $this->moduleDirectory . DS . 'Config',
                '--input-dir' => $this->moduleDirectory . DS . 'Config'
            ]),
            $output
        );

        $mapPath = $this->moduleDirectory . DS . 'Config' . DS . 'sqldb.map';
        if ($this->fileSystem->exists($mapPath)) {
            $this->fileSystem->remove($mapPath);
        }

        $output->renderBlock([
            '',
            'Sql generated successfully',
            'File available in your module config directory',
            ''
        ], 'bg=green;fg=black');
    }

    /**
     * Check if schema.xml contain "databases" tag
     *
     * @return bool
     */
    protected function checkDatabases()
    {
        $this->domSchema = XmlUtils::loadFile($this->schemaFilePath);
        $this->splitableSchema = $this->domSchema->getElementsByTagName('databases')->length === 1;

        return $this->splitableSchema;
    }

    /**
     *  Split schema by "database" nodes
     */
    protected function splitSchema()
    {
        /** @var \DatabasesManager\Handler\ConfigurationHandler $configHandler */
        $configHandler = $this->getContainer()->get('databases.manager.config.handler');

        $domNodeList = $this->domSchema->getElementsByTagName('database');
        $this->maxSplitIdx = $domNodeList->length;

        /** @var \DOMElement $domElement */
        foreach ($domNodeList as $dbIdx => $domElement) {
            $configHandler->addEmptyConfiguration($domElement->getAttribute('name'));

            $domDocument = new \DOMDocument('1.0', 'utf-8');
            $domDocument->appendChild($domDocument->importNode($domElement, true));
            $domDocument->save($this->moduleDirectory . DS . 'Config' . DS . $dbIdx . '_split_schema.xml');
        }

        $this->tmpSchemaName = uniqid('split');
        $this->fileSystem->rename(
            $this->schemaFilePath,
            $this->moduleDirectory . DS . 'Config' . DS . $this->tmpSchemaName
        );
    }

    /**
     * Remove split schemas
     */
    protected function mergeSchema()
    {
        for ($i = 0; $i < $this->maxSplitIdx; $i++) {
            $splitPath = $this->moduleDirectory . DS . 'Config' . DS . $i . '_split_schema.xml';
            if ($this->fileSystem->exists($splitPath)) {
                $this->fileSystem->remove($splitPath);
            }
        }
        $this->maxSplitIdx = 0;

        if ($this->tmpSchemaName !== null
            && $this->fileSystem->exists($this->moduleDirectory . DS . 'Config' . DS . $this->tmpSchemaName)
        ) {
            $this->fileSystem->rename(
                $this->moduleDirectory . DS . 'Config' . DS . $this->tmpSchemaName,
                $this->schemaFilePath
            );
        }
        $this->tmpSchemaName = null;
    }
}
