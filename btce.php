<?php

include_once('request.php');

class btce
{
  function tryLogin( $pow = null )
  {
    if ($pow === null)
      $otp = '-', $pow = '';
    else
      $otp = '';

    $request = new request('https://btc-e.com/ajax/login.php');
    $arg = array("email" => $email, "password" => $password, "otp" => $otp, "PoW_nonce" => $pow);
    $res = $request->Post($arg)->Result();
    return json_decode($res, true);
  }
  
  function Login()
  {
    $obj = $this->tryLogin();
    $pow = $this->getPoW($obj['data']['work']['target'], $obj['data']['work']['data']);
    $obj = $this->tryLogin($pow);
  }

  function getPoW(a, b)
  {
    var c=0;
    do
    {
      hash_hex=md5(md5(b+c));
      hash=eval("(0x"+hash_hex+")");
      ++c;
    } while(hash>=a);
    return c;
  }
}
