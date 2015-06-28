<?php

require_once __DIR__.'/vendor/autoload.php';
function add_action( $name, callable $func ) {
  throw new \Exception('WordPress add_action');
}

function register_setting( $group, $name ) {
  throw new \Exception('WordPress register_setting');
}

function plugins_url( $filepath, $relative_to='' ) {
  throw new \Exception('WordPress plugins_url');
}

function add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ) {
  throw new \Exception('WordPress add_menu_page');
}

function add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function ) {
  throw new \Exception('WordPress add_submenu_page');
}
