<?php

if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1')
  define("_ip_", $_SERVER['REMOTE_ADDR']);
else
  define("_ip_", explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"])[0]);

error_reporting(E_ALL); ini_set('display_errors', '1');

define("_sub", isset($_GET['sub']) ? "/".$_GET['sub'] : "");
  
function phoxy_conf()
{
  $ret = phoxy_default_conf();
  $ret['site'] = 'http://btc/';
  $ret['api_dir'] .= _sub;
  return $ret;
}

include_once('config.php');
include_once('connect.php');
include_once('phoxy/index.php');
