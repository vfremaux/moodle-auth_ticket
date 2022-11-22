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
 * Version details.
 *
 * @package     auth_ticket
 * @category    auth
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright   (C) 2010 ValEISTI (http://www.valeisti.fr)
 * @copyright   (C) 2012 onwards Valery Fremaux (https://www.mylearningfactory.com)
 * @copyright   (C) 2015 onwards Valery Fremaux (https://www.activeprolearn.com)
 */

defined('MOODLE_INTERNAL') || die;

$plugin->version   = 2018110900;        // The current plugin version (Date: YYYYMMDDXX).
$plugin->requires  = 2022042200;        // Requires this Moodle version.
$plugin->component = 'auth_ticket';     // Full name of the plugin (used for diagnostics).
$plugin->release = '4.0.0 (Build 2018110900)';
$plugin->maturity = MATURITY_STABLE;
$plugin->supported = [40, 40];

// Non moodle attributes.
$plugin->codeincrement = '4.0.0005';