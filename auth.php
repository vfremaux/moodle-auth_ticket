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
defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/authlib.php');
require_once($CFG->dirroot.'/auth/ticket/lib.php');

/**
 * Moodle Ticket based authentication.
 */
class auth_plugin_ticket extends auth_plugin_base{

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'ticket';
        $this->config = get_config('auth/ticket');
    }

    /**
     * This function is normally used to determine if the username and password
     * are correct for local logins. Always returns false, as local users do not
     * need to login over mnet xmlrpc.
     *
     * @return bool Authentication success or failure.
     */
    public function user_login($username, $password) {

        // If everything failed, we let the next authentication plugin play.
        return false; // error("Remote MNET users cannot login locally.");
    }
    
    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    public function is_internal() {
        return false;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $page An object containing all the data for this page.
     */
    public function config_form($config, $err, $user_fields) {
        global $CFG;

        include($CFG->dirroot.'/auth/ticket/config.html');
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     *
     *
     * @param object $config Configuration object
     */
    public function process_config($config) {

        // Set to defaults if undefined.
        $config->tickettimeguard = ($config->tickettimeguard) ? $config->tickettimeguard * HOURSECS : HOURSECS * 24;
        // Save settings.
        set_config('tickettimeguard', $config->tickettimeguard, 'auth/ticket');

        return true;
    }

    /**
     * we do not propose any hooking for explicit login page
     *
     */
    public function loginpage_hook() { 
        global $USER, $DB;
        global $frm; // We must catch the login/index.php $user credential holder.
        global $user;

        $config = get_config('auth/ticket');

        if (!isset($config->tickettimeguard)) {
            $config->tickettimeguard = HOURSECS * 24;
            set_config('tickettimeguard', $config->tickettimeguard, 'auth/ticket');
        }

        $sealedticket = optional_param('ticket', null, PARAM_RAW);
        if (!$sealedticket) {
            // Do nothing other login methods.
            return false;
        }

        $ticket = ticket_decode($sealedticket);

        if (!empty($ticket)) {
            if ($ticket->date < time() - $config->tickettimeguard) {
                return false;
            }

            $user = $DB->get_record('user', array('username' => $ticket->username, 'deleted' => 0));

            $user = $USER = complete_user_login($user);
            $url = str_replace('\\', '', $ticket->wantsurl);
            redirect($url);
        }
        return false;
    }

    /**
     *
     */
    public function logoutpage_hook() {
        return;
    }
}
