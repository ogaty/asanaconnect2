<?php
/*
Plugin Name: asanaconnect
Description: asana personal token
Version: 1.0
Author: Yuji Ogata
Author URI: https://ogatism.jp
License: MIT
*/

require_once 'html.php';
require_once 'hook.php';

if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'register_mysettings' );
}

function register_mysettings() { // whitelist options
  add_options_page(
  'asanaconnect',	//ページタイトル
  'asanaconnect',	//設定メニューに表示されるメニュータイトル
  'manage_options',	//権限
  'asanaconnect',	//設定ページのURL。options-general.php?page=sample_setup_page
  'asanaconnect_html'	//設定ページのHTMLをはき出す関数の定義
  );
}

