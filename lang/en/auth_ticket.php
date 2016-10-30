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
 * @author      Valery Fremaux <valery@valeisti.fr>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright   (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 * @copyright   (C) 2010 ValEISTI (http://www.valeisti.fr)
 */

$string['pluginname'] = 'Ticket direct access';
$string['auth_ticket'] = 'Ticket direct access';
$string['auth_tickettitle'] = 'Ticket direct access';
$string['auth_courseshopdescription'] = 'This authentication mode allows uers to log in again on Moodle directly from a notification message they have received by mail. The encrypted ticket contains all necessay infomation to identify the user and know what comeback url is asked for.';
$string['tickettimeguard'] = 'Validity period (hours)';
$string['configtickettimeguard'] = 'Defines the validity delay for the ticket. After this delay the ticket is refused.';
$string['decodeerror'] = 'Failed reading ticket';
$string['encodeerror'] = 'Failed encoding ticket';
$string['ticketerror'] = 'Ticket deserializing error';

$string['no'] = 'No';
$string['yes'] = 'Yes (more compatible)';

