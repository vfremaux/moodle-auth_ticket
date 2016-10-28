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
 * @package auth_ticket
 * @category auth
 * @author     Valery Fremaux <valery@valeisti.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 * @copyright  (C) 2010 ValEISTI (http://www.valeisti.fr)
 *
 * implements an external access with encrypted access ticket for notification returns
 */

$string['pluginname'] = 'Accès direct par ticket';
$string['auth_ticket'] = 'Accès direct par ticket';
$string['auth_tickettitle'] = 'Accès direct par ticket';
$string['auth_courseshopdescription'] = 'Ce mode d\'authentification permet à des utilisteurs ayant reçu une notification par courriel de se connecter directement sur leur compte sans passer par la page de login. Le ticket crypté leur ayant été transmis contient toutes les informations suffisantes de login pendant une certaine durée de temps de validité. Au delà de cette durée le ticket est perdu.';
$string['tickettimeguard'] = 'Temps de validité du ticket (en heures)';
$string['configtickettimeguard'] = 'Ce temps détermine la durée de validité d\'un ticket de notification. Un accès avec un ticket plus âgé que cette durée est rejeté.';
$string['decodeerror'] = 'Erreur de lecture du ticket';
$string['encodeerror'] = 'Erreur d\'encodage du ticket';

$string['no'] = 'Non';
$string['yes'] = 'Oui (plus compatible)';

