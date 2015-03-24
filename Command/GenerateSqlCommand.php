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

namespace DatabasesManager\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate sql for a specific module
 *
 * Class GenerateSqlCommand
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class GenerateSqlCommand extends SchemaParserCommand
{
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this
            ->setName('module:generate:sql')
            ->setDescription('Generate the sql from schema.xml file')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Module name'
            )
            ->addOption(
                'generate-model',
                '-g',
                InputOption::VALUE_NONE,
                'Generate model files at the same time'
            )
        ;
    }

    /**
     * Executes the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface  $input  An InputInterface instance
     * @param \Symfony\Component\Console\Output\OutputInterface $output An OutputInterface instance
     *
     * @throws \Exception
     *
     * @return null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->preExecute($input);

        try {
            $this->generateSql($output);

            if ($input->getOption('generate-model')) {
                $output->writeln(' ');
                $this->generateModel($output);
            }
        } catch (\Exception $exception) {
        }

        $this->postExecute($input);

        if (isset($exception)) {
            throw $exception;
        }
    }
}
