<?php
/**
 * Databases manager module
 * DatabaseConfigurationForm.php
 *
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */

namespace DatabasesManager\Form;

use DatabasesManager\DatabasesManager;
use DatabasesManager\Validator\Constraints as ModuleAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Thelia\Form\BaseForm;

/**
 * Class DatabaseConfigurationForm
 */
class DatabaseConfigurationForm extends BaseForm
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
        $this->formBuilder
            ->add('label', 'text', [
                'label' => $this->translator->trans('FORM_LABEL_LABEL', [], DatabasesManager::DOMAIN_NAME . '.ai'),
                'label_attr' => [
                    'for' => $this->getName() . '-label'
                ],
                'constraints' => [
                    new Assert\NotBlank
                ]
            ])
            ->add('host', 'text', [
                'label' => $this->translator->trans('FORM_LABEL_HOST', [], DatabasesManager::DOMAIN_NAME . '.ai'),
                'label_attr' => [
                    'for' => $this->getName() . '-host'
                ],
                'constraints' => [
                    new Assert\NotBlank,
                    new ModuleAssert\Url
                ]
            ])
            ->add('user', 'text', [
                'label' => $this->translator->trans('FORM_LABEL_USER', [], DatabasesManager::DOMAIN_NAME . '.ai'),
                'label_attr' => [
                    'for' => $this->getName() . '-user'
                ],
                'constraints' => [
                    new Assert\NotBlank
                ]
            ])
            ->add('pass', 'text', [
                'label' => $this->translator->trans('FORM_LABEL_PASS', [], DatabasesManager::DOMAIN_NAME . '.ai'),
                'label_attr' => [
                    'for' => $this->getName() . '-pass'
                ]
            ])
            ->add('db_name', 'text', [
                'label' => $this->translator->trans('FORM_LABEL_DB_NAME', [], DatabasesManager::DOMAIN_NAME . '.ai'),
                'label_attr' => [
                    'for' => $this->getName() . '-db_name'
                ],
                'constraints' => [
                    new Assert\NotBlank
                ]
            ])
        ;
    }
}
