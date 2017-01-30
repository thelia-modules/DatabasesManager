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

namespace DatabasesManager\Form;

use DatabasesManager\DatabasesManager;
use DatabasesManager\Validator\Constraints as ModuleAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Thelia\Form\BaseForm as TheliaBaseForm;

/**
 * Class BaseForm
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class BaseForm extends TheliaBaseForm
{
    /** @var string Form name */
    const NAME = 'databases_manager_configuration_form';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @inheritdoc
     */
    protected function buildForm()
    {
        /** @var \DatabasesManager\Handler\ConfigurationHandler $configHandler */
        $configHandler = $this->container->get('databases.manager.config.handler');

        $this->formBuilder
            ->add(
                'label',
                'text',
                [
                    'label' => $this->translator->trans('FORM_LABEL_LABEL', [], DatabasesManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => $this->getName() . '-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank
                    ]
                ]
            )
            ->add(
                'host',
                'text',
                [
                    'label' => $this->translator->trans('FORM_LABEL_HOST', [], DatabasesManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => $this->getName() . '-host'
                    ],
                    'constraints' => [
                        new Assert\NotBlank,
                        new ModuleAssert\Url
                    ]
                ]
            )
            ->add(
                'user',
                'text',
                [
                    'label' => $this->translator->trans('FORM_LABEL_USER', [], DatabasesManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => $this->getName() . '-user'
                    ],
                    'constraints' => [
                        new Assert\NotBlank
                    ]
                ]
            )
            ->add(
                'pass',
                'text',
                [
                    'label' => $this->translator->trans('FORM_LABEL_PASS', [], DatabasesManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => $this->getName() . '-pass'
                    ]
                ]
            )
            ->add(
                'db_name',
                'text',
                [
                    'label' => $this->translator->trans('FORM_LABEL_DB_NAME', [], DatabasesManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => $this->getName() . '-db_name'
                    ],
                    'constraints' => [
                        new Assert\NotBlank
                    ]
                ]
            )
            ->add(
                'db_charset',
                'choice',
                [
                    'label' => $this->translator->trans('FORM_LABEL_DB_CHARSET', [], DatabasesManager::DOMAIN_NAME),
                    'label_attr' => [
                        'for' =>  $this->getName() . '-charset'
                    ],
                    'choices' => $configHandler->getCharsets(),
                    'constraints' => [
                        new Assert\NotNull
                    ]
                ]
            )
        ;
    }
}
