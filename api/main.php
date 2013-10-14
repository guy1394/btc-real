<?php

class main extends api
{
  private $btce;
  private static $cource = 4500;
  
  public function __construct()
  {
    api::__construct();
    $this->btce = null;
  }
  
  protected function Reserve()
  {
    //if (_ip_ == '213.21.7.6')
    //  return $this->Request(400, 9213243303, 'wallet');
    return array("error" => "Coming soon");
  }
  
  private function btce()
  {
    if ($this->btce !== null)
      return $this->btce;
    include_once('btce.php');
    return $this->btce = new btce();
  }
  
  protected function Request( $rur_amount, $phone, $wallet )
  {
    $btc = $rur_amount / $cource;
    if ($btc < 0.1)
      return array("error" => "Минимальная сумма покупки ".(0.1 * $cource));
    $row = db::Query("SELECT \"AddQiwi\"($1::currency, $2::currency, $3::varchar, $4::varchar, $5::inet)",
      array($rur_amount, $cource, $phone, $wallet, _ip_), true);
    $id = $row['AddQiwi'];
    $row = $this->GetBill($id);
    
    $actual_price = db::Query("SELECT * FROM allowed_qiwi_orders WHERE amount=$1", $row['rur']);
    $bill = $this->btce()->OpenQiwiBill($row['phone'], $actual_price['to_pay'], $id);
    $data = array("id" => $id, "url" => $bill);
    return array(
      'error' => var_export($data, true),
      'data' => $data,
      'reset' => $bill
      );
  }
  
  private function GetBill( $id )
  {
    return db::Query("SELECT rur, phone FROM qiwi2btc WHERE id=$1", array($id), true);
  }
  
  protected function MakeOrder( $id, $order )
  {
    $bill = $this->GetBill($id);
    assert(count($bill));
    $res = $this->btce()->SearchAmountInHistory($bill['rur']);
    var_dump($res);
    assert(false);
  }
}