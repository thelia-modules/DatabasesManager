<?php
/**
 * Databases manager module
 * ConfigurationController.php
 *
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */

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

        /** @var \DatabasesManager\Form\DatabaseConfigurationForm $form */
        $form = $this->createForm('databases.manager.form');
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
                        DatabasesManager::DOMAIN_NAME . '.ai'
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
                    DatabasesManager::DOMAIN_NAME . '.ai'
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
                    DatabasesManager::DOMAIN_NAME . '.ai'
                )
            );
        }

        return $this->getRedirectToModuleConfiguration();;
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
