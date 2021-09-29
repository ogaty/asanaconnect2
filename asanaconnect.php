<?php
/*
Plugin Name: asanaconnect
Description: asana personal token sample
Version: 1.0
Author: Yuji Ogata
Author URI: https://ogatism.jp
License: MIT
*/

require_once 'html.php';
require_once 'hook.php';

add_action('admin_menu', 'register_mysettings');
add_action('admin_init', 'init_asana');

function register_mysettings()
{
    add_options_page(
        'asanaconnect',    // page title
        'asanaconnect',    // menu title
        'manage_options',  // 権限
        'asanaconnect',    // 設定ページのURL。
        'asanaconnect_html'    // callback
    );
}

function init_asana()
{
    register_setting('asanaconnect', 'asana_personal_token');
    register_setting('asanaconnect', 'asana_project_gid');
    register_setting('asanaconnect2', 'asana_user_task_lists');
    add_settings_section(
        'asana_settings_section_info',
        'API Settings',
        'asana_settings_section_info_callback',
        'asanaconnect'
    );

    add_settings_field(
        'asana_personal_token',
        'Personal Token',
        'asana_settings_callback',
        'asanaconnect',
        'asana_settings_section_info'
    );

    add_settings_field(
        'asana_project_gid',
        'Project gid',
        'asana_gid_callback',
        'asanaconnect',
        'asana_settings_section_info'
    );

    add_settings_section(
        'asana_settings_section_dashboard',
        'Dashboard Settings',
        'asana_settings_section_dashboard_callback',
        'asanaconnect2'
    );

    add_settings_field(
        'asana_user_task_lists',
        'UserTaskList gid(カンマ区切り)',
        'asana_user_task_list_callback',
        'asanaconnect2',
        'asana_settings_section_dashboard'
    );
}


function asana_settings_section_info_callback()
{
}

function asana_settings_section_dashboard_callback()
{
}

function asana_settings_callback()
{
    $token = get_option('asana_personal_token');
    echo '<input type="text" name="asana_personal_token" id="asana_personal_token" value="' . $token . '">';
}

function asana_gid_callback()
{
    $gid = get_option('asana_project_gid');
    echo '<input type="text" name="asana_project_gid" id="asana_project_gid" value="' . $gid . '">';
}

function asana_user_task_list_callback()
{
    $user_task_lists = get_option('asana_user_task_lists');
    echo '<input type="text" name="asana_user_task_lists" id="asana_user_task_lists" value="' . $user_task_lists . '">';
}
