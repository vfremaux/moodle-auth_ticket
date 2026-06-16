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
 * upgrade processes for this module.
 *
 * @package     auth_ticket
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2016 Valery Fremaux (http://www.mylearningfactory.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace auth_ticket;

// Abusive PSR12 rule : adds useless spaces in string concatenation.
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceBefore
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceAfter

use stdClass;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;
use auth_ticket\ticket;

/**
 * Auth Ticket Web services
 */
class external extends external_api {
    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_ticket_parameters() {
        $uidsourcedoc = 'Source for the user id, can be either \'id\', \'username\' or \'idnumber\'';
        return new external_function_parameters(
            [
                'uidsource' => new external_value(PARAM_ALPHA, $uidsourcedoc, VALUE_DEFAULT, 'id'),
                'uid' => new external_value(PARAM_TEXT, 'User id', VALUE_DEFAULT, 0),
                'url' => new external_value(PARAM_TEXT, 'Url to go', VALUE_DEFAULT, ''),
                'term' => new external_value(PARAM_TEXT, 'Term of ticket. short, long or persistant', VALUE_DEFAULT, ''),
                'duration' => new external_value(PARAM_INT, 'Validity duration in second', VALUE_DEFAULT, 0),
                'purpose' => new external_value(PARAM_TEXT, 'Additional payload for information', VALUE_DEFAULT, ''),
            ]
        );
    }

    /**
     * Get a valid access ticket for a user
     *
     * @param string $uidsource the source field for the user identifier.
     * @param string $uid the userid id.
     * @param string $url the target where to go url.
     * @param string $term term code of ticket, '', short long or persistant. If not given, requires a duration.
     * @param int $duration validity duration in seconds.
     * @param string $purpose Additional optional payload.
     *
     * @return external_description
     */
    public static function get_ticket($uidsource, $uid, $url, $term, $duration, $purpose) {
        global $CFG, $DB;

        $context = context_system::instance();
        if (!has_capability('auth/ticket:get', $context)) {
            throw new access_denied_exception('No capability with service user to get ticket');
        }

        $parameters = [
            'uidsource'  => $uidsource,
            'uid'  => $uid,
            'url'  => $url,
            'term'  => $term,
            'duration'  => $duration,
            'purpose'  => $purpose,
        ];
        // Calling core validation.
        self::validate_parameters(self::get_ticket_parameters(), $parameters);

        if (empty($term) && empty($duration)) {
            throw new invalid_parameter_exception('Either term or duration should be provided');
        }

        // Make a non blocking call.

        $user = $DB->get_record('user', [$uidsource => $uid]);
        if (!$user) {
            throw new invalid_parameter_exception('User not found using this id');
        }

        $results = new StdClass();
        $results->ticket = ticket::generate($user, $purpose, $url, null, $term, $duration);
        $results->endpoint = $CFG->wwwwroot.'/login/index.php';

        return $results;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function get_ticket_returns() {
        return new external_single_structure(
            [
                'ticket' => new external_value(PARAM_TEXT, 'Ticket'),
                'endpoint' => new external_value(PARAM_URL, 'Endpoint where to present ticket'),
            ]
        );
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function validate_ticket_parameters() {
        return new external_function_parameters(
            [
                'ticket' => new external_value(PARAM_TEXT, 'A ticket you hold'),
            ]
        );
    }

    /**
     * Validates a ticket and returns validation status.
     *
     * @param int $ticket
     */
    public static function validate_ticket($ticket) {

        $context = context_system::instance();
        if (!has_capability('auth/ticket:validate', $context)) {
            throw new access_denied_exception('No capability with service user to validate ticket');
        }

        $parameters = [
            'ticket' => $ticket,
        ];
        // Calling core validation.
        self::validate_parameters(self::validate_ticket_parameters(), $parameters);

        $ticketonbj = ticket::decode($ticket);

        return ticket::accept($ticketonbj);
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function validate_ticket_returns() {
        return new external_value(PARAM_BOOL, 'Validation ticket, true if validated or false');
    }
}
