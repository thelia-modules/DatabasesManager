<?php
/**
 * Databases manager module
 * DatabasesManagerGenerateSqlCommand.php
 *
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */

namespace DatabasesManager\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate sql for a specific module
 *
 * Class DatabasesManagerGenerateSqlCommand
 */
class DatabasesManagerGenerateSqlCommand extends DatabasesManagerSchemaParser
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
