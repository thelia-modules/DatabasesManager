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

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EditForm
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
class EditForm extends BaseForm
{
    /** @var string Form name */
    const NAME = 'databases_manager_configuration_form_edit';

    /**
     * @inheritdoc
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'original_label',
                'hidden',
                [
                    'constraints' => [
                        new Assert\NotBlank
                    ]
                ]
            )
        ;

        parent::buildForm();
    }
}
