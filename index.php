<?php

require_once '../../config.php';
require_once 'form.php';

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
        "\n\n" . $data->email_body;

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
