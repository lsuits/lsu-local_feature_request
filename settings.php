<?php

defined('MOODLE_INTERNAL') or die();

if ($hassiteconfig) {
    $setting_page = new admin_settingpage(
        'local_feature_request', get_string('pluginname', 'local_feature_request')
    );

    $admins = get_admins();
    $admin = empty($admins) ? '' : reset($admins)->email;

    $setting_page->add(new admin_setting_configtext(
        'local_feature_request/email',
        get_string('email', 'local_feature_request'),
        get_string('email_help', 'local_feature_request'),
        $admin
    ));

    $setting_page->add(new admin_setting_configtextarea(
        'local_feature_request/body_prepend',
        get_string('body_prepend', 'local_feature_request'),
        get_string('body_prepend_help', 'local_feature_request'),
        ''
    ));

    $setting_page->add(new admin_setting_configtextarea(
        'local_feature_request/disclaimer',
        get_string('disclaimer', 'local_feature_request'),
        get_string('disclaimer_help', 'local_feature_request'),
        get_string('default_disclaimer', 'local_feature_request')
    ));

    $ADMIN->add('localplugins', $setting_page);
}
