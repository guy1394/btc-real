<?php

class request
{
  private $result;
  private $url;
  private $obj;
  
  public function __construct( $url, $get = null )
  {
    assert($this->obj = curl_init());
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
  }
  
  public function __destruct()
  {
    curl_close($curl);    
  }
  
  public function Post( $post )
  {
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    $this->result = curl_exec($curl);
    return $this;
  }
  
  public function Result()
  {
    return $this->result;
  }
}
