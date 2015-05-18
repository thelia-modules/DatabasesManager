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

namespace DatabasesManager\Controller;

use DatabasesManager\DatabasesManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Tools\URL;

/**
 * Class ConfigurationController
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class ConfigurationController extends BaseAdminController
{
    /**
     * Handle databases manager add config request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Thelia\Core\HttpFoundation\Response
     */
    public function addConfigAction()
    {
        $authFail = $this->checkAuth(AdminResources::MODULE, DatabasesManager::DOMAIN_NAME, AccessManager::CREATE);
        if ($authFail !== null) {
            return $authFail;
        }

        /** @var \DatabasesManager\Form\AddForm $form */
        $form = $this->createForm('databases.manager.form.add');
        try {
            $this->validateForm($form);

            /** @var \DatabasesManager\Handler\ConfigurationHandler $configHandler */
            $configHandler = $this->container->get('databases.manager.config.handler');

            $databasesConfiguration = $configHandler->parse();

            $newConfigKey = $form->getForm()->get('label')->getData();
            if (array_key_exists($newConfigKey, $databasesConfiguration)) {
                throw new FormValidationException(
                    $this->getTranslator()->trans(
                        'CONFIG_EXISTS',
                        [
                            '%label' => $newConfigKey
                        ],
                        DatabasesManager::DOMAIN_NAME
                    )
                );
            }

            $databasesConfiguration[$newConfigKey] = [
                'host' => $form->getForm()->get('host')->getData(),
                'user' => $form->getForm()->get('user')->getData(),
                'pass' => $form->getForm()->get('pass')->getData(),
                'db_name' => $form->getForm()->get('db_name')->getData()
            ];

            $configHandler->dump($databasesConfiguration);

            $response = $this->getRedirectToModuleConfiguration();
        } catch (FormValidationException $exception) {
            if (!$form->getForm()->isValid()) {
                $this->setupFormErrorContext(
                    'Databases manager add configuration',
                    $this->createStandardFormValidationErrorMessage($exception),
                    $form
                );
                $form->setErrorMessage(null);
            } else {
                $this->setupFormErrorContext(
                    'Databases manager add configuration',
                    $exception->getMessage(),
                    $form
                );
            }

            $response = $this->render(
                'module-configure',
                [
                    'module_code' => DatabasesManager::MODULE_CODE
                ]
            );
        }

        return $response;
    }

    /**
     * Handle databases manager edit config request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Thelia\Core\HttpFoundation\Response
     */
    public function editConfigAction()
    {
        $authFail = $this->checkAuth(AdminResources::MODULE, DatabasesManager::DOMAIN_NAME, AccessManager::CREATE);
        if ($authFail !== null) {
            return $authFail;
        }

        /** @var \DatabasesManager\Form\EditForm $form */
        $form = $this->createForm('databases.manager.form.edit');
        try {
            $this->validateForm($form);

            /** @var \DatabasesManager\Handler\ConfigurationHandler $configHandler */
            $configHandler = $this->container->get('databases.manager.config.handler');

            $databasesConfiguration = $configHandler->parse();

            $originalConfigKey = $form->getForm()->get('original_label')->getData();
            if (!array_key_exists($originalConfigKey, $databasesConfiguration)) {
                throw new FormValidationException(
                    $this->getTranslator()->trans(
                        'CONFIG_DOES_NOT_EXIST',
                        [
                            '%label' => $originalConfigKey
                        ],
                        DatabasesManager::DOMAIN_NAME
                    )
                );
            }
            unset($databasesConfiguration[$originalConfigKey]);

            $newConfigKey = $form->getForm()->get('label')->getData();
            if (array_key_exists($newConfigKey, $databasesConfiguration)) {
                throw new FormValidationException(
                    $this->getTranslator()->trans(
                        'CONFIG_EXISTS',
                        [
                            '%label' => $newConfigKey
                        ],
                        DatabasesManager::DOMAIN_NAME
                    )
                );
            }

            $databasesConfiguration[$newConfigKey] = [
                'host' => $form->getForm()->get('host')->getData(),
                'user' => $form->getForm()->get('user')->getData(),
                'pass' => $form->getForm()->get('pass')->getData(),
                'db_name' => $form->getForm()->get('db_name')->getData()
            ];

            $configHandler->dump($databasesConfiguration);

            $response = $this->getRedirectToModuleConfiguration();
        } catch (FormValidationException $exception) {
            if (!$form->getForm()->isValid()) {
                $this->setupFormErrorContext(
                    'Databases manager edit configuration',
                    $this->createStandardFormValidationErrorMessage($exception),
                    $form
                );
                $form->setErrorMessage(null);
            } else {
                $this->setupFormErrorContext(
                    'Databases manager edit configuration',
                    $exception->getMessage(),
                    $form
                );
            }

            $response = $this->render(
                'module-configure',
                [
                    'module_code' => DatabasesManager::MODULE_CODE
                ]
            );
        }

        return $response;
    }

    /**
     * Handle databases manager delete config request
     *
     * @param string $configKey Database configuration key
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Thelia\Core\HttpFoundation\Response
     */
    public function deleteConfigAction($configKey)
    {
        $authFail = $this->checkAuth(AdminResources::MODULE, DatabasesManager::DOMAIN_NAME, AccessManager::DELETE);
        if ($authFail !== null) {
            return $authFail;
        }

        /** @var \DatabasesManager\Handler\ConfigurationHandler $configHandler */
        $configHandler = $this->container->get('databases.manager.config.handler');

        $databasesConfiguration = $configHandler->parse();

        if (array_key_exists($configKey, $databasesConfiguration)) {
            unset($databasesConfiguration[$configKey]);

            $configHandler->dump($databasesConfiguration);

            $this->getSession()->getFlashBag()->add(
                'databasesmanager.delete.success',
                $this->getTranslator()->trans(
                    'CONFIG_DELETED',
                    [
                        '%label' => $configKey
                    ],
                    DatabasesManager::DOMAIN_NAME
                )
            );
        } else {
            $this->getSession()->getFlashBag()->add(
                'databasesmanager.delete.error',
                $this->getTranslator()->trans(
                    'CONFIG_DOES_NOT_EXIST',
                    [
                        '%label' => $configKey
                    ],
                    DatabasesManager::DOMAIN_NAME
                )
            );
        }

        return $this->getRedirectToModuleConfiguration();
    }

    /**
     * Get redirection response to module configuration
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getRedirectToModuleConfiguration()
    {
        return RedirectResponse::create(
            URL::getInstance()->absoluteUrl('/admin/module/' . DatabasesManager::MODULE_CODE)
        );
    }
}
