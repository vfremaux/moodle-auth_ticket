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
 * External database auth sync tests, this also tests adodb drivers
 * that are matching our four supported Moodle database drivers.
 *
 * @package     auth_ticket
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2016 Valery Fremaux
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace auth_ticket;

// Abusive PSR12 rule : adds useless spaces in string concatenation.
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceBefore
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceAfter

use advanced_testcase;
use auth_plugin_ticket;
use Stdclass;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot.'/auth/ticket/auth.php');

/**
 * Test case for the plugin.
 */
final class ticket_test extends advanced_testcase {
    /** @var auth_plugin_manual Keeps the authentication plugin. */
    protected $authplugin;

    /** @var stdClass Keeps authentication plugin config */
    protected $config;

    /**
     * Setup test data.
     */
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
        $this->authplugin = new auth_plugin_ticket();
        set_config('shortvaliditydelay', 2, 'auth_ticket');
        set_config('longvaliditydelay', 24, 'auth_ticket');
        set_config('persistantvaliditydelay', 90, 'auth_ticket');
        set_config('usessl', 0, 'auth_ticket');
        $this->authplugin->config = get_config(auth_plugin_ticket::COMPONENT_NAME);
    }

    /**
     * Tests encryption/decrypt
     * @covers ::ticket_generate
     * @covers ::ticket_decode
     * @covers \auth_plugin_ticket::validate_timeguard
     */
    public function test_plugin(): void {
        global $CFG;

        $this->resetAfterTest(true);

        /** @var auth_plugin_db $auth */
        $auth = get_auth_plugin('ticket');

        // Generate some test users.
        $user = $this->getDataGenerator()->create_user();

        // Test ticket encode/decode.

        // Test with Internal.  DES needs SQL AES_ENCRYPT() and AES_DECRYPT().
        $reason = 'Self test';
        $url = $CFG->wwwroot;

        $ticket = ticket_generate($user, $reason, $url, 'internal', 'short');
        $decoded = ticket_decode($ticket, 'internal');
        $this->assertTrue($decoded != null);
        $this->assertEquals($user->username, $decoded->username);
        $this->assertEquals($url, str_replace('\\', '', $decoded->wantsurl));
        $this->assertEquals($reason, $decoded->reason);
        $this->assertEquals('short', $decoded->term);

        $validate = $auth->validate_timeguard($decoded);
        // Check it's fresh and valid.
        $this->assertTrue($validate);
        // Make it obsolete.
        $decoded->date -= $this->authplugin->config->shortvaliditydelay * (int) HOURSECS + 10;
        $validate = $auth->validate_timeguard($decoded);
        $this->assertFalse($validate);

        $reason = 'Quoted \'reason\'';
        $ticket = ticket_generate($user, $reason, $url, 'internal', 'long');
        $decoded = ticket_decode($ticket, 'internal');
        $this->assertTrue($decoded != null);
        $this->assertEquals($user->username, $decoded->username);
        $this->assertEquals($url, str_replace('\\', '', $decoded->wantsurl));
        $this->assertEquals($reason, $decoded->reason);
        $this->assertEquals('long', $decoded->term);

        $validate = $auth->validate_timeguard($decoded);
        // Check it's fresh and valid.
        $this->assertTrue($validate);
        // Make it obsolete.
        $decoded->date -= $this->authplugin->config->longvaliditydelay * (int) DAYSECS + 10;
        $validate = $auth->validate_timeguard($decoded);
        $this->assertFalse($validate);

        $reason = 'Quoted \'reason\'';
        $ticket = ticket_generate($user, $reason, $url, 'internal', 'persistant');
        $decoded = ticket_decode($ticket, 'internal');
        $this->assertTrue($decoded != null);
        $this->assertEquals($user->username, $decoded->username);
        $this->assertEquals($url, str_replace('\\', '', $decoded->wantsurl));
        $this->assertEquals($reason, $decoded->reason);
        $this->assertEquals('persistant', $decoded->term);

        $validate = $auth->validate_timeguard($decoded);
        // Check it's fresh and valid.
        $this->assertTrue($validate);
        // Make it obsolete.
        $decoded->date -= 2000 * (int) DAYSECS + 10;
        $validate = $auth->validate_timeguard($decoded);
        $this->assertFalse($validate);

        // Test with RSA.
        if (function_exists('openssl_public_encrypt') && ($CFG->mnet_dispatcher_mode === 'strict')) {
            $reason = 'Self test';
            $url = $CFG->wwwroot;

            $ticket = ticket_generate($user, $reason, $url, 'rsa', 'short');
            $decoded = ticket_decode($ticket, 'rsa');
            $this->assertTrue($decoded != null);
            $this->assertEquals($user->username, $decoded->username);
            $this->assertEquals($url, str_replace('\\', '', $decoded->wantsurl));
            $this->assertEquals($reason, $decoded->reason);
            $this->assertEquals('short', $decoded->term);
        }
    }
}
