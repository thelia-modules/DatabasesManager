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
 * Locale for french - France
 *
 * @author Jérôme Billiras <jerome DOT billiras PLUS github AT gmail DOT com>
 */
return [
    'SHARED_VIEW_TITLE' => 'Configuration partagée des bases de données.',
    'ENV_VIEW_TITLE' => 'Configuration des bases de données de l\'environnement "%env".',

    'NO_CONFIG' => 'Aucune configuration',

    'TABLE_HEAD_LABEL' => 'Libellé',
    'TABLE_HEAD_HOST' => 'Hôte',
    'TABLE_HEAD_USER' => 'Utilisateur',
    'TABLE_HEAD_PASS' => 'Mot de passe',
    'TABLE_HEAD_DB_NAME' => 'Nom de la base',
    'TABLE_HEAD_DB_CHARSET' => 'Jeu de caractères de la base',
    'TABLE_HEAD_ACTION' => 'Action',

    'ADD_BUTTON' => 'Ajouter une configuration',

    'PWDS_ACTION_HIDE' => 'Masquer les mots de passe',
    'PWD_ACTION_SHOW' => 'Afficher le mot de passe',
    'PWD_ACTION_HIDE' => 'Masquer le mot de passe',

    'ACTION_EDIT' => 'Modifier la base de données',
    'ACTION_DELETE' => 'Supprimer la base de données',

    'FORM_LABEL_LABEL' => 'Libellé',
    'FORM_LABEL_HOST' => 'Hôte',
    'FORM_LABEL_USER' => 'Utilisateur',
    'FORM_LABEL_PASS' => 'Mot de passe',
    'FORM_LABEL_DB_NAME' => 'Nom de la base',
    'FORM_LABEL_DB_CHARSET' => 'Jeu de caractères de la base',
    'FORM_DB_CHARSET_VALUE_DEFAULT' => 'Jeu de caractères par défaut de la base',

    'MODAL_TITLE_ADD_SHARED' => 'Ajouter une configuration partagée de base de données',
    'MODAL_TITLE_ADD_ENV' => 'Ajouter une configuration de base de données sur l\'environnement "%env"',
    'CONFIG_EXISTS' => 'Une configuration de base de données avec le libellé "%label" existe déjà. Utiliser l\'édition ou modifier ce libellé.',

    'MODAL_TITLE_EDIT_SHARED' => 'Editer une configuration partagée de base de données',
    'MODAL_TITLE_EDIT_ENV' => 'Editer une configuration de base de données sur l\'environnement "%env"',

    'MODAL_TITLE_DELETE_SHARED' => 'Supprimer une configuration partagée de base de données',
    'MODAL_TITLE_DELETE_ENV' => 'Supprimer une configuration de base de données sur l\'environnement "%env"',
    'MODAL_DELETE_CONFIRM' => 'Voulez-vous vraiment supprimer la configuration de base de données &quot;%label&quot;&nbsp;?',
    'CONFIG_DOES_NOT_EXIST' => 'La configuration de la base de données avec le libellé "%label" n\'existe pas.',
    'CONFIG_DELETED' => 'La configuration de la base de données avec le libellé "%label" a été supprimée.',
];
