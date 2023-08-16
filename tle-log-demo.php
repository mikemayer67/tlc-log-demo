<?php
namespace TLC\LogDemo;

/**
 * Plugin Name: TLC Log Demo
 * Plugin URI: https://github.com/mikemayer67/tlc-log-demo
 * Description: Plugin for testing ability to create/read a plugin log file
 * Version: 0.0.1
 * Author: Michael A. Mayer
 * Requires PHP: 5.3.0
 * License: GPLv3
 * License URL: https://www.gnu.org/licenses/gpl-3.0.html
 */

if( ! defined('WPINC') ) { die; }

function ns($s)
{
  return __NAMESPACE__.'\\'.$s;
}

function plugin_file()
{
  return __FILE__;
}

function plugin_dir()
{
  return plugin_dir_path(__FILE__);
}

function plugin_path($path)
{
  return plugin_dir() . '/' . $path;
}

function plugin_url($rel_url)
{
  return plugin_dir_url(__FILE__).'/'.$rel_url;
}

require_once 'logger.php';


function handle_activate()
{
  log_clear();
  log_info('activate: '.__NAMESPACE__);
}

function handle_deactivate()
{
  log_warning('deactivate: '.__NAMESPACE__);
}

function handle_uninstall()
{
  log_info('uninstall: '.__NAMESPACE__);
}

register_activation_hook(   __FILE__, ns('handle_activate') );
register_deactivation_hook( __FILE__, ns('handle_deactivate') );
register_uninstall_hook(    __FILE__, ns('handle_uninstall') );

if( is_admin() ) /* Admin setup */
{
  log_error("not really... just testing");
  require_once plugin_path('admin.php');
}
else
{
  add_shortcode('tlc-log-demo',ns('handle_shortcode'));
}

function handle_shortcode($attr,$content=null,$tag=null)
{
  $rval = "handle_shortcode(";
  $rval .= print_r($attr,true) . ', ';
  $rval .= print_r($content,true) . ', ';
  $rval .= print_r($tag,true) . ')';
  log_info($rval);
  return $rval;
}
