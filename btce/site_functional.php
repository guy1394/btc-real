<?php

class btce_site_functional
{
  private $cookie;
  private $token;

  public function __construct()
  {
    $this->cookie = null;
    $this->token = null;
  }

  function tryLogin( $pow = null )
  {
    $otp = '-';  
    if ($pow === null)
      $pow = '';

    global $config;
    $email = $config->btce_user;
    $password = $config->btce_password;

    $arg = array("email" => $email, "password" => $password, "otp" => $otp, "PoW_nonce" => $pow);
    return $this->BTCERequest('https://btc-e.com/ajax/login.php', $arg);
  }
  
  public function Login()
  {
    $obj = $this->tryLogin();
    $pow = $this->getPoW($obj['data']['work']['target'], $obj['data']['work']['data']);
    $obj = $this->tryLogin($pow);
    assert($obj['data']['login_success'] == 1);
    return true;
  }

  private function BTCERequest( $url, $post, $decode = true )
  {
    $request = new request($url);
    $request->cookie = $this->cookie;
    $res = $request->Post($post)->Result();
    $this->cookie = $request->cookie;
    if ($decode === false)
      return $res;
    return json_decode($res, true);  
  }
  
  public function OpenQiwiBill( $number, $amount, $id )
  {
    $arg = array("amount" => $amount, "to" => $number);
    $c = $this->BTCERequest('https://btc-e.com/ajax/qiwi.php', $arg);

    global $config;
    $params = array(
      "txn_id" => $c['txn_id'],
      "from" => $c['from'],
      "to" => $number,
      "summ" => $amount,
      "com" => $c['com'],
      "lifetime" => $config->qiwi_recept_lifetime_hours,
      "check_agt" => "false",
      "successUrl" => $config->qiwi_success_url."/$id/",
      "failUrl" => $config->qiwi_fail_url."/$id/",
      );
    $redirect = "http://w.qiwi.ru/setInetBill.do?".http_build_query($params);
    return $redirect;
    var_dump($params);
    var_dump($redirect);
  }
  
  public function GetOrders()
  {
    $arg = array("act" => "orders_update", "pair" => 17);
    return $this->BTCERequest('https://btc-e.com/ajax/order.php', $arg);
  }
  
  public function GetMinPrice()
  {
    return $this->GetOrders()['min'];
  }
  
  public function BuyBitcoin( $rur_amount, $price )
  {
    $token = $this->Token();
    $btc_count = $rur_amount / $price;
    $arg = array("trade" => "buy", "btc_count" => $btc_count, "btc_price" => $price, "pair" => 17, "token" => $token);
    $obj = $this->BTCERequest('https://btc-e.com/ajax/order.php', $arg);
    assert(false); // i dont really know what expect here
  }
  
  public function WithdrawBitcoin( $address, $amount )
  {
    $token = $this->Token();
    $arg = array("act" => "withdraw", "sum" => $amount, "address" => $address, "coin_id" => 1, "token" => $token, "otp" => 0);
    $obj = $this->BTCERequest('https://btc-e.com/ajax/coins.php', $arg);
    assert(false);
  }

  private function getPoW( $a, $b )
  {
    $c = 0;
    do
    {
      $hash_hex = md5(md5($b.$c));
      $hash = hexdec($hash_hex);
      ++$c;
    } while($hash >= $a);
    
    return $c;
  }
  
  private function GetSiteToken()
  {
    $html = $this->BTCERequest('https://btc-e.com/', null, false);
    list($garbage, $prefetch) = explode("<input id='token' type='hidden' value='", $html);
    list($token) = explode("'", $prefetch);
    return $token;
  }
  
  private function Token()
  {
    if ($this->token != null)
      return $this->token;
    return $this->token = $this->GetSiteToken();
  }
}