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

// Work in progress...

// jshint unused:false, undef:false

define(['jquery', 'core/log', 'core/config'], function($, log, cfg) {

    var authticket = {

        pushtoclipboard: function(spanobjid, button) {
            var $span = $('#' + spanobjid);
            var $button = $(button);

            // Approche moderne via Clipboard API
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText($span.text())
                    .then(function() {
                        $button.css('background-color', '#00FF00');
                        log.debug('pushtoclipboard: copied via Clipboard API');
                    })
                    .catch(function(err) {
                        log.error('pushtoclipboard: Clipboard API failed', err);
                    });
                return;
            }

            // Fallback legacy pour navigateurs sans Clipboard API
            var range, selection;
            var spanobj = $span.get(0);

            if (window.getSelection && document.createRange) {
                selection = window.getSelection();
                range = document.createRange();
                range.selectNodeContents(spanobj);
                selection.removeAllRanges();
                selection.addRange(range);
            } else if (document.selection && document.body.createTextRange) {
                range = document.body.createTextRange();
                range.moveToElementText(spanobj);
                range.select();
            }

            var ret = document.execCommand('copy');
            if (ret) {
                $button.css('background-color', '#00FF00');
                log.debug('pushtoclipboard: copied via execCommand');
            } else {
                log.warn('pushtoclipboard: execCommand copy failed');
            }
        }
    };
    
    return authticket;
});