<?php

include_once('request.php');
include_once('config.php');

error_reporting(E_ALL); ini_set('display_errors','On'); ini_set('display_startup_errors','On');
class btce
{
  private $cookie;
  
  public function __btce()
  {
    $this->cookie = null;
  }
  
  function tryLogin( $pow = null )
  {
    $otp = '-';  
    if ($pow === null)
      $pow = '';

    global $config;
    $email = $config->btce_user;
    $password = $config->btce_password;

    $request = new request('https://btc-e.com/ajax/login.php');
    $request->cookie = $this->cookie;
    $arg = array("email" => $email, "password" => $password, "otp" => $otp, "PoW_nonce" => $pow);
    $res = $request->Post($arg)->Result();
    $this->cookie = $request->cookie;
    var_dump($res);
    return json_decode($res, true);
  }
  
  public function Login()
  {
    $obj = $this->tryLogin();
    $pow = $this->getPoW($obj['data']['work']['target'], $obj['data']['work']['data']);
    $obj = $this->tryLogin($pow);
  }

  function getPoW($a, $b)
  {
    $c=0;
    do
    {
      $hash_hex=md5(md5($b.$c));
      $hash= hexdec($hash_hex);
      ++$c;
    } while($hash>=$a);
    return $c;
  }
}

$a = new  btce();
$a->Login();
