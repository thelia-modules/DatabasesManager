<?php
/**
 * Databases manager module
 * KernelListener.php
 *
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */

namespace DatabasesManager\EventListener;

use DatabasesManager\Handler\ConfigurationHandler;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelListener
 */
class KernelListener implements EventSubscriberInterface
{
    /**
     * Class constructor
     *
     * @param \DatabasesManager\Handler\ConfigurationHandler $configurationHandler DatabasesManager config handler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configHandler = $configurationHandler;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['connectAllDatabases', 100]
            ]
        ];
    }

    /**
     * Initialize Propel connections to databases
     */
    public function connectAllDatabases()
    {
        $manager = new ConnectionManagerSingle;

        foreach ($this->configHandler->parse() as $label => $databaseConfig) {
            if (empty($databaseConfig['host']) || empty($databaseConfig['user']) || empty($databaseConfig['db_name'])) {
                continue;
            }

            $manager->setConfiguration([
                'dsn' => 'mysql:host=' . $databaseConfig['host'] . ';dbname=' . $databaseConfig['db_name'],
                'user' => $databaseConfig['user'],
                'password' => $databaseConfig['pass'],
            ]);

            /** @var \Propel\Runtime\ServiceContainer\StandardServiceContainer $serviceContainer */
            $serviceContainer = Propel::getServiceContainer();
            $serviceContainer->setAdapterClass($label, 'mysql');
            $serviceContainer->setConnectionManager($label, $manager);
        }
    }
}
