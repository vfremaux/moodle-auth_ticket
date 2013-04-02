<?php // $Id: auth.php,v 1.1 2010/03/28 20:14:11 vf Exp $

/**
 * Moodle - Modular Object-Oriented Dynamic Learning Environment
 *          http://moodle.org
 * Copyright (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package auth-ticket
 * @category auth
 * @author     Valery Fremaux <valery@valeisti.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 * @copyright  (C) 2010 ValEISTI (http://www.valeisti.fr)
 *
 * implements an external access with encrypted access ticket for notification returns
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/authlib.php');
require_once($CFG->dirroot.'/auth/ticket/lib.php');

/**
 * Moodle Ticket based authentication.
 */
class auth_plugin_ticket extends auth_plugin_base{

    /**
     * Constructor.
     */
    function auth_plugin_ticket() {
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
    function user_login($username, $password) {
        global $CFG, $SESSION, $USER;

        // if everything failed, we let the next authentication plugin play
        return false; // error("Remote MNET users cannot login locally.");
    }
    
    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
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
    function config_form($config, $err, $user_fields) {
        global $CFG;

        include "config.html";
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     *
     *
     * @param object $config Configuration object
     */
    function process_config($config) {
        global $CFG;

        // set to defaults if undefined
        $config->tickettimeguard = ($config->tickettimeguard) ? $config->tickettimeguard * HOURSECS : HOURSECS * 24 ;
        // save settings
        set_config('tickettimeguard', $config->tickettimeguard, 'auth/ticket');

        return true;
    }

    /**
    * we do not propose any hooking for explicit login page
    *
    */
    function loginpage_hook() { 
        global $SESSION, $USER, $DB;       
        global $frm; // we must catch the login/index.php $user credential holder.
        global $user;

        $config = get_config('auth/ticket');
        
        if (!isset($config->tickettimeguard)) {
            $config->tickettimeguard = HOURSECS * 24;
            set_config('tickettimeguard', $config->tickettimeguard, 'auth/ticket');
        }

        $sealedticket = optional_param('ticket', null, PARAM_RAW);
        if (!$sealedticket) return false; // do nothing other login methods
        
        $ticket = ticket_decodeTicket($sealedticket);

        if (!empty($ticket)){
            if ($ticket->date < time() - $config->tickettimeguard){
                return false;
            }
            // print_object($ticket);
            $user = $DB->get_record('user', array('username' => $ticket->username, 'deleted' => 0));
            
            $user = $USER = complete_user_login($user);
            
            redirect($ticket->wantsurl);
        }
        
        return false;        
    }

    /**
    *
    *
    */
    function logoutpage_hook() {
        global $USER, $CFG, $redirect;

    }

}

?>
