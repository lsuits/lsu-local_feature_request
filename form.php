<?php

require_once $CFG->libdir . '/formslib.php';

class feature_request_form extends moodleform {
    function definition() {
        global $USER;

        $_s = function($key, $a = null) {
            return get_string($key, 'local_feature_request', $a);
        };

        $m =& $this->_form;

        $m->addElement('text', 'email_subject', $_s('email_subject'));
        $m->setDefault('email_subject', $_s('default_subject'));

        $m->addElement('textarea', 'email_body', $_s('email_body'));

        $disclaimer = get_config('local_feature_request', 'disclaimer');

        if ($disclaimer) {
            $m->addElement('static', 'disclaimer', $_s('disclaimer'), $disclaimer);
        }

        $buttons = array(
            $m->createElement('submit', 'submit', get_string('send')),
            $m->createElement('cancel')
        );

        $m->addGroup($buttons, 'buttons', '', array(' '), false);
    }
}
