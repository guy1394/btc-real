<?php

class btce_api_functional
{
  public function __construct()
  {
  }
  
  public function __call($name, $arguments)
  {
    return $this->btce_query($name, $arguments);
  }
  
  public function SearchQiwiDeposit( $amount )
  {
    $obj = $this->TransHistory();
    var_dump($obj);
    exit();
    if (!count($obj['return']))
      return false;
    foreach ($obj['return'] as $id => $tr)
      if ($tr['currency'] == 'RUR' && $tr['amount'] == $amount)
        break;
    unset($obj);
    if ($tr['amount'] != $amount)
      return false;
    $tr['id'] = $id;
    return $tr;
  }
  
  private function btce_query($method, array $req = array())
  {
    global $config;
    // API settings
    $key = $config->btce_api_key; // your API-key
    $secret = $config->btce_api_secret; // your Secret-key

    $req['method'] = $method;
    $mt = explode(' ', microtime());
    $req['nonce'] = $mt[1];
   
    // generate the POST data string
    $post_data = http_build_query($req, '', '&');

    $sign = hash_hmac("sha512", $post_data, $secret);

    // generate the extra headers
    $headers = array(
            'Sign: '.$sign,
            'Key: '.$key,
    );

    // our curl handle (initialize if required)
    static $ch = null;
    if (is_null($ch)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; BTCE PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
    }
    curl_setopt($ch, CURLOPT_URL, 'https://btc-e.com/tapi/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    // run the query
    $res = curl_exec($ch);
    if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
    $dec = json_decode($res, true);
    if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists');
    return $dec;
  }
}