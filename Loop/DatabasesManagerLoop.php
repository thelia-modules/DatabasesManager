<?php
/**
 * Databases manager module
 * DatabasesManager.php
 *
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */

namespace DatabasesManager\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class DatabasesManagerLoop
 */
class DatabasesManagerLoop extends BaseLoop implements ArraySearchLoopInterface
{
    /**
     * @inheritdoc
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection;
    }

    /**
     * @inheritdoc
     */
    public function buildArray()
    {
        /** @var \DatabasesManager\Handler\ConfigurationHandler $configHandler */
        $configHandler = $this->container->get('databases.manager.config.handler');

        return $configHandler->parse();
    }

    /**
     * @inheritdoc
     */
    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $label => $databaseConfig) {
            $loopResultRow = new LoopResultRow;

            $loopResultRow
                ->set('LABEL', $label)
                ->set('HOST', $databaseConfig['host'])
                ->set('USER', $databaseConfig['user'])
                ->set('PASS', $databaseConfig['pass'])
                ->set('DB_NAME', $databaseConfig['db_name'])
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
