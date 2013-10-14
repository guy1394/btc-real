<?php

include_once('request.php');
include_once('config.php');

error_reporting(E_ALL); ini_set('display_errors','On'); ini_set('display_startup_errors','On');
class btce
{
  private $site_functional;
  private $api_functional;
  public function __construct()
  {
    include_once('btce/site_functional.php');
    $this->site_functional = new btce_site_functional();
    $this->site_functional->Login();
    
    include_once('btce/api_functional.php');
    $this->api_functional = new btce_api_functional();
  }
  
  public function OpenQiwiBill( $number, $amount, $id )
  {
    return $this->site_functional->OpenQiwiBill($number, $amount, $id);
  }
  
  public function WithdrawBitcoin( $address, $amount )
  {
    return $this->site_functional->WithdrawBitcoin($address, $amount);
  }
  
  public function BuyBitcoin( $rur_amount, $price )
  {
    return $this->site_functional->BuyBitcoin($rur_amount, $price);
  }

  public function SearchAmountInHistory( $amount )
  {
    return $this->api_functional->SearchQiwiDeposit($amount);
  }
}