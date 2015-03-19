<?php
/**
 * Databases manager module
 * DatabaseConfigurationEditForm.php
 *
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */

namespace DatabasesManager\Form;

/**
 * Class DatabaseConfigurationEditForm
 */
class DatabaseConfigurationEditForm extends DatabaseConfigurationForm
{
    /** @var string Form name */
    const NAME = 'databases_manager_configuration_form_edit';

    /**
     * @inheritdoc
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add('original_label', 'hidden', [
                'attr' => [
                    'id' =>  $this->getName() . '-original_label'
                ]
            ])
        ;

        parent::buildForm();
    }
}
