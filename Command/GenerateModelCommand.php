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

namespace DatabasesManager\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate class model for a specific module
 *
 * Class GenerateModelCommand
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class GenerateModelCommand extends SchemaParserCommand
{
    protected function configure()
    {
        $this
            ->setName('module:generate:model')
            ->setDescription('Generate model for a specific module')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'module name'
            )
            ->addOption(
                'generate-sql',
                '-g',
                InputOption::VALUE_NONE,
                'Generate sql file at the same time'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->preExecute($input);

        try {
            $this->generateModel($output);

            if ($input->getOption('generate-sql')) {
                $output->writeln(' ');
                $this->generateSql($output);
            }
        } catch (\Exception $exception) {
        }

        $this->postExecute();

        if (isset($exception)) {
            throw $exception;
        }
    }
}
