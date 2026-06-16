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
 * Auth main class
 * implements an external access with encrypted access ticket for notification returns
 *
 * @package     auth_ticket
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright   (C) 2012 onwards Valery Fremaux (http://www.activeprolearn.com)
 */

// phpcs:disable moodle.Commenting.ValidTags.Invalid
// Abusive PSR12 rule : adds useless spaces in string concatenation.
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceBefore
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceAfter

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/authlib.php');

use auth_ticket\ticket;

/**
 * Moodle Ticket based authentication.
 */
class auth_plugin_ticket extends auth_plugin_base {
    /**
     * The name of the component. Used by the configuration.
     */
    const COMPONENT_NAME = 'auth_ticket';

    /**
     * The component's legacy name for older moodles.
     */
    const LEGACY_COMPONENT_NAME = 'auth/ticket';

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'ticket';
        $config = get_config(self::COMPONENT_NAME);
        $legacyconfig = get_config(self::LEGACY_COMPONENT_NAME);
        $this->config = (object)array_merge((array)$legacyconfig, (array)$config);
    }

    /**
     * This function is normally used to determine if the username and password
     * are correct for local logins. Always returns false, as local users do not
     * need to login over mnet xmlrpc.
     *
     * @param string $username
     * @param string $password
     * @return bool Authentication success or failure.
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function user_login($username, $password) {

        // If everything failed, we let the next authentication plugin play.
        return false;
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
     * we do not propose any hooking for explicit login page
     *
     */
    public function loginpage_hook() {
        global $USER, $DB;
        global $user;

        $config = get_config(self::COMPONENT_NAME);

        if (empty($config->longvaliditydelay)) {
            // Ensure defaults are set.
            $this->process_config(null);
            $config = get_config(self::COMPONENT_NAME);
        }

        $sealedticket = optional_param('ticket', null, PARAM_BASE64);
        if (!$sealedticket) {
            // Do nothing but try other login methods.
            return false;
        }

        $ticket = ticket::decode($sealedticket);

        if (!empty($ticket)) {
            if (!$this->validate_timeguard($ticket)) {
                return false;
            }
            $user = $DB->get_record('user', ['username' => $ticket->username, 'deleted' => 0]);

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

    /**
     * Checks the time validity of a ticket.
     *
     * @param objectref $ticket
     */
    public function validate_timeguard($ticket) {

        $config = get_config(self::COMPONENT_NAME);

        if (empty($ticket->term)) {
            $ticket->term = 'short';
        }

        switch ($ticket->term) {
            case 'persistant':
                /*
                 * This is a passthrough. However, we consider that a 6 years old ticket
                 * might be an exterme limit.
                 */
                if ($ticket->date < (time() - $config->persistantvaliditydelay)) {
                    return false;
                }
                break;

            case 'long':
                if ($ticket->date < (time() - $config->longvaliditydelay)) {
                    return false;
                }
                break;

            case 'short':
            default:
                if ($ticket->date < (time() - $config->shortvaliditydelay)) {
                    return false;
                }
        }

        return true;
    }
}
