<?php

class request
{
  private $result;
  private $url;
  private $obj;
  private $cookie;
  
  public function __construct( $url, $get = null )
  {
    assert($this->obj = curl_init());
    curl_setopt($this->obj, CURLOPT_URL, $url);
    curl_setopt($this->obj, CURLOPT_RETURNTRANSFER,true);
    $this->cookie = null;
  }
  
  public function __set( $name, $val )
  {
    assert($name == 'cookie');
    $this->ChangeCookieFile($val);
  }
  
  public function __get( $name )
  {
    assert($name == 'cookie');
    return $this->cookie;
  }
  
  private function ChangeCookieFile( $name = null)
  {
    if ($name === null)
      $name = tempnam(null, 'btcec_');
    fclose(fopen($name, "a"));
    
    $this->cookie = $name;
    curl_setopt($this->obj, CURLOPT_COOKIEFILE, $this->cookie);
    curl_setopt($this->obj, CURLOPT_COOKIEJAR,  $this->cookie);
  }
  
  public function __destruct()
  {
    curl_close($this->obj);    
  }
  
  public function Post( $post = null )
  {
    if ($post !== null)
    {
      curl_setopt($this->obj, CURLOPT_POST, true);
      curl_setopt($this->obj, CURLOPT_POSTFIELDS, $post);
    }
    return $this->Get();
  }
  
  public function Get( )
  {
    $this->result = curl_exec($this->obj);
    return $this;
  }
  
  public function Result()
  {
    return $this->result;
  }
}
