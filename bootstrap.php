<?php

require_once __DIR__.'/vendor/autoload.php';
function add_action( $name, callable $func ) {
  trigger_error('WordPress add_action', E_USER_NOTICE);
}

function register_setting( $group, $name ) {
  trigger_error('WordPress register_setting', E_USER_NOTICE);
}

function plugins_url( $filepath, $relative_to='' ) {
  trigger_error('WordPress plugins_url', E_USER_NOTICE);
}

function add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ) {
  trigger_error('WordPress add_menu_page', E_USER_NOTICE);
}

function add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function ) {
  trigger_error('WordPress add_submenu_page', E_USER_NOTICE);
}
