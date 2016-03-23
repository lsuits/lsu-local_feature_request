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
 * @package   local_feature_request
 * @copyright 2016 Louisiana State University, Jason Peak, Philip Cali, Adam Zapletal, Dave Elliott, Robert Russo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once $CFG->libdir . '/formslib.php';

class feature_request_form extends moodleform {
    function definition() {
        global $USER;

        $_s = function($key, $a = null) {
            return get_string($key, 'local_feature_request', $a);
        };

        $m =& $this->_form;

        $params = array('size' => 60);
        $m->addElement('text', 'email_subject', $_s('email_subject'), $params);
        $m->setDefault('email_subject', $_s('default_subject'));
        $m->setType('email_subject', PARAM_TEXT);

        $params = array('rows' => 10, 'cols' => 69);
        $m->addElement('textarea', 'email_body', $_s('email_body'), $params);
        $m->setType('email_body', PARAM_TEXT);
        
        $disclaimer = get_config('local_feature_request', 'disclaimer');

        if ($disclaimer) {
            $m->addElement('static', 'disclaimer', $_s('disclaimer'), get_string('default_disclaimer', 'local_feature_request'));
        }

        $buttons = array(
            $m->createElement('submit', 'submit', $_s('send')),
            $m->createElement('cancel')
        );

        $m->addGroup($buttons, 'buttons', '&nbsp;', array(' '), false);
    }
}
