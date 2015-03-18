<?php
/**
 * Databases manager module
 * DatabasesManager.php
 *
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */

namespace DatabasesManager\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigurationHandler
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

    public function parse()
    {
        return (array) Yaml::parse($this->configurationPath);
    }

    public function dump($databasesConfig)
    {
        $yml = Yaml::dump($databasesConfig);

        $fileSystem = new Filesystem;
        $fileSystem->dumpFile($this->configurationPath, $yml);
    }
}
