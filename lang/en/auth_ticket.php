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
 * Lang file for auth Ticket
 *
 * @package     auth_ticket
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright   (C) 2012 onwards Valery Fremaux (http://www.activeprolearn.com)
 */

$string['aesdes'] = 'AES/DES (Mysql only)';
$string['auth_ticket'] = 'Ticket direct access';
$string['auth_ticketdescription'] = 'This authentication mode allows uers to log in again on Moodle directly from a notification message they have received by mail. The encrypted ticket contains all necessay infomation to identify the user and know what  comeback url is asked for.';
$string['auth_tickettitle'] = 'Ticket direct access';
$string['configencryption'] = 'Encryption method';
$string['configencryption_desc'] = 'DES is simple method and uses a native Mysql function. This is not available on other databases. AES is not database dependant, but requires openssl being installed and configures on the server.';
$string['configinternalseed'] = 'Internal seed';
$string['configinternalseed_desc'] = 'Seed for the internal method. Should be set to an arbitrary value.';
$string['configlongvaliditydelay'] = 'Long lifetime delay';
$string['configlongvaliditydelay_desc'] = 'Time of life of the short timelife ticket (in seconds). Long lifetime tickets are usually used for browseback that are asynchronous and may be delayed for some days.';
$string['configpersistantvaliditydelay'] = 'Persistant lifetime delay';
$string['configpersistantvaliditydelay_desc'] = 'Persistant delay can be 0 (no limit), or should be set to a very large amount of seconds. Note that ticket peristance may be altered by the MNET key renewal whenn using DSA cryting method.';
$string['configrsaseed'] = 'RSA Seed';
$string['configrsaseed_desc'] = 'An arbitrary secret string for encrypting and decoding the ticket.';
$string['configshortvaliditydelay'] = 'Short lifetime delay';
$string['configshortvaliditydelay_desc'] = 'Time of life of the short timelife ticket (in seconds). Short lifetime tickets should be used for immediate browseback into moodle.';
$string['configtesturl'] = 'Copy this url into a non logged in browser.';
$string['configticketusessl'] = 'If enabled, the openssl encrypt method is used. If not, the internal AES_ENCRYPT function of the database is used';
$string['copytoclipboard'] = 'Copy check url';
$string['debugmsg'] = 'Sending Mail Notification to {$a->recipient}<br>{$a->notification}';
$string['decodeerror'] = 'Failed reading ticket';
$string['encodeerror'] = 'Failed encoding ticket';
$string['internal'] = 'Internal';
$string['no'] = 'No (Mysql and MariaDB only)';
$string['pluginname'] = 'Ticket direct access';
$string['privacy:metadata'] = "The Auth ticket do not store any data belonging to users";
$string['rsa'] = 'RSA (openssl)';
$string['testurl'] = 'Test url';
$string['ticket:get'] = 'Can get a ticket';
$string['ticket:validate'] = 'Can validate a ticket';
$string['ticketerror'] = 'Ticket deserializing error (using method {$a})';
$string['tickettimeguard'] = 'Validity period (hours)';
$string['usessl'] = 'Ticket encrypt method uses ssl';
$string['yes'] = 'Yes (more compatible)';
