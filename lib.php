<?php

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
 * Ticket related library
 */

/**
* simple sending to user with return ticket.
* The return ticket allows auser receiving amail to enter immediately
* the platform being connected automatically during a hold time.
* the ticket is catched by a custom auth module that decodes generated ticket and
* let user through.
* Only recipients that have a valid Moodle account can use an access tickets.
* The ticket is only valid on the given return URL and cannot be used for going
* to another location, unless user's profile other mention.
* @param object $recipient
* @param object $sender
* @param string $title
* @param string $notification
* @param string $notification_html
* @param string $url
* @param string $purpose
*/

function ticket_notify($recipient, $sender, $title, $notification, $notification_html, $url, $purpose = ''){
    global $CFG;

    $ticket = ticket_generate($recipient, $purpose, $url);
    $notification_html = str_replace('<%%TICKET%%>', $ticket, $notification_html);

    // tickets only can be sent as HTML href values.
    $notification = str_replace('<%%TICKET%%>', '', $notification);
    

    // todo send the email to user
    if ($CFG->debugsmtp) echo "Sending Mail Notification to " . fullname($recipient) .'<br/>'.$notification_html;
    email_to_user($recipient, $sender, $title, $notification, $notification_html);
}

/**
* send a notification message to all users having the role in the given context.
* @param int $roleid
* @param object $context
* @param object $sender
* @param string $title
* @param string $notification
* @param string $notification_html
* @param string $url
* @param string $purpose
*/
function ticket_notifyrole($roleid, $context, $sender, $title, $notification, $notification_html, $url, $purpose = ''){
    global $CFG, $USER, $DB;

    // get all users assigned to that role in context
    $role = $DB->get_record('role', array('id' => $roleid));
    $assigns = get_users_from_role_on_context($role, $context);
        
    foreach($assigns as $assign){
        $user = $DB->get_record('user', array('id' => $assign->userid), 'id, username, email, emailstop, firstname, lastname, mailformat');
        $ticket = ticket_generate($user, $purpose, $url);
        $notification = str_replace('<%%TICKET%%>', $ticket, $notification);
        $notification_html = str_replace('<%%TICKET%%>', $ticket, $notification_html);
    
        // todo send the email to user
        if ($CFG->debugsmtp) echo "Sending Mail Notification to " . fullname($user) . '<br/>'.$notification;
        email_to_user($user, $sender, $title, $notification, $notification_html);
    }
}

/**
* generates a direct access ticket for this user.
* @param int $userid the ID of the user to whom the ticket must be made for
* @param string $reason the reason of the ticket
* @param string $url the access URL the user will be redirected to after validating his return ticket. 
*
*/
function ticket_generate($user, $reason, $url){
    global $CFG, $DB;
    
    $ticket->username = $user->username;
    $ticket->reason = $reason;
    $ticket->wantsurl = $url;
    $ticket->date = time();
    
    $keyinfo = json_encode($ticket);
    
    $pkey = substr(base64_encode(@$CFG->passwordsaltmain), 0,32);

    $sql = "
        SELECT
            HEX(AES_ENCRYPT('$keyinfo', '$pkey')) as result
    ";

    if($result = $DB->get_record_sql($sql)){
        $encrypted = $result->result;
    } else {
        $encrypted = 'encryption error';
    }

    return base64_encode($encrypted); // make sure we can emit this ticket through an URL
}

/**
* decodes a direct access ticket for this user.
* @param string $encrypted the received ticket
*
*/
function ticket_decodeTicket($encrypted){
    global $CFG, $DB;
    
    $encrypted = base64_decode($encrypted);
        
    $pkey = substr(base64_encode(@$CFG->passwordsaltmain), 0,32);    
    $sql = "
        SELECT
            AES_DECRYPT(UNHEX('$encrypted'), '$pkey') as result
    ";

    if($result = $DB->get_record_sql($sql)){
        $decrypted = $result->result;
    } else {
        $decrypted = 'encryption error';
    }

    if (debugging() && function_exists('debug_trace')){
        debug_trace(str_replace('/', "\\/", $decrypted));
    }

    if (!$ticket = json_decode(str_replace('/', "\\/", $decrypted))){
        error("Ticket deserializing error");
    }
    
    return $ticket;
}

/**
* gives the timeguard of the ticket.
*
*/
function ticket_get_timeguard(){
    return set_config('tickettimeguard', 'auth/ticket');
}

?>