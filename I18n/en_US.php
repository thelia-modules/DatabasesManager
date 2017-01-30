<?php
/*************************************************************************************/
/*                                                                                   */
/*    DatabasesManager - A Thelia 2 databases manager module                         */
/*    Copyright (C) 2015-2017 Jérôme BILLIRAS                                        */
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

/**
 * Locale for english - United States
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
return [
    'SHARED_VIEW_TITLE' => 'Databases shared configuration.',
    'ENV_VIEW_TITLE' => 'Databases configuration for "%env" environment.',

    'NO_CONFIG' => 'No configuration',

    'TABLE_HEAD_LABEL' => 'Label',
    'TABLE_HEAD_HOST' => 'Host',
    'TABLE_HEAD_USER' => 'User',
    'TABLE_HEAD_PASS' => 'Password',
    'TABLE_HEAD_DB_NAME' => 'Database name',
    'TABLE_HEAD_DB_CHARSET' => 'Database charset',
    'TABLE_HEAD_ACTION' => 'Action',

    'ADD_BUTTON' => 'Add a configuration',

    'PWDS_ACTION_HIDE' => 'Hide passwords',
    'PWD_ACTION_SHOW' => 'Show password',
    'PWD_ACTION_HIDE' => 'Hide password',

    'ACTION_EDIT' => 'Edit database',
    'ACTION_DELETE' => 'Delete database',

    'FORM_LABEL_LABEL' => 'Label',
    'FORM_LABEL_HOST' => 'Host',
    'FORM_LABEL_USER' => 'User',
    'FORM_LABEL_PASS' => 'Password',
    'FORM_LABEL_DB_NAME' => 'Database name',
    'FORM_LABEL_DB_CHARSET' => 'Database charset',
    'FORM_DB_CHARSET_VALUE_DEFAULT' => 'Database default charset',

    'MODAL_TITLE_ADD_SHARED' => 'Add a shared database configuration',
    'MODAL_TITLE_ADD_ENV' => 'Add a database configuration on environment "%env"',
    'CONFIG_EXISTS' => 'Database configuration with label "%label" already exists. Use editing or change the current label.',

    'MODAL_TITLE_EDIT_SHARED' => 'Edit a shared database configuration',
    'MODAL_TITLE_EDIT_ENV' => 'Edit a database configuration on environment "%env"',

    'MODAL_TITLE_DELETE_SHARED' => 'Delete a shared database configuration',
    'MODAL_TITLE_DELETE_ENV' => 'Delete a database configuration on environment "%env"',
    'MODAL_DELETE_CONFIRM' => 'Do you really want to delete the database configuration &quot;%label&quot;&nbsp;?',
    'CONFIG_DOES_NOT_EXIST' => 'Database configuration with label "%label" doesn\'t exist.',
    'CONFIG_DELETED' => 'Database configuration with label "%label" was deleted.',
];
