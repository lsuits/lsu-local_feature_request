<?php

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
