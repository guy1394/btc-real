<?php

include_once('request.php');

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

    $request = new request('https://btc-e.com/ajax/login.php');
    $request->cookie = $this->cookie;
    var_dump($request->cookie);
    $arg = array("email" => $email, "password" => $password, "otp" => $otp, "PoW_nonce" => $pow);
    var_dump(http_build_query($arg));
    $res = $request->Post(http_build_query($arg))->Result();
    $this->cookie = $request->cookie;
    return json_decode($res, true);
  }
  
  public function Login()
  {
    $obj = $this->tryLogin();
    var_dump($obj);
    $pow = $this->getPoW($obj['data']['work']['target'], $obj['data']['work']['data']);
    $obj = $this->tryLogin($pow);
    var_dump($obj);
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
