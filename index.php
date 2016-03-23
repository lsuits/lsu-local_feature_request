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

require_once '../../config.php';
require_once $CFG->dirroot . '/local/feature_request/form.php';

require_login();

$_s = function($key, $a = null) {
    return get_string($key, 'local_feature_request', $a);
};

$heading = $_s('pluginname');

$url = new moodle_url('/local/feature_request/');

$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_heading("$SITE->shortname: $heading");
$PAGE->set_title($heading);
$PAGE->navbar->add($SITE->shortname);
$PAGE->navbar->add($heading);
$feature_form = new feature_request_form();

if ($feature_form->is_cancelled()) {
    redirect(new moodle_url('/my'));
} else if ($data = $feature_form->get_data()) {
    if (empty($data->email_subject)) {
        $data->email_subject = $_s('default_subject');
    }

    $subject = $data->email_subject;
    $body  = sprintf("Originating host: %s\n\n",$CFG->wwwroot);
    $body .= get_config('local_feature_request', 'body_prepend') .
        "\n\n" . $data->email_body . "\n\n\n\n"
            . get_string('user_info_append', 'local_feature_request') . "\n\n"
            . get_string('username', 'local_feature_request') . $USER->username . " \n\n "
            . get_string('firstname', 'local_feature_request') . $USER->firstname . "\n\n"
            . get_string('lastname', 'local_feature_request') . $USER->lastname;

    $to_user = new stdClass;
    $to_user->email = get_config('local_feature_request', 'email');
    $to_user->id = $USER->id;
    $to_user->mailformat = 0;

    if (email_to_user($to_user, $USER, $subject, $body)) {
        $success = $_s('success');
    } else {
        $failure = $_s('failure');
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading($heading);

if (!empty($success)) {
    echo $OUTPUT->notification($success, 'notifysuccess');
} else if (!empty($failure)) {
    echo $OUTPUT->notification($failure);
}

$feature_form->display();

echo $OUTPUT->footer();
