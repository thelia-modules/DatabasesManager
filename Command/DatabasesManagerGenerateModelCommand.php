<?php
/**
 * Databases manager module
 * DatabasesManagerGenerateModelCommand.php
 *
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */

namespace DatabasesManager\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate class model for a specific module
 *
 * Class DatabasesManagerGenerateModelCommand
 */
class DatabasesManagerGenerateModelCommand extends DatabasesManagerSchemaParser
{
    /**
     * @inheritdoc
     */
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
            $this->generateModel($output);

            if ($input->getOption('generate-sql')) {
                $output->writeln(' ');
                $this->generateSql($output);
            }
        } catch (\Exception $exception) {
        }

        $this->postExecute($input);

        if (isset($exception)) {
            throw $exception;
        }
    }
}
