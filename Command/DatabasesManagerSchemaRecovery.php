<?php
/**
 * Databases manager module
 * DatabasesManagerSchemaParser.php
 *
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */

namespace DatabasesManager\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Thelia\Command\BaseModuleGenerate;

/**
 * Class DatabasesManagerSchemaRecovery
 */
class DatabasesManagerSchemaRecovery extends BaseModuleGenerate
{
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this
            ->setName('module:schema:recovery')
            ->setDescription('Recover split schema if unexpected problem append before merge')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Module name'
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
        /** @var \Symfony\Component\Finder\SplFileInfo $file */

        $this->module = $this->formatModuleName($input->getArgument('name'));
        $this->moduleDirectory = THELIA_MODULE_DIR . $this->module;

        $filesystem = new Filesystem;

        $finder = (new Finder)
            ->files()
            ->name('#^[0-9]+_split_schema\.xml$#')
            ->in($this->moduleDirectory . DS . 'Config')
        ;

        $xxSplitRemoved = false;
        foreach ($finder as $file) {
            $filesystem->remove($file);
            $xxSplitRemoved = true;
        }
        if ($xxSplitRemoved) {
            $output->renderBlock([
                '',
                'xx_split_schema.xml successfully removed',
                ''
            ], 'bg=green;fg=black');
            $output->writeln('');
        }

        $finder = (new Finder)
            ->files()
            ->name('#^split[0-9a-f]{13}$#')
            ->in($this->moduleDirectory . DS . 'Config')
        ;

        if ($finder->count() === 1) {
            foreach ($finder as $file) {
                $filesystem->rename($file, $this->moduleDirectory . DS . 'Config' . DS . 'schema.xml');
            }
            $output->renderBlock([
                '',
                'schema.xml successfully recovered',
                ''
            ], 'bg=green;fg=black');
        } else {
            $output->renderBlock([
                '',
                'No schema.xml found to recover',
                ''
            ], 'bg=yellow;fg=black');
        }
    }
}
