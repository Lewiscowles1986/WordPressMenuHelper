<?php

require_once __DIR__.'/vendor/autoload.php';
function add_action( $name, callable $func ) {
  echo WordPress add_action '{$name}'\n";
}

function register_setting( $group, $name ) {
  echo "WordPress register_setting '{$setting}'->'{$name}'\n";
}

function plugins_url( $filepath, $relative_to='' ) {
  echo "WordPress plugins_url\n";
}

function add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ) {
  echo "WordPress add_menu_page\n";
}

function add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function ) {
  echo "WordPress add_submenu_page\n";
}
