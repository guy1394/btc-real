<?php

class request
{
  private $result;
  private $url;
  private $obj;
  
  public function __construct( $url, $get )
  {
    assert($this->obj = curl_init());
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
  }
  
  public function __destruct()
  {
    curl_close($curl);    
  }
  
  public function Post()
  {
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "a=4&b=7");
    $this->result = curl_exec($curl);
  }
  
  public function Result()
  {
    return $this->result;
  }
}
