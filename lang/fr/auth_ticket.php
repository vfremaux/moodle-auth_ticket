<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     auth_ticket
 * @category    auth
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright   (C) 2010 ValEISTI (http://www.valeisti.fr)
 * @copyright   (C) 2012 onwards Valery Fremaux (http://www.mylearningfactory.com)
 *
 * Implements an external access with encrypted access ticket for notification returns.
 */

$string['auth_ticket'] = 'Accès direct par ticket';
$string['auth_ticketdescription'] = 'Ce mode d\'authentification permet à des utilisteurs ayant reçu une notification par courriel de se connecter directement sur leur compte sans passer par la page de login. Le ticket crypté leur ayant été transmis contient toutes les informations suffisantes de login pendant une certaine durée de temps de validité. Au delà de cette durée le ticket est perdu.';
$string['auth_tickettitle'] = 'Accès direct par ticket';
$string['configtesturl'] = 'Copiez cette url dans un navigateur non connecté.';
$string['configlongtermtickettimeguard'] = 'Ce temps détermine la durée de validité d\'un ticket de notification à longue durée. Un accès avec un ticket plus âgé que cette durée est rejeté.';
$string['configtickettimeguard'] = 'Ce temps détermine la durée de validité d\'un ticket de notification. Un accès avec un ticket plus âgé que cette durée est rejeté.';
$string['configticketusessl'] = 'Si oui, le ticket est crypté/décrypté en utilisant les librairies openssl du système. Si non, c\'est la fonction d\'encryption interne de la base de données qui sera utilisée.';
$string['decodeerror'] = 'Erreur de lecture du ticket';
$string['encodeerror'] = 'Erreur d\'encodage du ticket';
$string['longtermtickettimeguard'] = 'Temps de validité du ticket long (en jours)&nbsp;';
$string['no'] = 'Non (Mysql et MariaDB uniquement)';
$string['pluginname'] = 'Accès direct par ticket';
$string['testurl'] = 'Url de test';
$string['ticketerror'] = 'Erreur de désérialisaton du ticket';
$string['tickettimeguard'] = 'Temps de validité du ticket court (en heures)&nbsp;';
$string['usessl'] = 'Utiliser SSL pour crypter le ticket&nbsp;';
$string['yes'] = 'Oui (plus compatible)';

