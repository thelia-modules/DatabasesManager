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

namespace DatabasesManager\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class ConfigurationHook
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class ConfigurationHook extends BaseHook
{
    /**
     * @var string Current environment
     */
    protected $environment;

    /**
     * Class constructor
     *
     * @param string $environment Current environment
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Add module configuration content
     *
     * @param HookRenderEvent $hookRenderEvent
     */
    public function onModuleConfiguration(HookRenderEvent $hookRenderEvent)
    {
        $hookRenderEvent->add(
            $this->render(
                'module_configuration.html',
                [
                    'kernelEnvironment' => $this->environment
                ]
            )
        );
    }

    /**
     * Add module configuration content script
     *
     * @param HookRenderEvent $hookRenderEvent
     */
    public function onModuleConfigJs(HookRenderEvent $hookRenderEvent)
    {
        $hookRenderEvent->add(
            $this->render(
                'module-config-js.html'
            )
        );
    }
}
