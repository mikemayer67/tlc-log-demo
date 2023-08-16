<?php
namespace TLC\LogDemo;

if( ! defined('WPINC') ) { die; }
if( ! is_admin() ) { return; }

require_once 'logger.php';

const PAGE_SLUG = 'tlc-log-demo';

function handle_admin_menu()
{
  add_options_page(
    'Log Demo', // page title
    'Log Demo', // menu title
    'manage_options', // required capability
    PAGE_SLUG, // settings page slug
    ns('populate_settings_page'), // callback to populate settingsn page
  );
}

function add_settings_link($links)
{
  $options_url = admin_url('options-general.php');
  $options_url .= "?page=".PAGE_SLUG;
  array_unshift($links,"<a href='$options_url'>Log</a>");
  return $links;
}

$action_links = 'plugin_action_links_' . plugin_basename(plugin_file());

add_action('admin_menu',  ns('handle_admin_menu'));
add_action($action_links, ns('add_settings_link'));

function populate_settings_page()
{
  $title = esc_html(get_admin_page_title());
  echo "<div class=wrap>";
  echo "<h1>$title</h1>";
  echo "<h2>Errors</h2>";
  dump_errors();
  echo "<h2>Warnings</h2>";
  dump_warnings();
  echo "<h2>Info</h2>";
  dump_info();
  echo "</div>";
}
