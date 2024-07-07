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
 * Auth Ticket external functions and service definitions.
 *
 * @package    auth_ticket
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  2016 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = [
    'auth_ticket_get_ticket' => [
        'classname' => 'auth_ticket_external',
        'methodname' => 'get_ticket',
        'classpath' => 'auth/ticket/externallib.php',
        'description' => 'Get an access ticket for a user immediate access',
        'type' => 'read',
        'capabilities' => 'auth/ticket:get',
    ],

    'auth_ticket_validate_ticket' => [
        'classname' => 'auth_ticket_external',
        'methodname' => 'validate_ticket',
        'classpath' => 'auth/ticket/externallib.php',
        'description' => 'Get an access ticket for a user immediate access',
        'type' => 'read',
        'capabilities' => 'auth/ticket:validate',
    ],
];
