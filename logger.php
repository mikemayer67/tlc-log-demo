<?php
namespace TLC\LogDemo;

if( ! defined('WPINC') ) { die; }

const LOG_FILE = 'tlc-log-demo.log';

class Logger
{
  private static $_instance = null;

  static function instance() {
    if( self::$_instance == null ) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  private function __construct() {
    $logfile = plugin_path(LOG_FILE);
    if( file_exists($logfile) and filesize($logfile) > 512*1024 ) {
      $tempfile = $logfile.".tmp";
      $fp = fopen($tempfile,"w");
      $skip = 1000;
      foreach(file($logfile) as $line) {
        if($skip > 0) {
          $skip--;
        } else {
          fwrite($fp,$line);
        }
      }
      fclose($fp);
      unlink($logfile);
      rename($tempfile,$logfile);
    }
    $this->fp = fopen($logfile,"a");
  }

  function __destruct() {
    fclose($this->fp);
  }


  function add($prefix,$msg) {
    $datetime = new \DateTime;
    $timestamp = $datetime->format("d-M-y H:i:s.v e");
    $prefix = str_pad($prefix,8);
    fwrite($this->fp, "[{$timestamp}] {$prefix} {$msg}\n");
  }

  function dump($level)
  {
    echo "<table>";
    $entry_re = '/^\[(.*?)\]\s*(\w+)\s*(.*?)\s*$/';
    foreach(file(plugin_path(LOG_FILE)) as $line) {
      $m = array();
      if(preg_match($entry_re,$line,$m))
      {
        if($m[2] == $level) {
          echo "<tr>";
          echo "<td>" . $m[1] . "</td>";
          echo "<td>" . $m[3] . "</td>";
          echo "</tr>";
        }
      }
    }
    echo "</table>";
  }

  function clear()
  {
    $file = plugin_path(LOG_FILE);
    fclose($this->fp);
    unlink($file);
    $this->fp = fopen($file,"a");
  }
}


function log_clear()
{
  Logger::instance()->clear();
}

function log_info($msg) {
  Logger::instance()->add("INFO",$msg);
}

function log_warning($msg) {
  Logger::instance()->add("WARNING",$msg);
}

function log_error($msg) {
  Logger::instance()->add("ERROR",$msg);
}

function dump_info() {
  Logger::instance()->dump("INFO");
}

function dump_warnings() {
  Logger::instance()->dump("WARNING");
}

function dump_errors() {
  Logger::instance()->dump("ERROR");
}
