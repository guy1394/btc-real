<?php

include_once('request.php');
include_once('config.php');

error_reporting(E_ALL); ini_set('display_errors','On'); ini_set('display_startup_errors','On');
class btce
{
  private $site_functional;
  public function __construct()
  {
    include_once('btce/site_functional.php');
    $this->site_functional = new btce_site_functional();
    $this->site_functional->Login();
  }
  
  public function OpenQiwiBill( $number, $amount )
  {
    $this->site_functional->OpenQiwiBill($number, $amount);
  }
  
  public function WithdrawBitcoin( $address, $amount )
  {
    $this->site_functional->WithdrawBitcoin($address, $amount);
  }
  
  public function BuyBitcoin( $rur_amount, $price )
  {
    $this->site_functional->BuyBitcoin($rur_amount, $price);
  }

  public function TransHistory()
  {
    assert(false); // need token $token
    $arg = array("method" => "TransHistory", "nonce" => time(), "since" => time() - 3600 * 72, "token" => $token);
    $obj = $this->BTCERequest('https://btc-e.com/tapi', $arg);
    return $obj['return'];
  }
  
  public function SearchAmountInHistory( $amount )
  {
    foreach ($this->TransHistory() as $id => $trans)
      if ($trans['currency'] == 'RUR' && $trans['amount'] == $amount)
        return $id;
    return false;
  }
}