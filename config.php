<?php

class config
{
  private $settings;
  public function config()
  {
    $file = file_get_contents(".htconfig");
    $this->settings = json_decode($file, true);
  }
  
  public function __get( $name )
  {
    assert(isset($this->settings[$name]));
    return $this->settings[$name];
  }
}

$config = new config();