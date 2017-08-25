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
 */

$string['auth_ticket'] = 'Ticket direct access';
$string['auth_tickettitle'] = 'Ticket direct access';
$string['copytoclipboard'] = 'Copy check url';
$string['configlongtermtickettimeguard'] = 'Defines the validity delay for the long term ticket. After this delay the ticket is refused.';
$string['configtesturl'] = 'Copy this url into a non logged in browser.';
$string['configtickettimeguard'] = 'Defines the validity delay for the ticket. After this delay the ticket is refused.';
$string['configticketusessl'] = 'If enabled, the openssl encrypt method is used. If not, the internal AES_ENCRYPT function of the database is used';
$string['decodeerror'] = 'Failed reading ticket';
$string['encodeerror'] = 'Failed encoding ticket';
$string['longtermtickettimeguard'] = 'Validity period for long tickets (days)';
$string['no'] = 'No (Mysql and MariaDB only)';
$string['pluginname'] = 'Ticket direct access';
$string['testurl'] = 'Test url';
$string['ticketerror'] = 'Ticket deserializing error';
$string['tickettimeguard'] = 'Validity period (hours)';
$string['usessl'] = 'Ticket encrypt method uses ssl';
$string['yes'] = 'Yes (more compatible)';

$string['auth_ticketdescription'] = 'This authentication mode allows uers to log in again on Moodle directly from a notification
message they have received by mail. The encrypted ticket contains all necessay infomation to identify the user and know what comeback url
is asked for.';

